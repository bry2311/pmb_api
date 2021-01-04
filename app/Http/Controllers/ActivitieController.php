<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ActivitieController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $activitie = Activitie::all();
        return $this->successResponse($activitie);
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
            "date" => "required",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            
            $years = DB::table('years')
            ->where('years.status','=',1)
            ->first();
            $yearId = null;
            if($years != null){
                $yearId = $years->id;
            }
            $activitie = Activitie::create([
                'date' => $request->get('date'),
                'start' => $request->get('start'),
                'end' => $request->get('end'),
                'description' => $request->get('description'),
                'place' => $request->get('place'),
                'pic' => $request->get('pic'),
                'years_id' => $yearId
            ]);
            return $this->successResponse($activitie, 'Activitie Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\activitie  $activitie
     * @return \Illuminate\Http\Response
     */
    public function show(activitie $activitie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\activitie  $activitie
     * @return \Illuminate\Http\Response
     */
    public function edit(activitie $activitie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\activitie  $activitie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, activitie $activitie)
    {
        //
        $validator = Validator::make($request->all(), [
            "date" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $activitie = Activitie::findOrFail($id);
            $activitie->date = $request->date;
            $activitie->start = $request->start;
            $activitie->end = $request->end;
            $activitie->description = $request->description;
            $activitie->place = $request->place;
            $activitie->pic = $request->pic;
            $activitie->years_id = $request->years_id;
            $activitie->save();
            return $this->successResponse($activitie, 'Activitie Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\activitie  $activitie
     * @return \Illuminate\Http\Response
     */
    public function destroy(activitie $activitie)
    {
        //
        try {
            $activitie = Activitie::findOrFail($id);
            $activitie->delete();
            return $this->successResponse(null, 'Activitie Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }
}
