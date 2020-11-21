<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CtController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ct = Ct::all();
        return $this->successResponse($ct);
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
            "name" => "required",
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
            $ct = Ct::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'date' => $request->get('date'),
                'start' => $request->get('start'),
                'end' => $request->get('end'),
                'duration' => $request->get('duration'),
                'years_id' => $yearId
            ]);
            return $this->successResponse($ct, 'Ct Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function show(ct $ct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function edit(ct $ct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $ct = Ct::findOrFail($id);
            $ct->name = $request->name;
            $ct->description = $request->description;
            $ct->date = $request->date;
            $ct->start = $request->start;
            $ct->end = $request->end;
            $ct->duration = $request->duration;
            $ct->save();
            return $this->successResponse($ct, 'Ct Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $ct = Ct::findOrFail($id);
            $ct->delete();
            return $this->successResponse(null, 'Ct Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }
}
