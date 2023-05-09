<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Models\User;
use App\Traits\Api\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

   
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $input = $request->validated();
            $user = User::query()->where('email', $input['email'])->first();

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $input['email'],
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            if ($user) {
                $name = $user['name'];
                //Mail send to Agent.
                Mail::send('mail.api.password-send', ['name' => $name, 'token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
                return $this->sendSuccessResponse('Success', 'We have e-mailed your password reset link!');
            } else {
                $errorMessages = [['status' => Response::HTTP_UNAUTHORIZED,'title'=>'Given data is not valid.','detail'=>null,'source'=>null]];
                return $this->sendErrorResponse('Given data is not valid.', $errorMessages, Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $errorMessages = [['status' => Response::HTTP_INTERNAL_SERVER_ERROR,'title'=>$e->getMessage(),'detail'=>null,'source'=>null]];
            return $this->sendErrorResponse('Something went wrong', $errorMessages, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.passwords.forgetPasswordLink', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\RedirectResponse()
     */
    public function submitResetPasswordForm(ResetPasswordRequest $request)
    {
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withErrors(['email' => 'Invalid entry']);
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return view('auth.passwords.thankyou', ['message' => 'Your password has been changed successfully!']);
    }
}
