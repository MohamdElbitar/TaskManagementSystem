<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskDependency extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }


    public function dependency()
    {
        return $this->belongsTo(Task::class, 'dependency_id');
    }
}
