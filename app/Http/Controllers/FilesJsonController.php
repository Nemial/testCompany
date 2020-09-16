<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilesJsonController extends Controller
{
    public function export($id)
    {
        $file = DB::table('files')->find($id);

        if ($file) {
            return response(
                "ID: $file->id\njsonData: $file->jsonData\ncreated_at: $file->created_at\nupdated_at: $file->updated_at\n"
            );
        }
        return response('Not Found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jsonData = $request->input('jsonData');
        $timestamp = Carbon::now()->toDateTimeString();
        $id = DB::table('files')->insertGetId(
            ['jsonData' => $jsonData, 'created_at' => $timestamp, 'updated_at' => $timestamp]
        );

        return response($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jsonData = $request->input('jsonData');
        $timestamp = Carbon::now()->toDateTimeString();
        DB::table('files')->where('id', $id)->update(['jsonData' => $jsonData, 'updated_at' => $timestamp]);
        return response('Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('files')->delete($id);
        return response('Deleted');
    }
}
