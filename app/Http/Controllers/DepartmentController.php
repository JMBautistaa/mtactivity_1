<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DepartmentController extends Controller
{
    public function index(){
        $companies = Company::get();
        return view('admin.masterdata.departments', compact('companies'));
    }

    public function show(){
        $data = Department::get()
            ->map(function($item) {
                $item->company_id = $item->Company->id;
                $item->company_name = $item->Company->name;
                return $item;
            });

        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|unique:departments,name',
            'budget' => 'required',
        ], [
            'name.required' => 'The department name field is required.',
            'name.unique' => 'This department name already exists.',
            'company_id.required' => 'The company field is required.',
            'company_id.exists' => 'The selected company does not exist.',
        ]);
        
        $data = new Department();
        $data->name = $request->name;
        $data->company_id = $request->company_id;
        $data->budget = $request->budget;
        $data->creation_date = Carbon::now();
        $data->save();
        
        return response()->json(['message' => 'Department successfully added']);
    }

    public function edit($id){
        $data = Department::find($id);

        if (!$data) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return response()->json(['data' => $data]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $id,
            'company_id' => 'required|exists:companies,id',
        ], [
            'name.required' => 'The department name field is required.',
            'name.unique' => 'This department name already exists.',
            'company_id.required' => 'The company field is required.',
            'company_id.exists' => 'The selected company does not exist.',
        ]);
    
        $department = Department::findOrFail($id);
        $department->company_id = $request->company_id;
        $department->name = $request->name;
        $department->budget = $request->budget;
        $department->save();
        
        return response()->json(['message' => 'Department successfully updated']);
    }
    

    public function destroy($id)
    {
        $data = Department::where('id', $id)->first();
        if ($data) {
            $data->delete();
            return response()->json([
                'message' => 'Department successfully deleted'
            ]);
        }

        return response()->json(['message' => 'Department not found'], 404);
    }
}
