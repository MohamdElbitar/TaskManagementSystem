<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\TaskDependency;

class TaskRepository implements TaskRepositoryInterface
{

    public function all()
    {
        return Task::all();
    }

    public function find($id)
    {
        return Task::findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }

    public function filterByStatus($status)
    {
        return Task::where('status', $status)->get();
    }

    public function filterByDueDateRange($startDate, $endDate)
    {
        return Task::whereBetween('due_date', [$startDate, $endDate])->get();
    }

    public function filterByAssignee($assigneeId)
    {
        return Task::where('assignee_id', $assigneeId)->get();
    }

    public function addDependency($taskId, $dependencyId)
    {
        return TaskDependency::create([
            'task_id' => $taskId,
            'dependency_id' => $dependencyId,
        ]);
    }

    public function getDependencies($taskId)
    {
        return TaskDependency::where('task_id', $taskId)->with('dependencyData')->get();
    }

    public function updateStatus($id, $status, $userId)
    {
        $task = Task::findOrFail($id);

        if (!$task) {
            return [
                'success' => false,
                'message' => 'Task not found.',
            ];
        }

        if ($task->assignee_id !== $userId) {
            return [
                'success' => false,
                'message' => 'You are not assigned to this task.',
            ];
        }

        if ($status === 'completed') {
            $incompleteDependencies = TaskDependency::where('task_id', $id)
                ->whereHas('dependencyData', function ($query) {
                    $query->where('status', '!=', 'completed');
                })
                ->exists();

            if ($incompleteDependencies) {
                return [
                    'success' => false,
                    'message' => 'Not all dependencies are completed.',
                ];
            }
        }

        $task->update([
            'status' => $status,
        ]);

        return [
            'success' => true,
            'message' => 'Task status updated successfully.',
            'task' => $task,
        ];
    }
}


