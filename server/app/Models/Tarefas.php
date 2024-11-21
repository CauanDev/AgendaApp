<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefas extends Model
{
    use HasFactory;

    protected $table = 'tarefas';
    protected $fillable = ['name', 'priority', 'color', 'data', 'participants','dono_da_atividade_id'];

    public function membros()
    {
        return $this->belongsToMany(User::class, 'tarefas_membros', 'tarefa_id', 'pessoa_id');
    }
}
