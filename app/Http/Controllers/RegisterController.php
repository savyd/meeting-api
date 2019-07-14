<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Meeting;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required',
            'user_id' => 'required',
        ]);

        $user_id = $request->input('user_id');
        $meeting_id = $request->input('meeting_id');

        $user = User::findOrFail($user_id);
        $meeting = Meeting::findOrFail($meeting_id);

        $message = [
            'msg' => 'User is alredy register for meeting',
            'user' => $user,
            'meeting' => $meeting,
            'unregister' => [
                'href' => 'api/v1/meeting/registration/'. $meeting->id,
                'method' => 'DELETE',
            ],
        ];

        if ($meeting->users()->where('user_id', $user_id)->first()) {
            return response()->json($message, 404);
        }
        /** ELSE */
        $user->meetings()->attach($meeting);

        $response = [
            'msg' => 'User register for meeting',
            'user' => $user,
            'meeting' => $meeting,
            'unregister' => [
                'href' => 'api/v1/meeting/registration/'. $meeting->id,
                'method' => 'DELETE',
            ],
        ];

        return response()->json($response, 201);
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
        $meeting->users()->detach();

        $response = [
            'msg' => 'User unregister for meeting',
            'user' => 'tdb',
            'meeting' => $meeting,
            'register' => [
                'href' => 'api/v1/meeting/registration',
                'method' => 'POST',
                'params' => 'user_id, meeting_id',
            ],
        ];

        return response()->json($response, 200);
    }
}
