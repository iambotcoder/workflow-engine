<?php

namespace Subodh\TestPackage;

use WorkflowController;

require_once 'Workflow.php';
require_once 'Process.php';
// require_once 'db.php';


require_once '../src/Controllers/WorkflowController.php';


function horizontalLine($char) {
    print("\n");
    for ($temp = 0; $temp < 50; $temp++) {
        print($char);
    }
}

function getUserConfirmation($prompt) {
    echo $prompt . " (y/n): ";
    $response = trim(fgets(STDIN));
    return strtolower($response) === 'y';
}

// ==================== WorkFlow =========================

function createWorkflow($workflowName, $workflow_id) {
    $workflow = new Workflow($workflowName, $workflow_id);
    return $workflow;   
}
function manageWorkflow() {
    $workflow = null;
    
    while (true) {
        echo "\nMenu:";
        echo "\n1. Create Workflow";
        echo "\n2. Add Step";
        echo "\n3. Add Step at Front";
        echo "\n4. Add Step at End";
        echo "\n5. Add Step in Middle";
        echo "\n6. Modify Step";
        echo "\n7. Delete Step";
        echo "\n8. Display Workflow";
        echo "\n9. Save";
        echo "\n10. Exit";
        echo "\nEnter your choice: ";
        
        $choice = trim(fgets(STDIN));
        
        switch ($choice) {
            case 1:
                echo "\nEnter Workflow Name: ";
                $name = trim(fgets(STDIN));
                echo "\nEnter Workflow ID: ";
                $workflow_id = trim(fgets(STDIN));
                $workflow = createWorkflow($name, $workflow_id);
                echo "\nWorkflow created successfully.";
                break;
                
            case 2:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    echo "\nEnter Step Owner: ";
                    $step_owner = trim(fgets(STDIN));
                    $workflow->addStep($step_id, $step_owner);
                    echo "\nStep added successfully.";
                }
                break;
                
            case 3:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    echo "\nEnter Step Owner: ";
                    $step_owner = trim(fgets(STDIN));
                    $workflow->addStepAtFront($step_id, $step_owner);
                    echo "\nStep added at front successfully.";
                }
                break;
                
            case 4:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    echo "\nEnter Step Owner: ";
                    $step_owner = trim(fgets(STDIN));
                    $workflow->addStepAtEnd($step_id, $step_owner);
                    echo "\nStep added at end successfully.";
                }
                break;
                
            case 5:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Position: ";
                    $position = trim(fgets(STDIN));
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    echo "\nEnter Step Owner: ";
                    $step_owner = trim(fgets(STDIN));
                    $workflow->addStepInMiddle($position, $step_id, $step_owner);
                    echo "\nStep added in middle successfully.";
                }
                break;
                
            case 6:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    echo "\nEnter New Step Owner: ";
                    $new_step_owner = trim(fgets(STDIN));
                    $workflow->modifyStep($step_id, $new_step_owner);
                    echo "\nStep modified successfully.";
                }
                break;
                
            case 7:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    echo "\nEnter Step ID: ";
                    $step_id = trim(fgets(STDIN));
                    $workflow->deleteStep($step_id);
                    echo "\nStep deleted successfully.";
                }
                break;
                
            case 8:
                if ($workflow === null) {
                    echo "\nPlease create a workflow first.";
                } else {
                    $workflow->display();
                }
                break;
            case 9:
                saveWorkFlow($workflow);
                break;    
            case 10:
                echo "\nExiting...";
                exit;
                
            default:
                echo "\nInvalid choice. Please try again.";
                break;
        }
    }
}
// ==================== WorkProcess =====================
function createWorkProcess($workflow_name, $work_process_name, $work_process_id) {
    $workProcess = new Process($workflow_name, $work_process_name, $work_process_id);
    return $workProcess;
}

function showWorkFlow()
{
    $controller = new WorkflowController();
    $beans = $controller->getAll();
    // print_r($beans);
    // print(gettype($beans));
    foreach($beans as $bean)
    {
        // print_r($bean);
        $bean->display();
        print(gettype($bean));
    }
}

function saveWorkFlow($workflow)
{
    $controller = new WorkflowController();
    $controller->insert($workflow);

    // $controller->update($workflow);
    
    // $controller->delete($workflow);

    // // print_r($workflow);
    // // $workflow->display();
    // // $userChoice = getUserConfirmation("\nAre You sure you want to store above Workflow : ");
    // // if($userChoice)
    // // {
    // //     (insertData('workflow',$workflow));
    // // }
    // // print_r($workflow);
    // // print("\n");
    // // $workflow_data = [];
    // // $workflow_arr = (array)$workflow; 
    // // $step = $workflow_arr['workflow_head_node'];
    // // // print_r(json_decode(json_encode($workflow), true));
    // // array_push($workflow_data,$workflow_arr['workflow_name']);
    // // array_push($workflow_data,$workflow_arr['workflow_id']);
    // // array_push($workflow_data,$workflow_arr['workflow_step_len']); 
    // // // print_r($workflow_arr['workflow_name']);
    // // // print_r($workflow_arr['workflow_id']);
    // // // print_r($workflow_arr['workflow_step_len']);
    
    // // print_r($workflow_data);
        
    // $step = $workflow->workflow_head_node;
    // while($step != NULL)
    // {
    //     $step_arr = [
    //         'step_id' => $step->step_id,
    //         'step_owner_role' => $step->step_owner,
    //         'step_owner_name' => $step->step_owner_name,
    //         'step_position' => $step->step_position,
    //         'onSuccess' => $step->onSuccess ? $step->onSuccess->step_id : "None",
    //         'onFailure' => $step->onFailure ? $step->onFailure->step_id : "None",
    //         'nextStep' => $step->nextStep ? $step->nextStep->step_id : "None",
    //         'previousStep' => $step->previousStep ? $step->previousStep->step_id : "None"

    //     ];
        
    //     // print($step->step_id."\n");

    //     // print( $step->step_owner);
    //     // print( $step->step_owner_name);
    //     // print( $step->step_position);
    //     // print( $step->onSuccess ? $step->onSuccess->step_id : "None");
    //     // print($step->onFailure ? $step->onFailure->step_id : "None");
    //     // print($step->nextStep? $step->nextStep->step_id:"None");
    //     // print($step->previousStep?$step->previousStep->step_id:"None");
    //     // print_r($step_arr);
    //     insertData('step',$step_arr);
    //     $step = $step->nextStep;
    // }
    
}
function saveStep($step)
{
    $step_arr = (array)$step; 
    print_r($step_arr);
    insertData('step',$step);
       
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
