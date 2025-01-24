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
        return TaskDependency::where('task_id', $taskId)->with('dependency')->get();
    }

    public function updateStatus($id, $status, $userId)
    {
        $task = Task::find($id);

        if (!$task || $task->assignee_id !== $userId) {
            return false;

            if ($status === 'completed') {
                $dependencies = TaskDependency::where('task_id', $id)->get();

                foreach ($dependencies as $dependency) {
                    $dependentTask = Task::find($dependency->dependency_id);
                    if ($dependentTask->status !== 'completed') {
                        return false;
                    }
                }
            }

            return $task->update([
                'status' => $status,
            ]);
        }
    }

}
