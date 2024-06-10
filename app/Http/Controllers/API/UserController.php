<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class UserController extends Controller
{

    //function to create user
    public function CreateUser(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => "required | string",
            'phone' => "required | numeric | digits:10",
            'email' => "required | string | unique:users",
            'password' => "required | min:6",
        ]);

        if ($validator->fails()) {
            $result = array(
                'staus' => false,
                'message' => 'Validation error occured',
                "error_message" => $validator->errors()
            );
            return response()->json($result, 400); //bad request
        }

        $user = user::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user->id) {
            $result = array(['staus' => true, 'message' => 'User Register', "data" => $user]);
            $responseCode = 200; // success request
        } else {
            $result = array(['staus' => false, 'message' => 'Somthing went wrong']);
            $responseCode = 400; // bad request
        }

        return response()->json([$result, $responseCode]);
    }

    // get users
    public function GetUser()
    {
        try {
            $users = User::all();
            $result = array('staus' => true, 'message' => count($users) . 'user(s) Fetched', "data" => $users);
            $responseCode = 200; // success request
            return response()->json([$result, $responseCode]);
        } catch (Exception $e) {
            $result = array('status' => false, 'message' => 'API failed due to run', "error" => $e->getMessage());
            return response()->json($result, 500);
        }

    }

    //retutrn specific user details
    public function GetUserDetails($id)
    {
        try {
            $users = User::find($id);
            if (!$users) {
                $result = array('staus' => false, 'message' => "User Not Fethced");
                $responseCode = 404; // success request
            } else {
                $result = array('staus' => true, 'message' => "User Fethced Successfully", "data" => $users);
                $responseCode = 200; // Bad request
            }
            return response()->json([$result, $responseCode]);
        } catch (Exception $e) {
            $result = array('status' => false, 'message' => "API failed Due to run", 'error' => $e->getMessage());
            return response()->json($result, 500);
        }
    }

    // update user 
    public function UpdateUser(Request $request, $id)
    {

        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => "User not found"], 404);
        }

        // validation
        $validator = Validator::make($request->all(), [
            'name' => "required | string",
            'email' => "required | string | unique:users,email," . $id,
            'phone' => "required | numeric",
        ]);


        if ($validator->fails()) {
            $result = array(
                'staus' => false,
                'message' => 'Validation error occured',
                "error_message" => $validator->errors()
            );
            return response()->json($result, 400); //bad request
        }

        // update code
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        $result = array(
            'satus' => true,
            'message' => "User has been updated successfully",
            'data' => $user
        );

        return response()->json($result, 200);

    }

    // delete user
    public function DeleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => "User not found", 404]);
        } else {
            $user->delete();
            $result = array('status' => true, 'message' => 'User Deleted Successfully');
        }

        return response()->json($result, 200);

    }

    // Login
    public function Login(Request $request)
    {

        $Validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($Validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation Error occured",
                'errors' => $Validator->errors()
            ], 400);
        }

        $credentials = $request->only("email", "password");

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // create a token
            $token = $user->createToken('mytoken')->accessToken;

            return response()->json(['status' => true, 'message' => "User Successfully Login", 
            'token' => $token], 200);
        }

        return response()->json(['status' => false, 'message' => "Invalid login Credentials"], 400);
    }

    // unauthenticate user 
    public function Unauthenticate(){
        return response()->json(['status' => false, 'message' => "Only authrized User Can Access", 
        'erorr' => 'unautherized'], 400);
    }

    // Logout user
    public function Logout(){

        $user = Auth::user();
        $user->tokens->each(function ($token, $key){
            $token->delete();
        });

        // another way you write
        // $user = Auth::guard('api')->user();

        return response()->json(['status' => true, 'message' => "Logged out Successfully"], 200);
    }


}

