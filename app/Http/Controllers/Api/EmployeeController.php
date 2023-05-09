<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Api\CreateEmployeeRequest;
use App\Http\Requests\Api\UpdateEmployeeRequest;
use App\Http\Requests\Api\DeleteEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function store(CreateEmployeeRequest $request)
    {
        try {
            $input = $request->validated();
            // Insert Employee Record
            $employee = Employee::create($input);
            return $this->sendSuccessResponse($employee, 'Employee Created Successfully.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateEmployeeRequest $request)
    {
        try {
            $input = $request->validated();
            $employee = Employee::find($request->id);
            if (!$employee) {
                $errorMessages = [['status' => Response::HTTP_NOT_FOUND, 'title'  => 'Employee not found', 'detail' => null, 'source' => null]];
                return $this->sendErrorResponse('Employee not found',  $errorMessages, Response::HTTP_NOT_FOUND);
            }
            // Update employee Record.
            $employee->update($input);
            return $this->sendSuccessResponse($employee, 'Employee Updated Successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     // delete employee data
     public function delete(DeleteEmployeeRequest $request) {
        try {
            $input = $request->validated();
            Employee::where('id', $input['id'])->delete();
            return $this->sendSuccessResponse([], 'Employee Deleted Successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
