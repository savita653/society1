<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\House;
use App\Society;
use App\User;
use App\HouseResidence;

class HouseController extends Controller
{
    public function index(Society $society)
    {
        $houses = House::houses($society->id)->get();

        return view('societies.houses.index', compact('houses', 'society'));
    }

    public function create(Society $society)
    {
       
       $users=  User::get(); 

        return view('societies.houses.create', compact('society', 'users'));
    }

    public function store(Society $society, Request $req)
    { 
       
        $attributes= $req->validate([
            
            'house_no' => 'required|numeric',
            'capacity' => 'required|max:255|min:1|numeric',
            'address' => 'required|min:3',
            'owner' => 'required',
            'resident' => 'required',
        ]);
        
        if($req->file('image'))
        {
            $path = $req->file('image')->store('public/files');
            
            $attributes+=[

                'image' => $path,      
            ];
        }
        
        $attributes+=[
            
            'society_id' => $society->id,
        ];
        
        $house = House::create($attributes);
        
        // insertion in house_residence table
        
        $features = $req->validate([

            'owner' => 'required',
            'resident' => 'required',
            'total_member' => 'required|min:1|max:255|numeric',
        ]);

     
        $features +=[

            'house_id' => $house->id, 
            'user_id' => $req->owner,
        ];
     
       HouseResidence::create($features);

        return back();
    }

    public function edit(Society $society, House $house)
    {
        $users=  User::get();

        $houseDetails= HouseResidence::where('house_id', $house->id)->first();

        return view('societies.houses.edit', compact('house', 'users', 'houseDetails', 'society'));
    }

    public function update(Request $req, Society $society, House $house)
    {
        $attributes= $req->validate([
            
            'house_no' => 'required|numeric',
            'capacity' => 'required|max:255|min:1',
            'address' => 'required|min:3',
            'owner' => 'required',
            'resident' => 'required',
        ]);
        
       $house->update($attributes);
        
        // insertion in house_residence table
        
        $features = $req->validate([

            'owner' => 'required',
            'resident' => 'required',
            'total_member' => 'required|min:1|max:255',
        ]);

        $features +=[

            'house_id' => $house->id, 
            'user_id' => $req->owner,
        ];

        $houseDetails= HouseResidence::where('house_id', $house->id)->first();
       
        $houseDetails->update($features);
        
        return redirect()->route('society.house.index' ,$society);
    }

    public function show()
    {

        $houses= House::get();

        return view('societies.houses.show', compact('houses'));
    }

    public function delete(House $house)
    {
        
        $house->delete();
        
        return back();
    }
}
