<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Society;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    
    // public function getUsers(Request $request)
    // {
    //     $data = [];
    //     if($request->has('q')){
    //         $search = $request->q;
    //         $data = User::where(function($query) use ($search) {
    //             $query->where('name', 'like', "%$search%");
    //             $query->orWhere('last_name', 'like', "%$search%");
    //             $query->orWhere('email', 'like', "%$search%");
    //         })->get();
    //     } else {
    //         $data = User::where('id', '<>', 0)->limit(100)->get();
    //     }
    //     return response()->json($data);
    // }

    public function index()
    {

        $users = User::where('id','!=',1)->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
       

        return view('users.create');
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

    public function edit(User $user)
    {

       $societies = Society::with('houses')->get();

        return view('users.edit', compact('societies', 'user'));
    }

    public function update(Request $req, User $user)
    {
        $attributes= $req->validate([

            'name' => 'required|min:3|max:255',
            'email' =>'required|email',
            'mobile' => 'required|digits:10|gt:0',
            'house_id' => 'required|gt:0',
         
        ]);

        $user->update($attributes);

        return redirect('users');
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
