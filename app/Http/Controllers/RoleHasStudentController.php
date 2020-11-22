<?php

namespace App\Http\Controllers;

use App\Models\Role_has_student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleHasStudentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roleHasStudent = Role_has_student::all();
        return $this->successResponse($roleHasStudent);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "roles_id" => "required",
            "students_id" => "required",
            "years_id" => "required",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $roleHasStudent = Role_has_student::create([
                'roles_id' => $request->get('roles_id'),
                'students_id' => $request->get('students_id'),
                'years_id' => $request->get('years_id'),
            ]);
            return $this->successResponse($roleHasStudent, 'RoleHasStudent Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\roleHasStudent  $roleHasStudent
     * @return \Illuminate\Http\Response
     */
    public function show(Role_has_student $roleHasStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\roleHasStudent  $roleHasStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(Role_has_student $roleHasStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\roleHasStudent  $roleHasStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "roles_id" => "required",
            "students_id" => "required",
            "years_id" => "required",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $roleHasStudent = Role_has_student::findOrFail($id);
            $roleHasStudent->roles_id = $request->roles_id;
            $roleHasStudent->students_id = $request->students_id;
            $roleHasStudent->years_id = $request->years_id;
            $roleHasStudent->save();
            return $this->successResponse($roleHasStudent, 'RoleHasStudent Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\roleHasStudent  $roleHasStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(roleHasStudent $roleHasStudent)
    {
        //
        try {
            $roleHasStudent = Role_has_student::findOrFail($id);
            $roleHasStudent->delete();
            return $this->successResponse(null, 'RoleHasStudent Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }
}
