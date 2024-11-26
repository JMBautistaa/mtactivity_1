<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('admin.masterdata.companies');
    }

    public function show()
    {
        return response()->json([
            'data' => Company::get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies,name',
            'address' => 'required',
            'established_date' => 'required|date',
        ], [
            'name.required' => 'The company name field is required.',
            'name.unique' => 'This company already exists.',
            'address.required' => 'The address field is required.',
            'established_date.required' => 'The established date field is required.',
        ]);

        $data = new Company();
        $data->name = $request->name;
        $data->address = $request->address;
        $data->established_date = $request->established_date;
        $data->save();

        return response()->json(['message' => 'Company successfully added']);
    }

    public function edit($id)
{
    $data = Company::findOrFail($id);
    return response()->json(['data' => $company]);
}

    public function update(Request $request, $id){

    $request->validate([
        'name' => 'required|unique:companies,name,' . $id,
        'address' => 'required',
        'established_date' => 'required|date',
    ], [
        'name.required' => 'The company name field is required.',
        'name.unique' => 'This company already exists.',
        'address.required' => 'The address field is required.',
        'established_date.required' => 'The established date field is required.',
    ]);

    $data = Company::findOrFail($id);
    $data->update($request->only('name', 'address', 'established_date'));

    return response()->json(['message' => 'Company successfully updated']);
}


    public function destroy($id)
    {
        $data = Company::where('id', $id)->first();
        $data->delete();

        return response()->json(['data' => 'Comapny deleted successfully']);
    }
}
