<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait Responses
{
    /**
     * Success response with data
     */
    protected function success($data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Success response without data
     */
    protected function successMessage(string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $status);
    }

    /**
     * Error response
     */
    protected function error(string $message = 'Something went wrong', int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Validation error response
     */
    protected function validationError(Validator $validator): JsonResponse
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $field => $messages) {
            $errors[$field] = $messages[0]; // First error message only
        }

        return $this->error('Validation failed', 422, $errors);
    }

    /**
     * Custom validation error
     */
    protected function validationErrors(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Not found response
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Created response
     */
    protected function created($data, string $message = 'Created successfully'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Updated response
     */
    protected function updated($data, string $message = 'Updated successfully'): JsonResponse
    {
        return $this->success($data, $message, 200);
    }

    /**
     * Deleted response
     */
    protected function deleted(string $message = 'Deleted successfully'): JsonResponse
    {
        return $this->successMessage($message, 200);
    }

    /**
     * Pagination response
     */
    protected function paginated($data, $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
            ...$meta,
        ]);
    }
}
