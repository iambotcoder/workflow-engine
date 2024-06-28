<?php

namespace Subodh\TestPackage;

require_once 'Workflow.php';
require_once 'Process.php';
// require_once 'db.php';


require_once '../src/Controllers/WorkflowController.php';
require_once '../src/Controllers/StepController.php';

use WorkflowController;
use StepController;

$workflowController = new WorkflowController();
$stepController = new StepController();

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

function createWorkflow($workflowName, $workflow_id_) {
    $workflow = new Workflow($workflowName, $workflow_id_);
    return $workflow;   
}

function getWorkflowLength($workflow_id_)
{
    global $workflowController;
    return $workflowController->getLength($workflow_id_);
}

function incrementWorkflowLength($workflow_id_)
{
    global $workflowController;
    $len = getWorkflowLength($workflow_id_);
    $workflowController->setLength($workflow_id_,($len+1));
}

function decrementWorkflowLength($workflow_id_)
{
    global $workflowController;
    $len = getWorkflowLength($workflow_id_);
    $workflowController->setLength($workflow_id_,($len-1));
}

function modifyWorkflow($workflow)
{
    global $workflowController;
    global $stepController;
    
    $workflowController->update($workflow);
    $step = $workflow->workflow_head_node;
    while ($step !== null) 
    {
        // print(" Step : ".$step->step_id_."\n");
        $bean = $stepController->get($step->step_id_);
        if(is_null($bean))
        {
            $stepController->insert($step);    
        }
        else
        {
            $stepController->update($step);
        }
        // print($bean);
        $step = $step->step_next_step;
    }
}

function deleteWorkflow($workflow)
{
    global $workflowController;
    $workflowController->delete($workflow);
}
function displayWorkflow($workflow)
{
    echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
    echo "\n║                           Workflow Details                              ║";
    echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
    printf("\n");
    printf("║ %-20s : %-48s ║\n", "Workflow Id", $workflow['workflow_id_']);
    printf("║ %-20s : %-48s ║\n", "Workflow Name", $workflow['workflow_name']);
    printf("║ %-20s : %-48s ║\n", "Workflow Length", $workflow['workflow_step_len']);
    echo "╚═════════════════════════════════════════════════════════════════════════╝";
}

function displayStep($step)
{
    print("\n Inside Display Step \n");
    // print_r($step);
        echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
        echo "\n║                             Step Details                                ║";
        echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
        printf("\n");
        printf("║ %-20s : %-48s ║\n", "Step Id", $step->step_id_);
        printf("║ %-20s : %-48s ║\n", "Step Owner", $step->step_owner_role);
        printf("║ %-20s : %-48s ║\n", "Step Owner Name", $step->step_owner_name);
        printf("║ %-20s : %-48s ║\n", "Step Position", $step->step_position);
        printf("║ %-20s : %-48s ║\n", "On Success", $step->step_on_success ? $step->step_on_success->step_id_ : "None");
        printf("║ %-20s : %-48s ║\n", "On Failure", $step->step_on_failure ? $step->step_on_failure->step_id_ : "None");
        if ($step->step_next_step) {
            printf("║ %-20s : %-48s ║\n", "Next Step", $step->step_next_step->step_id_);
        }
        if ($step->step_previous_step) {
            printf("║ %-20s : %-48s ║\n", "Previous Step", $step->step_previous_step->step_id_);
        }
        echo "╚═════════════════════════════════════════════════════════════════════════╝";

}

function showStoredWorkflow()
{
    global $workflowController;
    global $stepController;
    $beans = $workflowController->getAll();
    // print_r($stepController->getAllByWorflow());
    // print_r($stepController);
    foreach($beans as $bean)
    {
        displayWorkflow($bean);
        // $steps = $stepController->getAllByWorflow($bean['workflow_id_']);
        // print_r($bean['workflow_id_']);
        // print_r($steps);
        // foreach($steps as $step)
        // {
            // displayStep($step);
            // print("\n".$step->step_id_." - ".$step->id);
            // print("\n".$stepController->get($step->id));
        // }
        // $stepBeans = $stepController->get
    }
}

function showDetailedWorkflow($workflow_id_)
{
    $worflow = fetchWorkflowObjectFromDB($workflow_id_);
    $worflow->display();
    
    global $workflowController;
    global $stepController;
    // $bean = $workflowController->get($workflow_id_);
    
    // print_r($stepController->getAllByWorflow());
    // print_r($stepController);
    
    // displayWorkflow($worflow);
    // $steps = $worflow->getAllByWorkflowId($bean['workflow_id_']);
    // print_r($bean['workflow_id_']);
    // print_r($steps);
    // foreach($steps as $step)
    // {
        
        // displayStep($step);
        // print("\n".$step->step_id_." - ".$step->id);
        // print("\n".$stepController->get($step->id));
    // }
    // $stepBeans = $stepController->get

}
function manageWorkflow() {
    $workflow = null;
    
    while (true) {
        echo "\nMenu:";
        echo "\n1. Create Workflow";
        echo "\n2. Show Workflows";
        echo "\n3. Exit";
        
        echo "\nEnter your choice: ";
        
        $choice = trim(fgets(STDIN));
        
        switch ($choice) {
            case 1:
                echo "\nEnter Workflow Name: ";
                $name = trim(fgets(STDIN));
                echo "\nEnter Workflow ID: ";
                $workflow_id_ = trim(fgets(STDIN));
                $workflow = createWorkflow($name, $workflow_id_);
                echo "\nWorkflow created successfully.";
                $createWorkflowChoice = 0;
                while($createWorkflowChoice != 9)
                {
                    echo "\n1. Add Step";
                    echo "\n2. Add Step at Front";
                    echo "\n3. Add Step at End";
                    echo "\n4. Add Step in Middle";
                    echo "\n5. Modify Step";
                    echo "\n6. Delete Step";
                    echo "\n7. Display Workflow";
                    echo "\n8. Save";
                    echo "\n9. Exit";

                    $createWorkflowChoice = trim(fgets(STDIN));
                    
                    switch($createWorkflowChoice)
                    {
                        case 1:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                echo "\nEnter Step Owner: ";
                                $step_owner_role = trim(fgets(STDIN));
                                $step = $workflow->addStep($step_id_, $step_owner_role);
                                
                                // saveStep($step);
                                echo "\nStep added successfully.";
                            }
                            break;
                            
                        case 2:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                echo "\nEnter Step Owner: ";
                                $step_owner_role = trim(fgets(STDIN));
                                $workflow->addStepAtFront($step_id_, $step_owner_role);
                                echo "\nStep added at front successfully.";
                            }
                            break;
                            
                        case 3:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                echo "\nEnter Step Owner: ";
                                $step_owner_role = trim(fgets(STDIN));
                                $workflow->addStepAtEnd($step_id_, $step_owner_role);
                                echo "\nStep added at end successfully.";
                            }
                            break;
                            
                        case 4:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Position: ";
                                $position = trim(fgets(STDIN));
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                echo "\nEnter Step Owner: ";
                                $step_owner_role = trim(fgets(STDIN));
                                $workflow->addStepInMiddle($position, $step_id_, $step_owner_role);
                                echo "\nStep added in middle successfully.";
                            }
                            break;
                            
                        case 5:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                echo "\nEnter New Step Owner: ";
                                $new_step_owner = trim(fgets(STDIN));
                                $workflow->modifyStep($step_id_, $new_step_owner);
                                echo "\nStep modified successfully.";
                            }
                            break;
                            
                        case 6:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                echo "\nEnter Step ID: ";
                                $step_id_ = trim(fgets(STDIN));
                                $workflow->deleteStep($step_id_);
                                echo "\nStep deleted successfully.";
                            }
                            break;
                            
                        case 7:
                            if ($workflow === null) {
                                echo "\nPlease create a workflow first.";
                            } else {
                                $workflow->display();
                            }
                            break;
                        case 8:
                            saveWorkFlow($workflow);
                            break;
                    }
                }
                break;
            case 3:
                echo "\nExiting...";
                exit;
            case 2:
                $showWorkflowChoice = 0;
                showStoredWorkflow();
                while($showWorkflowChoice != 4)
                {  
                    echo "\n1. Choose A Workflow to modify ";
                    echo "\n2. Clone a workflow ";
                    echo "\n3. Display Workflow In detail ";
                    echo "\n4. Exit ";
                    $showWorkflowChoice = trim(fgets(STDIN));
                    switch($showWorkflowChoice)
                    {
                        case 1:
                            echo "\nEnter Workflow ID: ";
                            $workflow_id_ = trim(fgets(STDIN));
                            $workflow = fetchWorkflowObjectFromDB($workflow_id_);
                            print(" You have Choosen Worflow : \n".$workflow->workflow_id_);
                            // print_r($workflow);
                            
                            $modifyExistingWorkflow = 0;
                            
                            while($modifyExistingWorkflow != 10)
                            {
                                echo "\n1. Add Step";
                                echo "\n2. Add Step at Front";
                                echo "\n3. Add Step at End";
                                echo "\n4. Add Step in Middle";
                                echo "\n5. Modify Step";
                                echo "\n6. Delete Step";
                                echo "\n7. Edit Workflow Details";
                                echo "\n8. Display Modifed Workflow";
                                echo "\n9. save Workflow";
                                echo "\n10. Exit";

                                $modifyExistingWorkflow = trim(fgets(STDIN));

                                switch($modifyExistingWorkflow)
                                {
                                    case 1:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            echo "\nEnter Step Owner: ";
                                            $step_owner_role = trim(fgets(STDIN));
                                            $step = $workflow->addStep($step_id_, $step_owner_role);
                                            
                                            // saveStep($step);
                                            echo "\nStep added successfully.";
                                        }
                                        break;
                                        
                                    case 2:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            echo "\nEnter Step Owner: ";
                                            $step_owner_role = trim(fgets(STDIN));
                                            $workflow->addStepAtFront($step_id_, $step_owner_role);
                                            echo "\nStep added at front successfully.";
                                        }
                                        break;
                                        
                                    case 3:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            echo "\nEnter Step Owner: ";
                                            $step_owner_role = trim(fgets(STDIN));
                                            $workflow->addStepAtEnd($step_id_, $step_owner_role);
                                            echo "\nStep added at end successfully.";
                                        }
                                        break;
                                        
                                    case 4:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Position: ";
                                            $position = trim(fgets(STDIN));
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            echo "\nEnter Step Owner: ";
                                            $step_owner_role = trim(fgets(STDIN));
                                            $workflow->addStepInMiddle($position, $step_id_, $step_owner_role);
                                            echo "\nStep added in middle successfully.";
                                        }
                                        break;
                                        
                                    case 5:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            echo "\nEnter New Step Owner: ";
                                            $new_step_owner = trim(fgets(STDIN));
                                            $workflow->modifyStep($step_id_, $new_step_owner);
                                            echo "\nStep modified successfully.";
                                        }
                                        break;
                                        
                                    case 6:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter Step ID: ";
                                            $step_id_ = trim(fgets(STDIN));
                                            $workflow->deleteStep($step_id_);
                                            echo "\nStep deleted successfully.";
                                        }
                                        break;
                                    
                                    case 7:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            echo "\nEnter new Workflow name : [ $workflow->workflow_name ] ";
                                            $worflow_name = trim(fgets(STDIN));
                                            $workflow->workflow_name = $worflow_name;
                                            modifyWorkflow($workflow);
                                            echo "\Modifed Workflow Details successfully.";
                                        }
                                        break;
                                    case 8:
                                        if ($workflow === null) {
                                            echo "\nPlease choose a workflow first.";
                                        } else {
                                            $workflow->display();
                                        }
                                        break;
                                    case 9:
                                        modifyWorkflow($workflow);
                                        break;
                                }
                            }
                            


                            break;
                        case 3:
                            echo "\nEnter Workflow ID: ";
                            $workflow_id_ = trim(fgets(STDIN));
                            showDetailedWorkflow($workflow_id_);
                            break;
                        case 4:
                            
                            break;
                        default:
                            print("Invalid Choice ");
                            break;
                    }
                }
                break;
            default:
                echo "\nInvalid choice. Please try again.";
                break;
        }
    }
}


function saveWorkFlow($workflow)
{
    global $workflowController;
    // print_r($workflow);
    
    $workflowController->insert($workflow,$workflow->workflow_id_);
    $step = $workflow->workflow_head_node;
    while ($step !== null) 
    {
        // print_r($step);
        saveStep($step);
        $step = $step->step_next_step;
    }
    

    // $workflowController->update($workflow);
    
    // $workflowController->delete($workflow);

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
    // // array_push($workflow_data,$workflow_arr['workflow_id_']);
    // // array_push($workflow_data,$workflow_arr['workflow_step_len']); 
    // // // print_r($workflow_arr['workflow_name']);
    // // // print_r($workflow_arr['workflow_id_']);
    // // // print_r($workflow_arr['workflow_step_len']);
    
    // // print_r($workflow_data);
        
    // $step = $workflow->workflow_head_node;
    // while($step != NULL)
    // {
    //     $step_arr = [
    //         'step_id_' => $step->step_id_,
    //         'step_owner_role' => $step->step_owner_role,
    //         'step_owner_name' => $step->step_owner_name,
    //         'step_position' => $step->step_position,
    //         'step_on_success' => $step->step_on_success ? $step->step_on_success->step_id_ : "None",
    //         'step_on_failure' => $step->step_on_failure ? $step->step_on_failure->step_id_ : "None",
    //         'step_next_step' => $step->step_next_step ? $step->step_next_step->step_id_ : "None",
    //         'step_previous_step' => $step->step_previous_step ? $step->step_previous_step->step_id_ : "None"

    //     ];
        
    //     // print($step->step_id_."\n");

    //     // print( $step->step_owner_role);
    //     // print( $step->step_owner_name);
    //     // print( $step->step_position);
    //     // print( $step->step_on_success ? $step->step_on_success->step_id_ : "None");
    //     // print($step->step_on_failure ? $step->step_on_failure->step_id_ : "None");
    //     // print($step->step_next_step? $step->step_next_step->step_id_:"None");
    //     // print($step->step_previous_step?$step->step_previous_step->step_id_:"None");
    //     // print_r($step_arr);
    //     insertData('step',$step_arr);
    //     $step = $step->step_next_step;
    // }
    
}

// This Function create workflowObject which is present in Database.
function fetchWorkflowObjectFromDB($workflow_id_)
{   
    global $workflowController;
    global $stepController;

    $workflowBean = $workflowController->get($workflow_id_);
    $workflow_name = $workflowBean->workflow_name;
    $workflow = createWorkflow($workflow_name,$workflow_id_);
    $steps = $stepController->getAllByWorkflowId($workflow_id_);
    
    
    foreach($steps as $step)
    {
        $workflow->addStep($step->step_id_,$step->step_owner_role);
    }
    return $workflow;
}

// ==================== WorkProcess =====================
function createWorkProcess($workflow_name, $work_process_name, $work_process_id) {
    $workProcess = new Process($workflow_name, $work_process_name, $work_process_id);
    return $workProcess;
}

function saveStep($step)
{
    global $stepController;
    $stepController->insert($step);
}

function deleteStep($step)
{
    global $stepController;
    $stepController->delete($step);
}

function modifyStep($step)
{
    global $stepController;
    // $stepController->update($step);
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
