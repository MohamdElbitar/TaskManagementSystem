<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function filterByStatus($status);
    public function filterByDueDateRange($startDate, $endDate);
    public function filterByAssignee($assigneeId);
    public function addDependency($taskId, $dependencyId);
    public function getDependencies($taskId);
    public function updateStatus($id, $status, $userId);
}
