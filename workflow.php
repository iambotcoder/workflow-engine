<?php
function horizontalLine($char) {
    print("\n");
    for ($temp = 0; $temp < 50; $temp++) {
        print($char);
    }
}

class Step {
    public $workflow_id;
    public $step_id;
    public $step_owner;
    public $step_owner_name;
    public $step_position;
    public $nextStep = null;
    public $previousStep = null;
    public $onSuccess = null;
    public $onFailure = null;

    public function __construct($workflow_id, $step_id, $step_owner, $step_position) {
        $this->workflow_id = $workflow_id;
        $this->step_id = $step_id;
        $this->step_owner = $step_owner;
        $this->step_position = $step_position;
    }
}

class Workflow {
    public $workflow_name;
    public $workflow_id;
    public $workflow_head_node = null;

    public function __construct($name, $workflow_id) {
        $this->workflow_name = $name;
        $this->workflow_id = $workflow_id;
    }

    public function addStep($step_id, $step_owner) {
        $step_position = 1;
        if ($this->workflow_head_node !== null) {
            $current = $this->workflow_head_node;
            while ($current->nextStep !== null) {
                $current = $current->nextStep;
                $step_position++;
            }
            $step_position++;
        }
        $new_step = new Step($this->workflow_id, $step_id, $step_owner, $step_position);
        if ($this->workflow_head_node === null) {
            $this->workflow_head_node = $new_step;
        } else {
            $current = $this->workflow_head_node;
            while ($current->nextStep !== null) {
                $current = $current->nextStep;
            }
            $current->nextStep = $new_step;
            $new_step->previousStep = $current;
        }
    }

    public function addStepAtFront($step_id, $step_owner) {
        $step_position = 1;
        $new_step = new Step($this->workflow_id, $step_id, $step_owner, $step_position);
        if ($this->workflow_head_node !== null) {
            $new_step->nextStep = $this->workflow_head_node;
            $this->workflow_head_node->previousStep = $new_step;
            $this->workflow_head_node = $new_step;
            $this->updateStepPositions();
        } else {
            $this->workflow_head_node = $new_step;
        }
    }

    public function addStepAtEnd($step_id, $step_owner) {
        $this->addStep($step_id, $step_owner);
    }

    public function addStepInMiddle($position, $step_id, $step_owner) {
        if ($position <= 1) {
            $this->addStepAtFront($step_id, $step_owner);
            return;
        }

        $new_step = new Step($this->workflow_id, $step_id, $step_owner, $position);
        $current = $this->workflow_head_node;
        $currentPosition = 1;

        while ($current !== null && $currentPosition < $position - 1) {
            $current = $current->nextStep;
            $currentPosition++;
        }

        if ($current === null || $current->nextStep === null) {
            $this->addStepAtEnd($step_id, $step_owner);
        } else {
            $new_step->nextStep = $current->nextStep;
            $new_step->previousStep = $current;
            if ($current->nextStep !== null) {
                $current->nextStep->previousStep = $new_step;
            }
            $current->nextStep = $new_step;
            $this->updateStepPositions();
        }
    }

    public function modifyStep($step_id, $new_step_owner) {
        $current = $this->workflow_head_node;
        while ($current !== null) {
            if ($current->step_id == $step_id) {
                $current->step_owner = $new_step_owner;
                return;
            }
            $current = $current->nextStep;
        }
        print("\nStep with id $step_id not found.");
    }

    public function deleteStep($step_id) {
        $current = $this->workflow_head_node;
        while ($current !== null) {
            if ($current->step_id == $step_id) {
                if ($current->previousStep !== null) {
                    $current->previousStep->nextStep = $current->nextStep;
                } else {
                    $this->workflow_head_node = $current->nextStep;
                }
                if ($current->nextStep !== null) {
                    $current->nextStep->previousStep = $current->previousStep;
                }
                $this->updateStepPositions();
                return;
            }
            $current = $current->nextStep;
        }
        print("\nStep with id $step_id not found.");
    }

    private function updateStepPositions() {
        $current = $this->workflow_head_node;
        $position = 1;
        while ($current !== null) {
            $current->step_position = $position;
            $position++;
            $current = $current->nextStep;
        }
    }

    public function display() {
        print("\nWorkflow Id : " . $this->workflow_id);
        print("\nWorkflow Name : " . $this->workflow_name);
        $step = $this->workflow_head_node;
        while ($step !== null) {
            print("\nWorkflow Id : " . $step->workflow_id);
            print("\nStep Id : " . $step->step_id);
            print("\nStep Owner : " . $step->step_owner);
            print("\nStep Owner Name: " . $step->step_owner_name);
            print("\nStep Position: " . $step->step_position);
            print("\nOn Success: " . ($step->onSuccess ? $step->onSuccess->step_id : "None"));
            print("\nOn Failure: " . ($step->onFailure ? $step->onFailure->step_id : "None"));
            if ($step->nextStep) {
                print("\nNext Step : " . $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                print("\nPrevious Step : " . $step->previousStep->step_id);
            }
            $step = $step->nextStep;
            horizontalLine("-");
        }
    }
}

class Process {
    private $workflow;
    private $work_process_name;
    private $work_process_id;
    private $current_stage = 1;

    public function __construct($workflow, $work_process_name, $work_process_id) {
        $this->workflow = $workflow;
        $this->work_process_name = $work_process_name;
        $this->work_process_id = $work_process_id;
        $this->initializeProcessSteps();
    }

    private function initializeProcessSteps() {
        $current = $this->workflow->workflow_head_node;
        while ($current !== null) {
            if ($current->nextStep !== null) {
                $current->onSuccess = $current->nextStep;
            }
            if ($current->previousStep !== null) {
                $current->onFailure = $current->previousStep;
            }
            print("\nEnter step owner name for step ID " . $current->step_id . " with Step owner as ".$current->step_owner. " : ");
            $current->step_owner_name = trim(fgets(STDIN));
            $current = $current->nextStep;
        }
    }

    public function getCurrentStage() {
        return $this->current_stage;
    }

    public function getProcessName() {
        return $this->work_process_name;
    }

    public function displayCurrentStatus() {
        print("\nProcess Current Stage : " . $this->getCurrentStage());
        print("\nProcess Current Name : " . $this->getProcessName());
        $step = $this->workflow->workflow_head_node;
        $temp = $this->current_stage - 1;
        if ($temp < 0) {
            print("\nRevoked Workflow");
            return;
        }
        while ($temp > 0 && $step !== null) {
            $step = $step->nextStep;
            $temp--;
        }
        if ($step !== null) {
            print("\nCurrent Workflow Position");
            print("\nWorkflow Id : " . $step->workflow_id);
            print("\nStep Id : " . $step->step_id);
            print("\nStep Owner : " . $step->step_owner);
            print("\nStep Owner Name: " . $step->step_owner_name);
            print("\nStep Position: " . $step->step_position);
            print("\nOn Success: " . ($step->onSuccess ? $step->onSuccess->step_id : "None"));
            print("\nOn Failure: " . ($step->onFailure ? $step->onFailure->step_id : "None"));
            if ($step->nextStep) {
                print("\nNext Step : " . $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                print("\nPrevious Step : " . $step->previousStep->step_id);
            }
            horizontalLine("=");
        } else {
            print("\nError: Invalid workflow stage.");
        }
    }

    public function acceptStep() {
        $this->current_stage++;
    }

    public function rejectStep() {
        $this->current_stage--;
    }

    public function revokeStep() {
        $this->current_stage = 0;
    }

    public function resetStage() {
        $this->current_stage = 1;
    }
}

function createWorkflow($workflowName, $workflow_id) {
    $workflow = new Workflow($workflowName, $workflow_id);
    return $workflow;
}

function modifyWorkflow($workflow) {
    while (true) {
        print("\nEnter the operation to perform:");
        print("\n1: Add Step at End");
        print("\n2: Add Step at Front");
        print("\n3: Add Step in Middle");
        print("\n4: Modify Step");
        print("\n5: Delete Step");
        print("\n6: Display Workflow");
        print("\n0: Quit");
        $choice = trim(fgets(STDIN));

        switch ($choice) {
            case '1':
                print("\nEnter step ID: ");
                $step_id = trim(fgets(STDIN));
                print("\nEnter step owner: ");
                $step_owner = trim(fgets(STDIN));
                $workflow->addStep($step_id, $step_owner);
                break;
            case '2':
                print("\nEnter step ID: ");
                $step_id = trim(fgets(STDIN));
                print("\nEnter step owner: ");
                $step_owner = trim(fgets(STDIN));
                $workflow->addStepAtFront($step_id, $step_owner);
                break;
            case '3':
                print("\nEnter position to insert step: ");
                $position = trim(fgets(STDIN));
                print("\nEnter step ID: ");
                $step_id = trim(fgets(STDIN));
                print("\nEnter step owner: ");
                $step_owner = trim(fgets(STDIN));
                $workflow->addStepInMiddle($position, $step_id, $step_owner);
                break;
            case '4':
                print("\nEnter step ID to modify: ");
                $step_id = trim(fgets(STDIN));
                print("\nEnter new step owner: ");
                $new_step_owner = trim(fgets(STDIN));
                $workflow->modifyStep($step_id, $new_step_owner);
                break;
            case '5':
                print("\nEnter step ID to delete: ");
                $step_id = trim(fgets(STDIN));
                $workflow->deleteStep($step_id);
                break;
            case '6':
                $workflow->display();
                break;
            case '0':
                return;
            default:
                print("\nInvalid choice, please try again.");
                break;
        }
    }
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
