<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/home', function () {
        return view('home');
    });

    // Masterdata -> Users
    Route::get('/master_data/users', [UserController::class, 'index']);
    Route::get('/master_data/users/getrecords', [UserController::class, 'getrecords']);
    Route::post('/master_data/users/store', [UserController::class, 'store']);
    Route::get('/master_data/users/edit/{id}', [UserController::class, 'edit']);
    Route::put('/master_data/users/update/{id}', [UserController::class, 'update']);
    Route::get('/master_data/users/destroy/{id}', [UserController::class, 'destroy']);

    //Regions
    Route::get('/master_data/regions', [RegionController::class, 'index']);
    Route::get('/master_data/regions/show', [RegionController::class, 'show']);
    Route::post('/master_data/regions/store', [RegionController::class, 'store']);
    Route::get('/master_data/regions/edit/{id}', [RegionController::class, 'edit']);
    Route::put('/master_data/regions/update/{id}', [RegionController::class, 'update']);
    Route::get('/master_data/regions/destroy/{id}', [RegionController::class, 'destroy']);

    // //Province
    Route::get('/master_data/provinces', [ProvinceController::class, 'index']);
    Route::get('/master_data/provinces/show', [ProvinceController::class, 'show']);
    Route::post('/master_data/provinces/store', [ProvinceController::class, 'store']);
    Route::get('/master_data/provinces/edit/{id}', [ProvinceController::class, 'edit']);
    Route::put('/master_data/provinces/update/{id}', [ProvinceController::class, 'update']);
    Route::get('/master_data/provinces/destroy/{id}', [ProvinceController::class, 'destroy']);

    // cities routes
    Route::get('/master_data/cities', [CityController::class, 'index']);
    Route::get('/master_data/cities/show', [CityController::class, 'show']);
    Route::post('/master_data/cities/store', [CityController::class, 'store']);
    Route::get('/master_data/cities/edit/{id}', [CityController::class, 'edit']);
    Route::put('/master_data/cities/update/{id}', [CityController::class, 'update']);
    Route::get('/master_data/cities/destroy/{id}', [CityController::class, 'destroy']);
    Route::get('/master_data/cities/getProvincesByRegion/{id}', [CityController::class, 'getProvincesByRegion']);

    // Companies Routes
    Route::get('/master_data/companies', [CompanyController::class, 'index']);
    Route::get('/master_data/companies/show', [CompanyController::class, 'show']);
    Route::post('/master_data/companies/store', [CompanyController::class, 'store']);
    Route::get('/master_data/companies/edit/{id}', [CompanyController::class, 'edit']);
    Route::put('/master_data/companies/update/{id}', [CompanyController::class, 'update']);
    Route::get('/master_data/companies/destroy/{id}', [CompanyController::class, 'destroy']);

    //department
    Route::get('/master_data/departments', [DepartmentController::class, 'index']);
    Route::get('/master_data/departments/show', [DepartmentController::class, 'show']);
    Route::post('/master_data/departments/store', [DepartmentController::class, 'store']);
    Route::get('/master_data/departments/edit/{id}', [DepartmentController::class, 'edit']);
    Route::put('/master_data/departments/update/{id}', [DepartmentController::class, 'update']);
    Route::get('/master_data/departments/destroy/{id}', [DepartmentController::class, 'destroy']);

    // Employees routes
    Route::get('/master_data/employees', [EmployeeController::class, 'index']);
    Route::get('/master_data/employees/show', [EmployeeController::class, 'show']);
    Route::post('/master_data/employees/store', [EmployeeController::class, 'store']);
    Route::get('/master_data/employees/edit/{id}', [EmployeeController::class, 'edit']);
    Route::put('/master_data/employees/update/{id}', [EmployeeController::class, 'update']);
    Route::get('/master_data/employees/destroy/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/master_data/employees/getDepartmentsByCompany/{id}', [EmployeeController::class, 'getDepartmentsByCompany']);


});
