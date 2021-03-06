<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEmployeeAPIRequest;
use App\Http\Requests\API\UpdateEmployeeAPIRequest;
use App\Models\Employee;
use App\Models\User;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class EmployeeController
 * @package App\Http\Controllers\API
 */

class EmployeeAPIController extends AppBaseController
{
    /** @var  EmployeeRepository */
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepository = $employeeRepo;
        $this->middleware('auth.jwt', [
            'except' => [
                'index',
                'show'
            ]
        ]);
    }

    /**
     * Display a listing of the Employee.
     * GET|HEAD /employees
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $employees = $this->employeeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($employees->toArray(), 'Employees retrieved successfully');
    }

    /**
     * Store a newly created Employee in storage.
     * POST /employees
     *
     * @param CreateEmployeeAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateEmployeeAPIRequest $request)
    {
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (!$user->can('create', Employee::class)) {
            return $this->sendError('Forbidden', 403);
        }

        $input = $request->all();

        /** @var Employee $employee */
        $employee = Employee::create($input);

        return $this->sendResponse($employee->toArray(), 'Employee saved successfully');
    }

    /**
     * Display the specified Employee.
     * GET|HEAD /employees/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Employee $employee */
        $employee = Employee::find($id);

        if (empty($employee)) {
            return $this->sendError('Employee not found');
        }

        return $this->sendResponse($employee->toArray(), 'Employee retrieved successfully');
    }

    /**
     * Update the specified Employee in storage.
     * PUT/PATCH /employees/{id}
     *
     * @param int $id
     * @param UpdateEmployeeAPIRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateEmployeeAPIRequest $request)
    {
        /** @var Employee $employee */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($employee = Employee::find($id))) {
            return $this->sendError('Employee not found');
        } else if (!$user->can('update', $employee)) {
            return $this->sendError('Forbidden', 403);
        }
        $input = $request->all();
        $employee->update($input);

        return $this->sendResponse($employee->toArray(), 'Employee updated successfully');
    }

    /**
     * Remove the specified Employee from storage.
     * DELETE /employees/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Employee $employee */
        /** @var User $user */
        if (!$user = Auth::user()) {
            return $this->sendError('Unauthenticated', 401);
        } else if (empty($employee = Employee::find($id))) {
            return $this->sendError('Employee not found');
        } else if (!$user->can('delete', $employee)) {
            return $this->sendError('Forbidden', 403);
        }

        $employee->delete();

        return $this->sendSuccess('Employee deleted successfully');
    }
}
