<?php

namespace App\Http\Controllers;

use App\Models\Announce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AnnounceController extends Controller
{

    /**
     * validate announce fielts
     *
     * @return \Illuminate\Support\Facades\Validator
     */
    private function validateAnnounce(Request $request)
    {
        return Validator::make($request->all(), [
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'weight' => ['required', 'integer', 'min:1'],
        ]);
    }
    /**
     * Get a listing of announces.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Announce::with('user:id,name')->paginate(10);
    }

    /**
     * Store a newly created announce.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateAnnounce($request);

        if ($validator->fails())
            return response()->json($validator->errors(), 403);

        return Auth::user()->announces()->create($request->all());
    }

    /**
     * Get the specified announce.
     *
     * @param  \App\Models\Announce  $announce
     * @return \Illuminate\Http\Response
     */
    public function show(Announce $announce)
    {
        return $announce->load('user:id,name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announce  $announce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announce $announce)
    {
        $validator = $this->validateAnnounce($request);

        if ($validator->fails())
            return response()->json($validator->errors(), 403);

        return Auth::user()->id === $announce->user_id &&
            $announce->update($request->all());
    }

    /**
     * Remove the specified announce.
     *
     * @param  \App\Models\Announce  $announce
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announce $announce)
    {
        return $announce->delete();
    }
}
