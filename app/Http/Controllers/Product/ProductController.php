<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Traits\Responses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use Responses;

    /* =======================
    | WEB PAGES
    ======================== */

   public function index()
{
    $products = Product::where('seller_id', Auth::id())
        ->with(['images', 'category'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('seller.products.index', compact('products'));
}


    public function create()
    {
        $categories = ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('seller.products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $categories = ProductCategory::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $product->load(['images', 'category']);

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /* =======================
    | IMAGE UPLOAD (STEP 1) - LOCAL STORAGE ✅ FIXED
    ======================== */

    public function uploadImagesFirst(Request $request)
    {
        try {
            Log::info('Image upload started', ['files' => $request->allFiles()]);

            $request->validate([
                'images' => ['required', 'array', 'min:1', 'max:8'],
                'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
            ]);

            $files = $request->file('images');
            
            if (!$files || empty($files)) {
                return $this->error('No images received', 422);
            }

            $urls = [];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $filename, 'public');
                    $urls[] = $path; // 🔥 STORE PATH ONLY: "products/abc.png"
                    Log::info('Image uploaded', ['path' => $path]);
                }
            }

            if (empty($urls)) {
                return $this->error('No valid images processed', 422);
            }

            return $this->success(['image_urls' => $urls], count($urls) . ' images uploaded');

        } catch (ValidationException $e) {
            return $this->validationErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Image upload failed', ['error' => $e->getMessage()]);
            return $this->error('Upload failed: ' . $e->getMessage(), 500);
        }
    }

    /* =======================
    | STORE (STEP 2)
    ======================== */

    public function store(Request $request)
    {
        return $this->createProduct($request);
    }

    /* =======================
    | CREATE PRODUCT (2-STEP FLOW) - JSON FIXED ✅
    ======================== */

    public function createProduct(Request $request)
    {
        try {
            $data = $request->validate([
                'category_id' => ['required', 'integer', 'exists:product_categories,id'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'short_description' => ['nullable', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0.01'],
                'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
                'cost_price' => ['nullable', 'numeric', 'min:0'],
                'brand' => ['required', 'string', 'max:100'],
                'stock_quantity' => ['required', 'integer', 'min:0'],
                'stock_alert' => ['nullable', 'integer', 'min:0'],
                'manage_stock' => ['nullable', 'boolean'],
                'image_urls' => ['required', 'string'], // 🔥 Accept JSON string
            ]);

            // 🔥 DECODE JSON STRING TO ARRAY
            $imageUrls = json_decode($request->input('image_urls'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($imageUrls) || empty($imageUrls)) {
                return $this->validationErrors(['image_urls' => ['Invalid image URLs format']]);
            }

            if (count($imageUrls) > 8) {
                return $this->validationErrors(['image_urls' => ['Maximum 8 images allowed']]);
            }

            $user = Auth::user();
            abort_if(!$user || $user->type !== 'seller', 403);

            /* ---------- REQUIRED DB FIELDS ---------- */
            $data['seller_id'] = $user->id;
            $data['image_urls'] = $imageUrls; // 🔥 Add decoded paths

            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $data['slug'] = $slug;

            $data['sku'] = strtoupper(Str::random(10));

            $data = array_merge($data, [
                'status' => 'pending',
                'is_visible' => true,
                'is_featured' => false,
                'is_active' => true,
            ]);

            /* ---------- TRANSACTION FOR ATOMICITY ---------- */
            return DB::transaction(function () use ($data) {
                $product = Product::create($data);

                // Bulk insert images from PATHS
                $imageData = collect($data['image_urls'])->map(function ($path, $index) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'path' => $path, // "products/abc.png"
                        'is_primary' => $index === 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                ProductImage::insert($imageData);

                Log::info('Product created successfully', ['product_id' => $product->id]);
                
                return $this->created(
                    $product->fresh(['images', 'category']),
                    'Product created successfully.'
                );
            });

        } catch (ValidationException $e) {
            Log::error('Product validation failed:', $e->errors());
            return $this->validationErrors($e->errors());
        } catch (\Throwable $e) {
            Log::error('Product creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->error('Failed to create product: ' . $e->getMessage(), 500);
        }
    }

    /* =======================
    | UPDATE - LOCAL STORAGE ✅
    ======================== */

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return $this->error('Unauthorized', 403);
        }

        try {
            $data = $request->validate([
                'category_id' => ['required', 'integer', 'exists:product_categories,id'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'short_description' => ['nullable', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0.01'],
                'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
                'cost_price' => ['nullable', 'numeric', 'min:0'],
                'brand' => ['required', 'string', 'max:100'],
                'stock_quantity' => ['required', 'integer', 'min:0'],
                'stock_alert' => ['nullable', 'integer', 'min:0'],
                'manage_stock' => ['nullable', 'boolean'],
                'is_active' => ['nullable', 'boolean'],
                'images' => ['nullable', 'array', 'max:8'],
                'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]);

            $product->update($data);

            // Handle new images (LOCAL STORAGE)
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('products', $filename, 'public');
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'path' => $path, // 🔥 PATH ONLY
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            return $this->success(
                $product->fresh(['images', 'category']),
                'Product updated successfully.'
            );

        } catch (ValidationException $e) {
            return $this->validationErrors($e->errors());
        } catch (\Exception $e) {
            return $this->error('Failed to update product: ' . $e->getMessage(), 500);
        }
    }

    /* =======================
    | DELETE & OTHER ACTIONS
    ======================== */

    public function destroy(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            return $this->error('Unauthorized', 403);
        }

        DB::transaction(function () use ($product) {
            $product->images()->delete();
            $product->delete();
        });

        return $this->success(null, 'Product deleted successfully.');
    }

    public function toggleStatus(Request $request, Product $product)
{
    if (!$product || $product->seller_id !== Auth::id()) {
        return $this->error('Unauthorized', 403);
    }

    // 🔥 3-STATE LOGIC: pending → active → inactive → active
    $currentStatus = $product->status;
    $nextStatus = match($currentStatus) {
        'pending' => 'active',
        'active' => 'inactive',
        'inactive' => 'active',
        default => 'pending'
    };

    $product->update(['status' => $nextStatus]);

    return $this->success(
        $product->fresh(['images', 'category']),
        "Status changed to " . ucfirst($nextStatus)
    );
}


    public function deleteImage(Request $request, ProductImage $image)
    {
        $product = $image->product;

        if (!$product || $product->seller_id !== Auth::id()) {
            return $this->error('Unauthorized', 403);
        }

        $image->delete();

        return $this->success(null, 'Image deleted successfully.');
    }
}
