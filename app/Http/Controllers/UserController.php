<?php


namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JWTAuth;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::get(), 200);
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $phone = $request->phone;
        $password = $request->password;

        if(isset($email) && isset($password)){
            // check if any field is empty
            if(empty($email) or empty($password)){
                return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
            }
            $credentials = request(['email', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Email or Password is invalid'], 401);
            }
            return $this->respondWithToken($token);
        }

        if(isset($phone) && isset($password)){
            // check if any field is empty
            if(empty($phone) or empty($password)){
                return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
            }
            $credentials = request(['phone', 'password']);
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Phone or Password is invalid'], 401);
            }
            return $this->respondWithToken($token);

        }
    }
         /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

public function register(Request $request)
    {


        $password = $request->password;


        $rules = [
            'name' => 'required|json',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone|starts_with:010,011,012,015',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // create new user
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = app('hash')->make($password);

            if($user->save()){
                // will call login method
                return $this->login($request);
            }
        } catch(Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
