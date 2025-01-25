<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
    ];


    public function assigneeData()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }


    public function creatorData()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dependenciesData()
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    public function dependentTasks()
    {
        return $this->hasMany(TaskDependency::class, 'dependency_id');
    }
}
