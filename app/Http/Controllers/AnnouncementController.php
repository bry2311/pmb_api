<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $announcement = Announcement::all();
        $announcement = DB::table('announcements')
        ->join('students', 'announcements.students_id', '=', 'students.id')
        ->join('years', 'announcements.years_id', '=', 'years.id')
        ->select('announcements.*','students.name as student_name','years.name as year_name')
        ->get();
        return $this->successResponse($announcement);
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
            "description" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $years = DB::table('years')
                ->where('years.status','=',1)
                ->first();
            $yearId = null;
            if($years != null){
                $yearId = $years->id;
            }
            $announcement = Announcement::create([
                'date' => $request->get('date'),
                'description' => $request->get('description'),
                'students_id' => $request->get('students_id'),
                'years_id' =>  $yearId
            ]);
        try {

            return $this->successResponse($announcement, 'Announcement Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "description" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $years = DB::table('years')
            ->where('years.status','=',1)
            ->first();
        $yearId = null;
        if($years != null){
            $yearId = $years->id;
        }
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->date = $request->date;
            $announcement->description = $request->description;
            $announcement->years_id = $yearId;
            $announcement->students_id = $request->students_id;
            $announcement->save();
            return $this->successResponse($announcement, 'Announcement Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();
            return $this->successResponse(null, 'Announcement Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }
}
