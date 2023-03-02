<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;
use DataTables;

class SocietyController extends Controller
{
    public function index(Request $request)
    {
        $societies = Society::all();

        return view('societies.index', compact('societies'));

    }

    public function create()
    {

        $societies= Society::society()->get();

        return view('societies.create', compact('societies'));
    }

    public function store(Request $req)
    {
        $attributes= $req->validate([
            'name' => 'required|max:255|min:3',
            'description' => 'required|min:3',
            'image' => 'required',
        ]);

        if($req->parent_id)
        {
            $attributes+=[
                'parent_id' => $req->parent_id
            ];
        }

        $file = $req->file('image');
        $filename = $file->getClientOriginalName();
        $file->storeAs('public/',$filename);

        Society::create($attributes);
    }

    public function edit(Society $society)
    {
        $societies= Society::society()->get();

        return view('societies.edit', compact('society', 'societies'));
    }

    public function update(Request $req, Society $society)
    {
        $attributes= $req->validate([
            'name' => 'required|max:255|min:3',
            'description' => 'required|min:3',
            'image' => 'required',

        ]);

        if($req->parent_id)
        {
            $attributes+=[
                'parent_id' => $req->parent_id
            ];
        }

        $file = $req->file('image');
        $filename = $file->getClientOriginalName();
        $file->storeAs('public/',$filename);

        $society->update($attributes);

        return redirect()->route('society.index');
    }

    public function delete(Society $society)
    {
        $society->delete();

        return redirect()->route('society.index');
    }
}
