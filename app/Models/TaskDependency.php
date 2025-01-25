<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskDependency extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function taskData()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }


    public function dependencyData()
    {
        return $this->belongsTo(Task::class, 'dependency_id');
    }
}
