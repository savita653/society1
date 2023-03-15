<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Society;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{
    public function index()
    {

        $societies = Society::get();

        return view('societies.index', compact('societies'));
    }

    public function create()
    {

        return view('societies.create');
    }

    public function store(Request $req)
    {

        $attributes= $req->validate([

            'name' => 'required|max:255|min:3|unique:societies',
            'description' => 'required|min:3',
        ]);

        if($req->file('image'))
        {

            $path = $req->file('image')->store('public/files');
    
            $attributes+=[
    
                'image' => $path,
            ];
        }

        Society::create($attributes);

        return redirect()->route('society.index');
    }

    // public function edit(Society $society)
    // {

    //     return view('societies.edit', compact('society'));
    // }

    public function update(Request $request, $id)
    {
    
        $society = Society::findOrFail($id); 

        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255|min:3',
            'description' => 'required|min:3',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()
                    ]);
        }

        $society->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['success'=>'society updated successfully']);
    }

    public function delete(Society $society)
    {
        $society->delete();

        
        return redirect()->route('society.index');
    }
}
