<?php

namespace App\Http\Controllers;

use App\Models\CityMun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityMunController extends Controller
{
    // Display the list of cities view
    public function index()
    {
        return view('admin.masterdata.cities');
    }

    // Fetch all cities
    public function getrecords()
    {
        $city_muns = CityMun::all();
        return response()->json(['cities' => $city_muns], 200);
    }

    // Store a new city
    public function store(Request $request)
    {
        // Validate the name field and ensure it's unique
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:city_muns,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        // Create the city
        $city_mun = CityMun::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'City successfully added', 'city' => $city_mun], 201);
    }

    // Edit an existing city by ID
    public function edit($id)
    {
        $city_mun = CityMun::findOrFail($id);
        return response()->json(['city' => $city_mun], 200);
    }

    // Update the city (using PUT)
    public function update(Request $request, $id)
    {
        // Validate the name field and ensure uniqueness except for the current city
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:city_muns,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $city_mun = CityMun::findOrFail($id);
        $city_mun->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'City successfully updated', 'city' => $city_mun], 200);
    }

    // Delete the city (using DELETE)
    public function destroy($id)
    {
        $city_mun = CityMun::find($id);
        
        if ($city_mun) {
            $city_mun->delete();
            return response()->json(['message' => 'City deleted successfully'], 200);
        }

        return response()->json(['message' => 'City not found'], 404);
    }
}
