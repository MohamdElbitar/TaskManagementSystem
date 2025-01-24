<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        return $this->taskRepository->all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed,canceled',
            'assignee_id' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
        ]);

        return $this->taskRepository->create($data);
    }

    public function show($id)
    {
        return $this->taskRepository->find($id);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,completed,canceled',
            'assignee_id' => 'sometimes|exists:users,id',
        ]);

        return $this->taskRepository->update($id, $data);
    }

    public function destroy($id)
    {
        $this->taskRepository->delete($id);
        return response()->noContent();
    }

    public function filterByStatus($status)
    {
        return $this->taskRepository->filterByStatus($status);
    }

    public function filterByDueDateRange(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        return $this->taskRepository->filterByDueDateRange($startDate, $endDate);
    }

    public function filterByAssignee($assigneeId)
    {
        return $this->taskRepository->filterByAssignee($assigneeId);
    }

    public function addDependency(Request $request, $taskId)
    {
        $dependencyId = $request->input('dependency_id');
        return $this->taskRepository->addDependency($taskId, $dependencyId);
    }

    public function getDependencies($taskId)
    {
        $dependencies = $this->taskRepository->getDependencies($taskId);

        return response()->json([
            'message' => 'Dependencies retrieved successfully',
            'dependencies' => $dependencies,
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $updated = $this->taskRepository->updateStatus($id, $request->input('status'), $user->id);

        if ($updated) {
            return response()->json([
                'message' => 'Task status updated successfully.',
                'task' => Task::find($id),
            ]);
        }

        return response()->json([
            'message' => 'You are not authorized to update this task, the task does not exist, or not all dependencies are completed.',
        ], 403);
    }

}
