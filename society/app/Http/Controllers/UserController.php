<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;
use App\Models\User;
use DataTables;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('users');
    }

    public function create()
    {

        $houses= Society::house()->get();

        return view('users.create', compact('houses'));
    }

    public function store(Request $req)
    {
        $attributes= $req->validate([
            'name' => 'required|max:255|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:255',
            'mobile' => 'required|gt:0|digits:10',
            'house_no' => 'required',
            'isOwner' => 'required',
            'DOA' => 'required_if:isOwner,0',
            'DOD' => 'required_if:isOwner,0',
        ]);

        User::create($attributes);
    }
}
