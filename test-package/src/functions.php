<?php

namespace Subodh\TestPackage;

require_once 'Workflow.php';
require_once 'Process.php';

function prints($val)
{
    print($val);
}
prints("In Functions.php");
function horizontalLine($char) {
    print("\n");
    for ($temp = 0; $temp < 50; $temp++) {
        print($char);
    }
}

function createWorkflow($workflowName, $workflow_id) {
    $workflow = new Workflow($workflowName, $workflow_id);
    return $workflow;
}

function createWorkProcess($workflow_name, $work_process_name, $work_process_id) {
    $workProcess = new Process($workflow_name, $work_process_name, $work_process_id);
    return $workProcess;
}

function accessWorkProcess($workProcess) {
    while (true) {
        print("\nEnter the operation to perform:");
        print("\n1: Accept ");
        print("\n2: Reject ");
        print("\n3: Revoke ");
        print("\n4: Status ");
        print("\n0: Quit");
        $choice = trim(fgets(STDIN));

        switch ($choice) {
            case '1':
                $workProcess->acceptStep();
                break;
            case '2':
                $workProcess->rejectStep();
                break;
            case '3':
                $workProcess->revokeStep();
                break;
            case '4':
                $workProcess->displayCurrentStatus();
                break;
            case '0':
                return;
            default:
                print("\nInvalid choice, please try again.");
                break;
        }
    }
}

?>
