<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Society;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {

        $users = User::where('id','!=',1)->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|min:5',
            'email' => 'required|unique:users',
            'password' => 'required',
            'confirmPassword' => 'required|same:password',
            'mobile' => 'required|digits:10',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()
                    ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'mobile' => $request->mobile,
        ]);

        return response()->json(['success'=>'user created successfully']);
           
    }

    public function update(Request $request, $id)
    {
    
        $user = User::findOrFail($id); 

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|min:4',
            'email' => 'required',
            'mobile' => 'required|digits:10',
        ]);
        
    
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()
                    ]);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        return response()->json(['success'=>'user updated successfully']);

    }

    public function delete(User $user)
    {

        $user->delete();

        return redirect('users'); 
    }

    public function logout()
    {

        Auth::logout();

        return redirect('/'); 
    }

    public function login(Request $req)
    {

       $user = User::where('email', $req->email)->first();

       if($user->password == $req->password)
        {
            
            return redirect('account');
        }
        
        return redirect('/'); 
    }
    
    
    

}
