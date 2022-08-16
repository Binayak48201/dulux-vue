<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Tools\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @group Authentication
 *
 * API endpoints for managing authentication
 */
class AuthController extends Controller
{
    /**
     *
     * Registers users and email verification with otp code
     * @bodyParam name string required string max:255 name of the user
     * @bodyParam email string required string email max:255 unique:users email of the user,
     * @bodyParam phone_number string  required min:10 max:10 phone no.of the user
     * @bodyParam gender string required enum[Male,Femail,Other],
     * @bodyParam age Number required
     * @bodyPaam address  required ,
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Http\JsonResponse|Response
     */
    public function register(Request $request)
    {
        // validate the incoming request
        $validator = $this->getValidator($request);

        if ($validator->fails()) {
            $data = $this->getMessage('error', $validator->errors(), []);
            if (request()->wantsJson()) {
                $response = Response::prepare(true, $data['message'], $data, []);
                return response()->json($response, 422);
            }
        }

        $request['password'] = Hash::make($request['password']);

        $request['remember_token'] = Str::random(10);

        $request['confirmation_token'] = Str::limit(md5($request['email'] . Str::random()), 5, '');

        $request['user_type'] = 3; // Indicates Normal Users

        $request['otp_code'] = rand(111111, 999999);

        $user = User::create($request->toArray());

        //Send mail to use email
//        Mail::to($user)->send(new PleaseConfirmYourEmail($user));

        $data = $this->getMessage('success', 'We have send OTP to your email address.', []);

        $res = Response::prepare(false, 'Registration success', $data, []);
        return response()->json($res, 200);
    }

    /**
     * Log in the user.
     * @bodyParam   email    string  required    The email of the  user.      Example: user@infodevelopers.com.np
     * @bodyParam   password    string  required    The password of the  user.   Example: password
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @response {
     *  "user": {
     *     "id" : "userId",
     *     "name": "userName",
     *     "email": "userEmail",
     *      "user_type" : "userRoleId",
     *      "verified_status : "otp and email verification status",
     *      "kyc_status : "kyc_verification_status",
     *      "status" => "userStatus",
     * },
     * "token" : "yJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiM"
     *
     * }
     */
    public function login(Request $request, $provider = null)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => [$validator->errors()->all()]], 404);
        }
        try {
            $user = User::where(['email' => $request->email])->firstOrFail();
            if (!$user) {
                $data = ['message' => 'These credentials do not match our records.'];
                if (request()->wantsJson()) {
                    $res = Response::prepare(true, 'These credentials do not match our records.', $data, []);
                    return response($res, 403);
                }
            }
//            if (!$user->confirmed && $user->user_type === 3) {
//                // $data = $this->getMessage('error', 'Please Confirm your email address.', []);
//                $data = ['message' => 'Please Confirm your email address.'];
//                if (request()->wantsJson()) {
//                    $res = Response::prepare(true, 'Please Confirm your email address', $data, []);
//                    return response($res, 403);
//                }
//            }
//            if ($user->status === 0) {
//                // $data = $this->getMessage('error', 'You account has been disabled', []);
//                $data = ['message' => 'You account has been disabled'];
//                if (request()->wantsJson()) {
//                    $res = Response::prepare(true, 'You account has been disabled', $data, []);
//                    return response($res, 403);
//                }
//            }

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $userData = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
                $response = [
                    'user' => $userData,
                    'token' => $token,
                    'status' => 'success'
                ];
                $res = Response::prepare(false, 'login success', $response, []);
                return response($res, 201);
            }
            // Password Do no match.
            // $data = $this->getMessage('error', 'These credentials do not match our records.', []);
            $data = ['message' => 'These credentials do not match our records.'];

            if (request()->wantsJson()) {
                $res = Response::prepare(true, 'These credentials do not match our records.', $data, []);
                return response($res, 403);
            }

        } catch (\Throwable $e) {
            $data = ['message' => 'These credentials do not match our records.'];
            if (request()->wantsJson()) {
                $res = Response::prepare(true, 'These credentials do not match our records.', $e->getMessage(), []);
                return response($res, 403);
            }
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function getValidator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => [
                'required',
                'min:10',
                'max:10',
                'unique:users,phone_number',
            ],
            'gender' => 'required',
            'age' => 'required',
            'address' => 'required',
        ]);
    }

    /**
     * @param $response
     * @param $message
     * @param array $data
     * @return array
     */
    private function getMessage($response, $message, array $data)
    {
        return [
            'status' => $response,
            'message' => $message,
            'data' => $data,
        ];
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function confirmPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            $data = ['errors' => $validator->errors()->all()];
            $res = Response::prepare(true, 'validation failed', $data, []);
            return response($res, 422);
        }
        $user = User::where('email', request('email'))->first();

        if (!$user) {
            $response = ["message" => 'Unknown User.'];
            $res = Response::prepare(true, 'Unknown User', $response, []);
            return response()->json($res, 422);
        }

        $user->createToken('Laravel Password Grant Client')->accessToken;

        $user->update([
            'password' => Hash::make(request('password'))
        ]);

        $response = ["message" => 'Your account has been created!', 'status' => 'success'];

        $res = Response::prepare(false, 'Your account has been created!', $response, []);
        return response($res, 200);
    }

    /**
     * Auth User Must be confirmed to change the password.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_confirm_password' => 'required|same:new_password',
        ]);
        $data = $request->all();
        $user = User::find(auth()->user()->id);
        if (!Hash::check($data['current_password'], $user->password)) {
            $returnData = Response::prepare(true, 'You have entered wrong password', [], []);
            return response()->json($returnData, 500);
        } else {
            auth()->user()->update(['password' => Hash::make($data['new_password'])]);
//            event(new RevokeTokenOfVerifiedUser(auth()->user()));
            $returnData = Response::prepare(false, 'Password Updated.', $data, []);
            return response()->json($returnData, 200);
        }
    }


    public function generateToken(User $user)
    {
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'verified_status' => $user->confirmed,
            'kyc_status' => $user->kycStatus,
            'kyc_submitted' => !!$user->kyc,
            'status' => $user->status,
            'address' => $user->address,
            'age' => $user->age,
            'phone_number' => $user->phone_number,
            'gender' => $user->gender,
            'avatar' => $user->avatar ? asset($user->avatar) : asset('images/logo.99c6004d.png'),
            'email_otp_status' => $user->email_otp_status,
            'sms_otp_status' => $user->sms_otp_status,
        ];
        $response = [
            'user' => $userData,
            'token' => $token,
            'device_key' => $user->device_key,
            'status' => 'success'
        ];

        $res = Response::prepare(false, 'login success', $response, []);

        return response()->json($res, 200);
    }
}


