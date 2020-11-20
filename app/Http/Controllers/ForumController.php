<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForumController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $forum = Forum::all();
        return $this->successResponse($forum);
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
            $forum = Forum::create([
                'name' => $request->get('name'),
                'date' => $request->get('date'),
                'description' => $request->get('description'),
                'years_id' => $request->get('years_id')
            ]);
            return $this->successResponse($forum, 'Forum Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function show(forum $forum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function edit(forum $forum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, forum $forum)
    {
        //
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $forum = Forum::findOrFail($id);
            $forum->name = $request->name;
            $forum->date = $request->date;
            $forum->description = $request->description;
            $forum->years_id = $request->years_id;
            $forum->save();
            return $this->successResponse($forum, 'Forum Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\forum  $forum
     * @return \Illuminate\Http\Response
     */
    public function destroy(forum $forum)
    {
        //
        try {
            $forum = Forum::findOrFail($id);
            $forum->delete();
            return $this->successResponse(null, 'Forum Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }
}
