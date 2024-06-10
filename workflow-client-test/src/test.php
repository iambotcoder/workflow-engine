<?php

namespace Subodh\TestPackage;

require __DIR__ . '/../vendor/autoload.php';

use Subodh\TestPackage\Workflow;
use Subodh\TestPackage\Process;


// Sample Usage

// print("\nEnter Workflow Name : ");
// $workflowName = trim(fgets(STDIN));
// print("\nEnter Workflow Id : ");
// $workflowId = trim(fgets(STDIN));

$workFlow = createWorkflow("Intern-WorkFlow", "wf1");

$workFlow->addStep("st1", "Intern");
$workFlow->addStep("st2", "FLA");
$workFlow->addStep("st3", "SLA");
$workFlow->addStep("st4", "HR");

$workFlow->display();

// print("\nEnter WorkProcess Name : ");
// $workProcessName = trim(fgets(STDIN));
// print("\nEnter WorkProcess Id : ");
// $workProcesId = trim(fgets(STDIN));

$workProcess = createWorkProcess($workFlow, "subodh-Attendance-workprocess", "wp1");
print("\nInitial Process Status:");
$workProcess->displayCurrentStatus();

accessWorkProcess($workProcess);

?>