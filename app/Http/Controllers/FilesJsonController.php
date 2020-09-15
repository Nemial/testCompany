<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilesJsonController extends Controller
{
    public function export($id)
    {
        $file = DB::table('files')->find($id);

        if ($file) {
            return $file->jsonData;
        }
        return response('Not Found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jsonData = $request->input('jsonData');
        $id = DB::table('files')->insertGetId(['jsonData' => $jsonData]);

        return response($id);
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
        $jsonData = $request->input('jsonData');
        DB::table('files')->where('id', $id)->update(['jsonData' => $jsonData]);
        return response('Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('files')->delete($id);
        return response('Deleted');
    }
}
