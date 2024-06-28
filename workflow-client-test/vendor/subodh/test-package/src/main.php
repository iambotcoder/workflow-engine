<?php

namespace Subodh\TestPackage;


require_once 'functions.php';

// Sample Usage

// print("\nEnter Workflow Name : ");
// $workflowName = trim(fgets(STDIN));
// print("\nEnter Workflow Id : ");
// $workflowId = trim(fgets(STDIN));

// $workFlow = createWorkflow("Intern-WorkFlow", "wf1");

// $workFlow->addStep("st1", "Intern");
// $workFlow->addStep("st2", "FLA");
// $workFlow->addStep("st3", "SLA");
// $workFlow->addStep("st4", "HR");

// $workFlow->display();

// // print("\nEnter WorkProcess Name : ");
// // $workProcessName = trim(fgets(STDIN));
// // print("\nEnter WorkProcess Id : ");
// // $workProcesId = trim(fgets(STDIN));

// $workProcess = createWorkProcess($workFlow, "subodh-Attendance-workprocess", "wp1");
// print("\nInitial Process Status:");
// $workProcess->displayCurrentStatus();

// accessWorkProcess($workProcess);

// manageWorkflow();


// $workFlow = createWorkflow("Intern-WorkFlow", "wf1");
// saveWorkFlow($workFlow);

// $step1 = $workFlow->addStep("st1", "Intern");
// // print_r($step);
// saveStep($step1);
// incrementWorkflowLength("wf1");

// $step2 = $workFlow->addStep("st2", "FLA");
// saveStep($step2);
// incrementWorkflowLength("wf1");

// $step3 = $workFlow->addStep("st3", "SLA");
// saveStep($step3);
// incrementWorkflowLength("wf1");

// $step4 = $workFlow->addStep("st4", "HR");
// saveStep($step4);
// incrementWorkflowLength("wf1");

// decrementWorkflowLength("wf1");


// $workFlow1 = createWorkflow("GatePass-WorkFlow", "wf11");

// $workFlow1->addStep("st1", "Employee");
// $workFlow1->addStep("st2", "FLA");
// $workFlow1->addStep("st3", "SLA");
// $workFlow1->addStep("st4", "Admin");

// $workFlow1->display();

// Save
// saveWorkFlow($workFlow);

// Display

// step 1 
// showWorkflow();

// Step 2
// showDetailedWorkflow("wf1");

// step 3
// Database to Object
// createWorkflowObject("wf1");
// Update 
// $workFlow1->workflow_name = 'test';
// modifyWorkflow($workFlow1);

// Delete a workflow
// deleteWorkflow($workFlow);

// $workFlow1->display();
// showWorkflow();

// $workFlow = createWorkflow("Intern-WorkFlow", "wf1");
// $step1 = $workFlow->addStep("st1", "Intern");
// $step2 = $workFlow->addStep("st2", "FLA");
// saveWorkFlow($workFlow);
// $workFlow = fetchWorkflowObjectFromDB("wf1");

// $step = $workFlow->addStep("st3", "SLA");
// saveStep($step);
// $step = $workflow->addStep("st4", "Admin");
// saveStep($step);
// print_r($step);
// print("\n");
// print_r($workFlow);
// saveWorkFlow($workFlow);



// $workflow = fetchWorkflowObjectFromDB("TWF1");
// $workflow->display();
// $workflow->addStep("TWF1-st4","own4");
// print_r($workflow);
// modifyWorkflow($workflow);
// $workflow1 = fetchWorkflowObjectFromDB("TWF1");
// print_r($workflow1);

manageWorkflow();
?>
