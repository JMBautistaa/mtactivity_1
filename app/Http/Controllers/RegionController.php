<?php

namespace App\Http\Controllers;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(){
       $user = Auth()->user();
        return view('admin.masterdata.regions');
    }

    public function show(){
        return response()->json([
            'data' => Region::get(),
        ]);
    }

    public function store(Request $request)
    {
         
       $request ->validate([
        'name' => 'required|unique:regions,name',
       ],[
        'name.required' => 'The Region name field is required.',
        'name.unique' => 'This Region name already exists.',
       ]);

       $data = new Region();
       $data->name = $request->name;
       $data->save();


       return response()->json(['message' => 'Region successfully added']);
    }
    
    public function edit ($id){
        $data = Region::where('id', $id)->first();
        
        return response()->json(['data' => Region::get()]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|unique:regions,name,' . $id,
        ], [
            'name.required' => 'The Region name field is required.',
            'name.unique' => 'This Region name already exists.',
        ]);


        $data = Region::where('id', $id)->first();
        $data->name = $request->name;
        $data->save();

        return response()->json([
            'message' => 'Region successfully updated'
        ]);
    }

    public function destroy($id)
    {
        $data = Region::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'message' => 'Region successfully deleted'
        ]);
    }
}