<?php

namespace App\Http\Controllers;

use App\Models\Tarefas;
use Illuminate\Http\Request;

class TarefasController extends Controller
{

    public function index($id)
    {
        $users = Tarefas::where('dono_da_atividade_id', $id)->get();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $formattedDate = null;

        try {
            $formattedDate = \Carbon\Carbon::parse($request->data)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Formato de data inválido. Use dd/mm/yyyy' . $request->data], 400);
        }
        $loggedUserId = $request->usuarioLogado['id'];
        $tarefa = Tarefas::create([
            'name' => $request->name,
            'priority' => $request->priority,
            'color' => $request->color,
            'data' => $formattedDate,
            'dono_da_atividade_id' => $loggedUserId
        ]);

        $participants = $request->participants ?? [];




        $participantIds = array_map(function ($participant) {
            return is_array($participant) ? $participant['id'] : $participant;
        }, $participants);

        $tarefa->membros()->attach($participantIds);
        $tarefa->save();
        return response()->json([
            'message' => 'Tarefa criada com sucesso!',
            'tarefa' => $tarefa,
        ], 201);
    }
}
