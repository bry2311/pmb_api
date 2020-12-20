<?php

namespace App\Http\Controllers;

use App\Models\JawabanUserPg;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JawabanUserPgController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $jawabanUserPg = JawabanUserPg::all();
        return $this->successResponse($jawabanUserPg);
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
            $jawabanUserPg = JawabanUserPg::create([
                'answer' => $request->get('answer'),
                'correctness' => $request->get('correctness'),
                'cts_id' => $request->get('cts_id'),
                'students_id' => $request->get('students_id'),
            ]);
            return $this->successResponse($jawabanUserPg, 'JawabanUserPg Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\jawabanUserPg  $jawabanUserPg
     * @return \Illuminate\Http\Response
     */
    public function show(jawabanUserPg $jawabanUserPg)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\jawabanUserPg  $jawabanUserPg
     * @return \Illuminate\Http\Response
     */
    public function edit(jawabanUserPg $jawabanUserPg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\jawabanUserPg  $jawabanUserPg
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
            $jawabanUserPg = JawabanUserPg::findOrFail($id);
            $jawabanUserPg->answer = $request->answer;
            $jawabanUserPg->correctness = $request->correctness;
            $jawabanUserPg->cts_id = $request->cts_id;
            $jawabanUserPg->students_id = $request->students_id;
            $jawabanUserPg->save();
            return $this->successResponse($jawabanUserPg, 'JawabanUserPg Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\jawabanUserPg  $jawabanUserPg
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $jawabanUserPg = JawabanUserPg::findOrFail($id);
            $jawabanUserPg->delete();
            return $this->successResponse(null, 'JawabanUserPg Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

}
