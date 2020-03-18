<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCustomerAPIRequest;
use App\Http\Requests\API\UpdateCustomerAPIRequest;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustomerController
 * @package App\Http\Controllers\API
 */

class CustomerAPIController extends AppBaseController
{
    /** @var  CustomerRepository */
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepository = $customerRepo;
        $this->middleware('auth.jwt', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Display a listing of the Customer.
     * GET|HEAD /customers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $customers = $this->customerRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($customers->toArray(), 'Customers retrieved successfully');
    }

    /**
     * Store a newly created Customer in storage.
     * POST /customers
     *
     * @param CreateCustomerAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCustomerAPIRequest $request)
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (!$user->can('create', Customer::class)) {
            return $this->sendError('Forbidden', 403);
        }

        $input = $request->all();

        /** @var Customer $customer */
        $customer = Customer::create($input);

        return $this->sendResponse($customer->toArray(), 'Customer saved successfully');
    }

    /**
     * Display the specified Customer.
     * GET|HEAD /customers/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Customer $customer */
        $customer = Customer::find($id);

        if (empty($customer)) {
            return $this->sendError('Customer not found');
        }

        return $this->sendResponse($customer->toArray(), 'Customer retrieved successfully');
    }

    /**
     * Update the specified Customer in storage.
     * PUT/PATCH /customers/{id}
     *
     * @param int $id
     * @param UpdateCustomerAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateCustomerAPIRequest $request)
    {
        /** @var Customer $customer */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($customer = Customer::find($id))) {
            return $this->sendError('Customer not found');
        } else if (!$user->can('update', $customer)) {
            return $this->sendError('Forbidden', 403);
        }
        $input = $request->all();
        $customer->update($input);

        return $this->sendResponse($customer->toArray(), 'Customer updated successfully');
    }

    /**
     * Remove the specified Customer from storage.
     * DELETE /customers/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Customer $customer */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($customer = Customer::find($id))) {
            return $this->sendError('Customer not found');
        } else if (!$user->can('update', $customer)) {
            return $this->sendError('Forbidden', 403);
        }

        $customer->delete();

        return $this->sendSuccess('Customer deleted successfully');
    }
}
