<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Offer;
use App\Models\User;
use App\Traits\response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    use response;

    public function save(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            return $this->jsonResponseMessage('The user already exists', false);
        } else {
            //$verificationCode = $this->generateVerificationCode();
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            //$user->addresses = $request->input('addresses');
            /*            $data = [
                            'name' => $user->name,
                            'email' => $user->email,
                            'verification_code' => $verificationCode,
                        ];*/
            if ($user->save()) {
                $token = $user->createToken("token")->plainTextToken;
                //MailController::sendEmail($data);
                return $this->jsonResponseMessage('User saved successfully', data: [
                    $user,
                    'token' => $token,
                    'offers' => (new OffersController)->allOffers(),
                ]);
            } else {
                return $this->jsonResponseMessage('Something went wrong', false);
            }
        }

    }

    public function customLogin(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if($user){
            $token = $user->createToken("token")->plainTextToken;
            return $this->jsonResponseMessage('Login Successful', true, data: [
                $user,
                'token' => $token,
                'offers' => (new OffersController)->allOffers(),
            ]);
        }else{
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($user->save()) {
                $token = $user->createToken("token")->plainTextToken;
                //MailController::sendEmail($data);
                return $this->jsonResponseMessage('User saved successfully', data: [
                    $user,
                    'token' => $token,
                    'offers' => (new OffersController)->allOffers(),
                ]);
            } else {
                return $this->jsonResponseMessage('Something went wrong', false);
            }
        }

    }

    public function getUserData(Request $request): JsonResponse
    {
        return $this->jsonResponseMessage('User data loaded successfully', data: [
           'user' => $request->user(),
           'offers' => (new OffersController)->allOffers(),
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            if (Hash::check($request->input('password'), $user->password)) {
                $token = $user->createToken("token")->plainTextToken;
                return $this->jsonResponseMessage('Login Successful', true, data: [
                    $user,
                    'token' => $token,
                    'offers' => (new OffersController)->allOffers(),
                ]);
            } else {
                return $this->jsonResponseMessage('Invalid Password', false);
            }
        } else {
            return $this->jsonResponseMessage('User does not exist', false);
        }
    }

    public function sendMail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'email|required']);
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            Mail::to($user)->send(new ResetPasswordMail($user->name));
            return $this->jsonResponseMessage('Password reset link has been sent. Check your inbox');
        } else {
            return $this->jsonResponseMessage('User does not exist. Please try again', success: false);
        }

    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return match ($status) {
            Password::RESET_LINK_SENT => $this->jsonResponseMessage('Password reset link has been sent successfully. Check your inbox'),
            Password::INVALID_USER => $this->jsonResponseMessage('Invalid user, please try again', false),
            Password::RESET_THROTTLED => $this->jsonResponseMessage("Link has already been sent. Please try again", false),
            default => $this->jsonResponseMessage('Internal Error', false),
        };
    }


}
