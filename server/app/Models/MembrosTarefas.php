<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarefaMembro extends Model
{
    use HasFactory;

    protected $table = 'tarefas_membros';
    protected $fillable = ['tarefa_id', 'pessoa_id'];
}
