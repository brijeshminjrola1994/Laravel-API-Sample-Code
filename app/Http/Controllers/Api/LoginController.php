<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use League\OAuth2\Server\Exception\OAuthServerException;

class LoginController extends Controller
{

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    // Login
    public function login(LoginRequest $request)
    {
        try {
            $input = $request->validated();
            if ($input['login_type'] == 'email' && Auth::attempt(['email' => $input['email'], 'password' => $input['password'], 'is_app_user' => 1])) {
                $user =  auth()->user();
                User::where('id', $user->id)->update(['login_type' => $input['login_type']]);
                $success['access_token'] = $user->createToken(config('app.name'))->accessToken;
                $success['user'] = $user;
                $success['company'] = Company::with('subContractors:id,parent_id,company_name')->where('user_id', auth()->user()->id)->first();
            } else if ($input['login_type'] == 'line' || $input['login_type'] == 'apple') {
                // get first app user
                $user = User::where('is_app_user',1)->first();
                if (empty($user)) {
                    $errorMessages = [['status' => Response::HTTP_UNAUTHORIZED, 'title' => 'Given data is not valid.', 'detail' => null, 'source' => null]];
                    return $this->sendErrorResponse('Given data is not valid.', $errorMessages, Response::HTTP_UNAUTHORIZED);
                }
                $success['access_token'] = $user->createToken(config('app.name'))->accessToken;
                $success['user'] = $user;
                $success['company'] = Company::with('subContractors:id,parent_id,company_name')->where('user_id', $user->id)->first();
                // }
            } else {
                $errorMessages = [['status' => Response::HTTP_UNAUTHORIZED, 'title' => 'Given data is not valid.', 'detail' => null, 'source' => null]];
                return $this->sendErrorResponse('Given data is not valid.', $errorMessages, Response::HTTP_UNAUTHORIZED);
            }
            return $this->sendSuccessResponse($success, 'User login successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong!', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // logout 
    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $tokens = Auth::user()->tokens->pluck('id');
                Token::whereIn('id', $tokens)->update(['revoked' => true]);
                RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
                return $this->sendSuccessResponse([], 'User logout successfully.');
            } else {
                $errorMessages = [['status' => Response::HTTP_UNAUTHORIZED, 'title' => 'Unauthorized', 'detail' => null, 'source' => null]];
                return $this->sendErrorResponse('Unauthorized.', $errorMessages, Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'title' => $e->getMessage(), 'detail' => null, 'source' => null]];
            return $this->sendErrorResponse('Something went wrong',  $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
