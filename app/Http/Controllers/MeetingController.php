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
        $request->validate([
            'time' => 'required',
            'title' => 'required',
            'user_id' => 'required',
            'description' => 'required',
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

        if ($meeting->save()) {
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
        } else {
            $message = [
                'msg' => 'Error during Create'
            ];
            return response()->json($meeting, 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meeting = Meeting::with('users')->where('id', $id)->firstOrFail();
        $meeting->view_meeting = [
            'href' => 'api/v1/meeting',
            'method' => 'GET',
        ];

        $response = [
            'msg' => 'Meeting Information',
            'meeting' => $meeting,
        ];

        return response()->json($response, 200);
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
        $request->validate([
            'time' => 'required',
            'title' => 'required',
            'user_id' => 'required',
            'description' => 'required',
        ]);

        $time = $request->input('time');
        $title = $request->input('title');
        $user_id = $request->input('user_id');
        $description =$request->input('description');

        $meeting = Meeting::with('users')->findOrFail($id);

        if (!$meeting->users()->where('user_id', $user_id)->first()) {
            return response()->json(['msg' => 'user not registered for meeting, update not succesful'], 401);
        }
        /** ELSE */
        $meeting->time = $time;
        $meeting->title = $title;
        $meeting->description = $description;

        if (!$meeting->update()) {
            return response()->json([
                'msg' => 'Error during update',
            ], 404);
        }
        /** ELSE */
        $meeting->view_meeting = [
            'href' => 'api/v1/meeting/' . $meeting->id,
            'method' => 'GET',
        ];

        $response = [
            'msg' => 'Meeting Updated',
            'meeting' => $meeting,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $users = $meeting->users;
        $meeting->users()->detach();

        if (!$meeting->delete()) {
            foreach ($users as $user) {
                $meeting->users()->attach($user);
            }

            return response()->json([
                'msg' => 'Deletion Failed',
            ], 404);
        }
        /** ELSE */
        $response = [
            'msg' => 'Meeting delete',
            'create' => [
                'href' => 'api/v1/meeting',
                'method' => 'POST',
                'params' => 'title, description, time'
            ]
        ];

        return response()->json($response, 200);
    }
}
