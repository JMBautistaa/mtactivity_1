<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Region;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(){
        $regions = Region::get();
        return view('admin.masterdata.provinces', compact('regions'));
    }

    public function show(){
        
        $data = Province::get()
        ->map(function($item) {
            $item->region_id = $item->Region->id;
            $item->region_name = $item->Region->name;
            return $item;
        });

        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:provinces,name',
            'region_id' => 'required|exists:regions,id',
        ], [
            'name.required' => 'The province name field is required.',
            'name.unique' => 'This province name already exists.',
            'region_id.required' => 'The region is required.',
            'region_id.exists' => 'The selected region does not exist.',
        ]);
    
        $data = new Province();
        $data->name = $request->name;
        $data->region_id = $request->region_id;
        $data->save();
    
        return response()->json(['message' => 'Province successfully added']);
    }
    

    public function edit($id){
        
        $data = Province::find($id);

        if (!$data) {
            return response()->json(['message' => 'Province not found'], 404);
        }

        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id) {

    $request->validate([
        'name' => 'required|unique:provinces,name,' . $id,
        'region_id' => 'required|exists:regions,id',
    ], [
        'name.required' => 'The province name field is required.',
        'name.unique' => 'This province name already exists.',
        'region_id.required' => 'The region is required.',
        'region_id.exists' => 'The selected region does not exist.',
    ]);

    $data = Province::find($id);
    if (!$data) {
        return response()->json(['message' => 'Province not found'], 404);
    }

    $data->name = $request->name;
    $data->region_id = $request->region_id;
    $data->save();

    return response()->json(['message' => 'Province successfully updated']);
}

    public function destroy($id)
   {
        $data = Province::where('id', $id)->first();
        $data->delete();

        return response()->json([
            'message' => 'Region successfully deleted'
        ]);
    }
}
