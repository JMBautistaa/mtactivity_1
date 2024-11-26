<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $regions = Region::get();
        $provinces = Province::get();
        return view('admin.masterdata.cities', compact('regions', 'provinces'));
    }

    public function show()
    {
        $data = City::with('region', 'province')
        ->get()
        ->map(function($item){
            // dd($item);
            $item->region_name = $item->Province->Region->name;
            $item->province_name = $item->Province->name;
            return $item;
        });

        return response()->json(['data' => $data]);

    }

    public function store(Request $request)

    {
        // dd($request);
        $request->validate([
            'province_id' => 'required',
            'name' => 'required',
        ], [], [
            'province_id' => 'Province',
            'name' => 'Name',
        ]);

        $data = new City();
        $data->province_id = $request->province_id;
        $data->name = $request->name;
        $data->save();

        return response()->json(['data' => 'City created successfully!']);
    }    

    public function edit($id){
        $data = City::with(['Province.Region'])->where('id', $id)->first();
        return response()->json([
            'data' => $data
        ]);
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|unique:cities,name,' . $id,
            'province_id' => 'required|exists:provinces,id',
        ], [
            'name.required' => 'The city name field is required.',
            'name.unique' => 'This city name already exists.',
            'province_id.required' => 'The province is required.',
            'province_id.exists' => 'The selected province does not exist.',
        ]);
    
        $data = City::find($id);
        if (!$data) {
            return response()->json(['message' => 'City not found'], 404);
        }
    
        $data->name = $request->name;
        $data->province_id = $request->province_id;
        $data->save();
    
        return response()->json(['message' => 'City successfully updated']);
    }
    
    
    public function destroy($id)
    {
        $data = City::where('id', $id)->first();
        $data->delete();

        return response()->json(['data' => 'City deleted successfully']);
    }

    public function getProvincesByRegion($id)
    {
        $provinces = Province::where('region_id', $id)->get();

        return response()->json(['data' => $provinces],200);
    }
}
