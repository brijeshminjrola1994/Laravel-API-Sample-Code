<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\Api\ResponseTrait;
use Log;

class RegisterController extends Controller
{

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function register(RegisterRequest $request)
    {
        try {
            $input = $request->validated();
            $input['password'] = Hash::make($input['password']);
            $input['is_app_user'] = 1;
            User::create($input);
            $user = User::where('email', $input['email'])->first();
            $success['access_token'] = $user->createToken(config('app.name'))->accessToken;
            $success['user'] = $user;
            return $this->sendSuccessResponse($success, 'User registered successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR,'title'=>$e->getMessage(),'detail'=>null,'source'=>null]];
            return $this->sendErrorResponse('Something went wrong!', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
