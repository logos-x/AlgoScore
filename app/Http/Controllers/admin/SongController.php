<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    public function index(Request $request) {
        $songs = Song::latest();

        if (!empty($request->get('keyword'))) {
            $songs = $songs->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $songs = $songs->paginate(10);
        return view( 'admin.song.list', compact('songs'));
    }

    public function create() {
        return view( 'admin.song.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:songs',
            'composer' => 'required',
            'singer' => 'required',
        ]);

        if ($validator->passes()) {
            $song = new Song();
            $song->title = $request->title;
            $song->slug = $request->slug;
            $song->composers = $request->composer;
            $song->singers = $request->singer;
            $song->status = $request->status;
            $song->save();

            $request->session()->flash('success', 'Song added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Song added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }


    }

    public function edit($songID, Request $request) {
        $song = Song::find($songID);
        if (empty($song)) {
            return redirect()->route('songs.index');
        }

        return view('admin.song.edit', compact('song'));
    }

    public function update($songID, Request $request) {
        $song = Song::find($songID);
        if (empty($song)) {
            $request->session()->flash('error', 'Song not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Sopng not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'composer' => 'required',
            'singer' => 'required',
            'slug' => 'required|unique:songs,slug,'.$song->id.',id',
        ]);

        if ($validator->passes()) {

            $song->title = $request->title;
            $song->slug = $request->slug;
            $song->composers = $request->composer;
            $song->singers = $request->singer;
            $song->status = $request->status;
            $song->save();

            $request->session()->flash('success', 'Song updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Song updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($songID, Request $request) {
        $song = Song::find($songID);
        if (empty($song)) {
            $request->session()->flash('error', 'Song not found');
            return response()->json([
                'status' => false,
                'message' => 'Song not found'
            ]);
        }

        $song->delete();

        $request->session()->flash('success', 'Song deleted successfully.');

        return response()->json([
            'status' => true,
            'message' => 'Song deleted successfully'
        ]);
    }
}
