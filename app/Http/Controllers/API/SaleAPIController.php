<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSaleAPIRequest;
use App\Http\Requests\API\UpdateSaleAPIRequest;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\SaleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class SaleController
 * @package App\Http\Controllers\API
 */

class SaleAPIController extends AppBaseController
{
    /** @var  SaleRepository */
    private $saleRepository;

    public function __construct(SaleRepository $saleRepo)
    {
        $this->saleRepository = $saleRepo;
        $this->middleware('auth.jwt', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Display a listing of the Sale.
     * GET|HEAD /sales
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sales = $this->saleRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($sales->toArray(), 'Sales retrieved successfully');
    }

    /**
     * Store a newly created Sale in storage.
     * POST /sales
     *
     * @param CreateSaleAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateSaleAPIRequest $request)
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (!$user->can('create', Sale::class)) {
            return $this->sendError('Forbidden', 403);
        }
        $input = $request->all();

        /** @var Sale $sale */
        $sale = Sale::create($input);

        return $this->sendResponse($sale->toArray(), 'Sale saved successfully');
    }

    /**
     * Display the specified Sale.
     * GET|HEAD /sales/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Sale $sale */
        $sale = Sale::find($id);

        if (empty($sale)) {
            return $this->sendError('Sale not found');
        }

        return $this->sendResponse($sale->toArray(), 'Sale retrieved successfully');
    }

    /**
     * Update the specified Sale in storage.
     * PUT/PATCH /sales/{id}
     *
     * @param int $id
     * @param UpdateSaleAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateSaleAPIRequest $request)
    {
        /** @var Sale $sale */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($sale = Sale::find($id))) {
            return $this->sendError('Sale not found');
        } else if (!$user->can('update', $sale)) {
            return $this->sendError('Forbidden', 403);
        }

        $input = $request->all();
        $sale->update($input);

        return $this->sendResponse($sale->toArray(), 'Sale updated successfully');
    }

    /**
     * Remove the specified Sale from storage.
     * DELETE /sales/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Sale $sale */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($sale = Sale::find($id))) {
            return $this->sendError('Sale not found');
        } else if (!$user->can('delete', $sale)) {
            return $this->sendError('Forbidden', 403);
        }

        $sale->delete();

        return $this->sendSuccess('Sale deleted successfully');
    }
}
