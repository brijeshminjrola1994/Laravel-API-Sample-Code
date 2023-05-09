<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateCompanyRequest;
use App\Http\Requests\Api\GetCompanyRequest;
use App\Models\Company;
use App\Traits\Api\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Api\UpdateCompanyRequest;
use App\Http\Requests\Api\DeleteCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;


    // store company data
    public function store(CreateCompanyRequest $request)
    {
        try {
            $input = $request->validated();
            $input['user_id'] = Auth::user()->id;
            // Insert Company Record
            $company = Company::create($input);
            return $this->sendSuccessResponse($company, 'Company created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // update company data
    public function update(UpdateCompanyRequest $request)
    {
        try {
            $input = $request->validated();
            $company = Company::find($input['id']);
            $input['user_id'] = Auth::user()->id;
            // Update Company Record.
            $company->update($input);
            // Insert Company Construction License Type.
            return $this->sendSuccessResponse($company, 'Company updated successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // delete company data
    public function delete(DeleteCompanyRequest $request) {
        try {
            $input = $request->validated();
            Company::where('id', $input['id'])->delete();
            return $this->sendSuccessResponse([], 'Company deleted successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
