<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    public function index()
    {   
        $companies = Company::get();
        return view('admin.masterdata.employees', compact('companies'));
    }

    public function getDepartmentsByCompany($id)
    {
        $departments = Department::where('company_id', $id)->get();
        return response()->json(['data' => $departments],200);
    }

    public function show()
    {
        $data = Employee::with(['Department.Company'])
        ->get()
        ->map(function($item){
            $item-> company_name = $item-> Department->Company->name;
            $item-> department_name = $item-> Department->name;
            return $item;
        });

        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'job_title' => 'required',
            'hire_date' => 'required',
        ], [
            'company_id.required' => 'The company field is required.',
            'company_id.exists' => 'The selected company does not exist.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department does not exist.',
            'full_name.required' => 'The full name field is required.',
            'email.required' => 'The email field is required.',
            'phone_number.required' => 'The phone number field is required.',
            'job_title.required' => 'The job title field is required.',
            'hire_date.required' => 'The hired date field is required.',
        ]);

        $data = new Employee();
        $data->full_name = $request->full_name;
        $data->company_id = $request ->company_id;
        $data->department_id = $request ->department_id;
        $data->email = $request-> email;
        $data->phone_number = $request->phone_number;
        $data->job_title = $request->job_title;
        $data->hire_date = $request->hire_date;
        $data->save();

        return response()->json(['message' => 'Employee successfully added']);
    }

    public function edit($id){
        $data = Employee::with(['Department.Company'])->where('id', $id)->first();
        return response()->json([
            'data' => $data
        ]);
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'full_name' => 'required|unique:employees,full_name,' . $id,
        'company_id' => 'required|exists:companies,id',
        'department_id' => 'required|exists:departments,id',
        'email' => 'required|email|unique:employees,email,' . $id,
        'phone_number' => 'required|unique:employees,phone_number,' . $id,
        'job_title' => 'required',
        'hire_date' => 'required|date',
    ], [
        'company_id.required' => 'The company field is required.',
        'company_id.exists' => 'The selected company does not exist.',
        'department_id.required' => 'The department field is required.',
        'department_id.exists' => 'The selected department does not exist.',
        'full_name.required' => 'The full name field is required.',
        'email.required' => 'The email field is required.',
        'email.unique' => 'The email is already in use.',
        'phone_number.required' => 'The phone number field is required.',
        'phone_number.unique' => 'The phone number is already in use.',
        'job_title.required' => 'The job title field is required.',
        'hire_date.required' => 'The hire date field is required.',
        'hire_date.date' => 'The hire date must be a valid date.',
    ]);
    $data = Employee::find($id);

    if (!$data) {
        return response()->json(['error' => 'Employee not found.'], 404);
    }
    $data->full_name = $request->full_name;
    $data->company_id = $request->company_id;
    $data->department_id = $request->department_id;
    $data->email = $request->email;
    $data->phone_number = $request->phone_number;
    $data->job_title = $request->job_title;
    $data->hire_date = $request->hire_date;
    $data->save();

    // Return a success message
    return response()->json(['message' => 'Employee successfully updated']);
}

    
    public function destroy($id)
    {
        $data = Employee::where('id', $id)->first();
        $data->delete();

        return response()->json(['data' => 'Employee deleted successfully']);
    }
}
