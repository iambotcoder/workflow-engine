<?php
function horizontalLine() {
    print("\n_________________________________________________________________________________________________________________");
}

class Step {
    public $workflow_id;
    public $step_id;
    public $step_owner;
    public $nextStep = null;
    public $previousStep = null;

    public function __construct($workflow_id, $step_id, $step_owner) {
        $this->workflow_id = $workflow_id;
        $this->step_id = $step_id;
        $this->step_owner = $step_owner;
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
        $new_step = new Step($this->workflow_id, $step_id, $step_owner);
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
        $new_step = new Step($this->workflow_id, $step_id, $step_owner);
        if ($this->workflow_head_node !== null) {
            $new_step->nextStep = $this->workflow_head_node;
            $this->workflow_head_node->previousStep = $new_step;
        }
        $this->workflow_head_node = $new_step;
    }

    public function addStepAtEnd($step_id, $step_owner) {
        $this->addStep($step_id, $step_owner);
    }

    public function addStepInMiddle($position, $step_id, $step_owner) {
        if ($position <= 1) {
            $this->addStepAtFront($step_id, $step_owner);
            return;
        }

        $new_step = new Step($this->workflow_id, $step_id, $step_owner);
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
                return;
            }
            $current = $current->nextStep;
        }
        print("\nStep with id $step_id not found.");
    }

    public function display() {
        print("\nWorkflow Id : " . $this->workflow_id);
        print("\nWorkflow Name : " . $this->workflow_name);
        $step = $this->workflow_head_node;
        while ($step !== null) {
            print("\nWorkflow Id : " . $step->workflow_id);
            print("\nStep Id : " . $step->step_id);
            print("\nStep Owner : " . $step->step_owner);
            if ($step->nextStep) {
                print("\nNext Step : " . $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                print("\nPrevious Step : " . $step->previousStep->step_id);
            }
            $step = $step->nextStep;
            horizontalLine();
        }
    }
}

class Process {
    private $workflow;
    private $current_stage = 1;

    public function __construct($workflow) {
        $this->workflow = $workflow;
    }

    public function getCurrentStage() {
        return $this->current_stage;
    }

    public function displayCurrentStatus() {
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
            if ($step->nextStep) {
                print("\nNext Step : " . $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                print("\nPrevious Step : " . $step->previousStep->step_id);
            }
            horizontalLine();
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

// Sample Usage
$interWorkflow = createWorkflow("Subodh-Intern-workflow", 1);
$interWorkflow->addStep("st-1", "Intern");
$interWorkflow->addStep("st-2", "FLA");
$interWorkflow->addStep("st-3", "HR");
// $interWorkflow->addEndStep(4);

print("\nInitial Workflow Display:");
$interWorkflow->display();

$interWorkflow->addStepAtFront("st-0", "Manager");
$interWorkflow->addStepInMiddle(3, "st-5", "Supervisor");
$interWorkflow->modifyStep("st-2", "Team Lead");
$interWorkflow->deleteStep(3);

print("\nModified Workflow Display:");
$interWorkflow->display();

$workProcess = new Process($interWorkflow);
print("\nInitial Process Status:");
$workProcess->displayCurrentStatus();

print("\nAccepting Step...");
$workProcess->acceptStep();
$workProcess->displayCurrentStatus();

print("\nRejecting Step...");
$workProcess->rejectStep();
$workProcess->displayCurrentStatus();

print("\nRevoking Step...");
$workProcess->revokeStep();
$workProcess->displayCurrentStatus();

print("\nResetting Stage...");
$workProcess->resetStage();
$workProcess->displayCurrentStatus();
?>
