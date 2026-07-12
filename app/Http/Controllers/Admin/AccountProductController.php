<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountProduct\StoreAccountProductRequest;
use App\Http\Requests\Admin\AccountProduct\UpdateAccountProductRequest;
use App\Http\Requests\Admin\AccountProduct\UpdateAccountProductStatusRequest;
use App\Http\Resources\Admin\AccountProduct\AccountProductResource;
use App\Services\AccountProduct\AccountProductService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AccountProductService $products,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->products->list($request->integer('per_page', 15));

        return $this->success(
            message: 'Account products retrieved successfully.',
            data: AccountProductResource::collection($products),
        );
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->products->find($id);

        return $this->success(
            message: 'Account product retrieved successfully.',
            data: new AccountProductResource($product),
        );
    }

    public function store(StoreAccountProductRequest $request): JsonResponse
    {
        try {
            $product = $this->products->create($request->validated());

            return $this->success(
                message: 'Account product created successfully and is pending approval.',
                data: new AccountProductResource($product),
                responseCode: '000',
                statusCode: 201,
            );
        } catch (ValidationException $e) {
            return $this->error(
                message: $e->getMessage(),
                responseCode: '101',
                statusCode: 422,
                errors: $e->errors(),
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error(
                message: 'We are unable to process your request please try again.',
                responseCode: '500',
                statusCode: 500,
            );
        }
    }

    public function update(UpdateAccountProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->products->find($id);
            $product = $this->products->update($product, $request->validated());

            return $this->success(
                message: 'Account product updated successfully.',
                data: new AccountProductResource($product),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account product was not found.',
                responseCode: '404',
                statusCode: 404,
            );
        } catch (ValidationException $e) {
            return $this->error(
                message: $e->getMessage(),
                responseCode: '101',
                statusCode: 422,
                errors: $e->errors(),
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error(
                message: 'We are unable to process your request please try again.',
                responseCode: '500',
                statusCode: 500,
            );
        }
    }

    public function approve(int $id): JsonResponse
    {
        try {
            $product = $this->products->find($id);
            $product = $this->products->approve($product);

            return $this->success(
                message: 'Account product approved successfully.',
                data: new AccountProductResource($product),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account product was not found.',
                responseCode: '404',
                statusCode: 404,
            );
        } catch (ValidationException $e) {
            return $this->error(
                message: $e->getMessage(),
                responseCode: '101',
                statusCode: 422,
                errors: $e->errors(),
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error(
                message: 'We are unable to process your request please try again.',
                responseCode: '500',
                statusCode: 500,
            );
        }
    }

    public function updateStatus(UpdateAccountProductStatusRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->products->find($id);
            $product = $this->products->updateStatus($product, $request->validated()['status']);

            return $this->success(
                message: 'Account product status updated successfully.',
                data: new AccountProductResource($product),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account product was not found.',
                responseCode: '404',
                statusCode: 404,
            );
        } catch (ValidationException $e) {
            return $this->error(
                message: $e->getMessage(),
                responseCode: '101',
                statusCode: 422,
                errors: $e->errors(),
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error(
                message: 'We are unable to process your request please try again.',
                responseCode: '500',
                statusCode: 500,
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $product = $this->products->find($id);

            $this->products->delete($product);

            return $this->success(
                message: 'Account product deleted successfully.',
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account product was not found.',
                responseCode: '404',
                statusCode: 404,
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->error(
                message: 'We are unable to process your request please try again.',
                responseCode: '500',
                statusCode: 500,
            );
        }
    }
}
