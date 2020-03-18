<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBrandAPIRequest;
use App\Http\Requests\API\UpdateBrandAPIRequest;
use App\Models\Brand;
use App\Models\User;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class BrandController
 * @package App\Http\Controllers\API
 */

class BrandAPIController extends AppBaseController
{
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepository = $brandRepo;
        $this->middleware('auth.jwt', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Display a listing of the Brand.
     * GET|HEAD /brands
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $brands = $this->brandRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($brands->toArray(), 'Brands retrieved successfully');
    }

    /**
     * Store a newly created Brand in storage.
     * POST /brands
     *
     * @param CreateBrandAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateBrandAPIRequest $request)
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (!$user->can('create', Brand::class)) {
            return $this->sendError('Forbidden', 403);
        }

        $input = $request->all();

        /** @var Brand $brand */
        $brand = Brand::create($input);

        return $this->sendResponse($brand->toArray(), 'Brand saved successfully');
    }

    /**
     * Display the specified Brand.
     * GET|HEAD /brands/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Brand $brand */
        $brand = Brand::find($id);

        if (empty($brand)) {
            return $this->sendError('Brand not found');
        }

        return $this->sendResponse($brand->toArray(), 'Brand retrieved successfully');
    }

    /**
     * Update the specified Brand in storage.
     * PUT/PATCH /brands/{id}
     *
     * @param int $id
     * @param UpdateBrandAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateBrandAPIRequest $request)
    {
        /** @var Brand $brand */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($brand = Brand::find($id))) {
            return $this->sendError('Brand not found');
        } else if (!$user->can('update', $brand)) {
            return $this->sendError('Forbidden', 403);
        }

        $input = $request->all();
        $brand->update($input);

        return $this->sendResponse($brand->toArray(), 'Brand updated successfully');
    }

    /**
     * Remove the specified Brand from storage.
     * DELETE /brands/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Brand $brand */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($brand = Brand::find($id))) {
            return $this->sendError('Brand not found');
        } else if (!$user->can('delete', $brand)) {
            return $this->sendError('Forbidden', 403);
        }

        $brand->delete();

        return $this->sendSuccess('Brand deleted successfully');
    }
}
