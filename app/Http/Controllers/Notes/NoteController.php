<?php

namespace App\Http\Controllers\Notes;

use App\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreRequest;

class NoteController extends Controller
{
    public function notes()
    {
        $notes = Note::all();

        return response()->json([
            'status' => 'success',
            'data' => $notes->toArray(),
        ]);
    }

    public function createNote(StoreRequest $request)
    {
        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => '',
            'message' => 'Note Created Successfully',
        ]);
    }

    public function deleteNote($id)
    {
        if(Note::destroy($id)) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully deleted.'
            ]);
        }
    }

    public function updateNote(StoreRequest $request)
    {
        //update note
        $note = Note::where('id', $request);
        if($note->update($request->all())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Note successfully updated.'
            ]);
        }
    }
}
