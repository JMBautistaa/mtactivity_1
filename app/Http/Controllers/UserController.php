<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();
        
        return view('admin.masterdata.users');

        
    }

    public function getrecords() {
        $users = User::get();

        return response()->json(['user' => $users],
            200);
    }

   
    public function store(Request $request)
    {
                // Check if email already exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['message' => 'Email already exists'], 400);
            }

            // Create new user if email is unique
            $data = new User();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->save();

            return response()->json(['message' => 'User successfully added'], 200);
    }

    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email already exists'], 400);
        }


        $data= User::where('id', $request->id)->first();
        $data->name  = $request->name;
        $data->email  = $request->email;
        $data->save();

        return response()->json(['message' => 'User successfully updated'], 200);
    }


    public function destroy($id)
    {
        $data= User::where('id', $id)->first();
        $data->delete();

        return response()->json(['message' => 'User successfully deleted'], 200);
    }
}