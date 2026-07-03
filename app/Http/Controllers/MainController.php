<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        //load user's notes
        $id = session('user.id');

        $notes = User::find($id)->notes()->whereNull('deleted_at')->get()->toArray();



        //show home view
        return view('home', ['notes' => $notes]);

    }

    public function newNote()
    {
        //show new note view
        return view('new_note');
    }

    public function newNoteSubmit(request $request)
    {
        //Validate requeste
         $request->validate(
            // rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            // error messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',
                'text_note.required' => 'A nota é obrigatório',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres'
            ]
        );



        //get user id
        //busca o id do usuário que  está logado
        $id = session('user.id');

        ////create new note
        //para criar a nota vamos usar os recursos do eleoquente ORM
        $note = new Note(); //cria um objeto a partir do modelo
        $note->user_id = $id; //para coluna user_id que está na tabela notes teremos $id
        $note->title = $request->text_title; //para coluna title que está na tabela notes teremos text_title
        $note->text = $request->text_note; //para coluna text que está na tabela notes teremos text_note

        //guarda as informações na base de dados e cria uma nova nota na base de dados
        $note->save();

        //redirect to home
        return redirect()->route('home');
    }

    public function editNote($id)
    {
        $id = Operations::decryptId($id);
        //load note
        //Nota que quero editar
         $note = Note::find($id); //estou dizendo encontra a nota que tem este id

        //show edit note view
        return view('edit_note', ['note' => $note]); //este $note esta recebendo os dados da linha de cima que são esses: Note::find($id)
    }

    public function editNoteSubmit(Request $request)
    {
        //Validate request
         $request->validate(
            // rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            // error messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',
                'text_note.required' => 'A nota é obrigatório',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres'
            ]
        );

        //check if note_id exists
        if ($request->note_id == null)
        {

            return redirect()->route('home');
        }

        //decrypt node_id
        $id = Operations::decryptId($request->note_id);

        //load note
        $note = Note::find($id);

        //upadate note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        //redirect to home
        return redirect()->route('home');

    }


    //delete
    public function deleteNote($id)
    {

        $id = Operations::decryptId($id);

        //load note
        $note = Note::find($id);

        //Show delete note confimation
        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id)
    {
        //verifica se o id é desencriptado ou não
        //check if $id is ecrypted
        $id = Operations::decryptId($id);

        //loado note
        $note = Note::find($id);

        //Registro será removido da base de dados
        //1. hard delete
        //$note->delete();

        //2. soft delete
        //$note->delete_at = date('Y:m:d H:i:s');
        //$note->save();

        // 3. Soft delete (property in mode)
        $note->delete();

        // 4. Hard delete (property in mode)
        //$note->forceDelete();



        //redirect to home
        return redirect()->route('home');

    }


}
