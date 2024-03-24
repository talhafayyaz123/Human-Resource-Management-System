<?php


namespace App\Dto;

use Carbon\Carbon;

class CreateTaskDto
{
    public string $name;
    public string $employee_id;
    public string $status;
    public string $priority;
    public string $description;
    public $resubmit_on;
    public string $task_id;

    public function __construct($name, $employee_id, $status = 'new', $priority = 'low', $description = '', $resubmit_on = '', $task_id = 'fnkwfn')
    {

        $this->name = $name;
        $this->employee_id = $employee_id;
        $this->status = $status;
        $this->priority = $priority;
        $this->description = $description;
        $this->resubmit_on = isset($resubmit_on) ? Carbon::parse($resubmit_on) : Carbon::now();
        $this->task_id = $task_id;
    }
}
