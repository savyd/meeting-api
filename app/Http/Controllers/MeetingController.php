<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meeting;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meetings = Meeting::all();

        foreach($meetings as $meeting){
            $meeting->view_meeting = [
                'href' => 'api/v1/meeting/'. $meeting->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List of All Meetings',
            'meetings' => $meetings
        ];

        return response()->json($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'time' => 'required',
            'title' => 'required',
            'user_id' => 'required',
            'description' => 'required'
        ]);

        $time = $request->input('time');
        $title = $request->input('title');
        $user_id = $request->input('user_id');
        $description =$request->input('description');

        $meeting = new Meeting([
            'time' => $time,
            'title' => $title,
            'description' => $description
        ]);

        if($meeting->save()){
            $meeting->users()->attach($user_id);
            $meeting->view_meeting = [
                'href' => 'api/v1/meeting/'. $meeting->id,
                'method' => 'GET'
            ];
            $message = [
                'msg' => 'Meeting created',
                'meeting' => $meeting
            ];
            return response()->json($message, 201);
        }

        $message = [
            'msg' => 'Error during Create'
        ];

        return response()->json($meeting, 400);


        $response = [
            'msg' => 'Meeting Created',
            'data' => $meeting
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'ini berhasil';
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'ini berhasil';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'ini berhasil';
    }
}
