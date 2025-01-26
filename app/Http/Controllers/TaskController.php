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
    }

    public function index()
    {
        return $this->taskRepository->all();
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        return $this->taskRepository->create($data);
    }

    public function show($id)
    {
        return $this->taskRepository->find($id);
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
