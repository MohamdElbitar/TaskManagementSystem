<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TaskRepositoryInterface;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\AddDependencyRequest;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;

        $this->middleware(['permission:view tasks'])->only(['index', 'show', 'filterByStatus', 'filterByDueDateRange', 'filterByAssignee']);
        $this->middleware(['permission:create tasks'])->only(['store']);
        $this->middleware(['permission:update tasks'])->only(['update']);
        $this->middleware(['permission:delete tasks'])->only(['destroy']);
        $this->middleware(['permission:assign tasks'])->only(['assignTask', 'addDependency']);
        // $this->middleware(['permission:update task status'])->only(['updateStatus']);

    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('manager')) {
            return $this->taskRepository->all();
        }

        return $this->taskRepository->filterByAssignee($user->id);
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        return $this->taskRepository->create($data);
    }

    public function show($id)
    {
        $user = auth()->user();

        $task = $this->taskRepository->find($id);

        if ($user->hasRole('manager') || $task->assignee_id === $user->id) {
            return $task;
        }

        return response()->json([
            'message' => 'You are not authorized to view this task.',
        ], 403);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $data = $request->validated();
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

    public function addDependency(AddDependencyRequest $request, $taskId)
    {
        $data = $request->validated();
        return $this->taskRepository->addDependency($taskId, $data['dependency_id']);
    }

    public function getDependencies($taskId)
    {
        $dependencies = $this->taskRepository->getDependencies($taskId);

        return response()->json([
            'message' => 'Dependencies retrieved successfully',
            'dependencies' => $dependencies,
        ]);
    }


    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $user = auth()->user();

        if (!$user->hasPermissionTo('update task status')) {
            return response()->json([
                'message' => 'You are not authorized to update this task status.',
            ], 403);
        }

        $data = $request->validated();

        $result = $this->taskRepository->updateStatus($id, $data['status'], $user->id);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'task' => $result['task'],
            ]);
        }

        return response()->json([
            'message' => $result['message'],
        ], 403);
    }

}
