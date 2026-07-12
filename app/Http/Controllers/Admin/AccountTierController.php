<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountTier\StoreAccountTierRequest;
use App\Http\Requests\Admin\AccountTier\UpdateAccountTierRequest;
use App\Http\Requests\Admin\AccountTier\UpdateAccountTierStatusRequest;
use App\Http\Resources\Admin\AccountTier\AccountTierResource;
use App\Services\AccountTier\AccountTierService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountTierController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AccountTierService $tiers,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $tiers = $this->tiers->list($request->integer('per_page', 15));

        return $this->success(
            message: 'Account tiers retrieved successfully.',
            data: AccountTierResource::collection($tiers),
        );
    }

    public function show(int $id): JsonResponse
    {
        $tier = $this->tiers->find($id);

        return $this->success(
            message: 'Account tier retrieved successfully.',
            data: new AccountTierResource($tier),
        );
    }

    public function store(StoreAccountTierRequest $request): JsonResponse
    {
        try {
            $tier = $this->tiers->create($request->validated());

            return $this->success(
                message: 'Account tier created successfully and is pending approval.',
                data: new AccountTierResource($tier),
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

    public function update(UpdateAccountTierRequest $request, int $id): JsonResponse
    {
        try {
            $tier = $this->tiers->find($id);
            $tier = $this->tiers->update($tier, $request->validated());

            return $this->success(
                message: 'Account tier updated successfully.',
                data: new AccountTierResource($tier),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account tier was not found.',
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
            $tier = $this->tiers->find($id);
            $tier = $this->tiers->approve($tier);

            return $this->success(
                message: 'Account tier approved successfully.',
                data: new AccountTierResource($tier),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account tier was not found.',
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

    public function updateStatus(UpdateAccountTierStatusRequest $request, int $id): JsonResponse
    {
        try {
            $tier = $this->tiers->find($id);
            $tier = $this->tiers->updateStatus($tier, $request->validated()['status']);

            return $this->success(
                message: 'Account tier status updated successfully.',
                data: new AccountTierResource($tier),
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account tier was not found.',
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
            $tier = $this->tiers->find($id);

            $this->tiers->delete($tier);

            return $this->success(
                message: 'Account tier deleted successfully.',
            );
        } catch (ModelNotFoundException $e) {
            return $this->error(
                message: 'The requested account tier was not found.',
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
