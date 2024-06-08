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
        $temp = $this->current_stage - 1; // Adjust for 0-based index
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
        // Logic for rejecting a step (if any) can be added here
        return $this->current_stage;
    }

    public function revokeStep() {
        // Logic for revoking a step (if any) can be added here
        return $this->current_stage;
    }
}

// Sample Usage
$interWorkflow = new Workflow("Intern-WorkFlow-Subodh", 1);
$interWorkflow->addStep(1, "Intern");
$interWorkflow->addStep(2, "FLA");
$interWorkflow->addStep(3, "HR");

print("\nInitial Workflow Display:");
$interWorkflow->display();

$workProcess = new Process($interWorkflow);
print("\nInitial Process Status:");
$workProcess->displayCurrentStatus();

print("\nAccepting Step...");
$workProcess->acceptStep();
$workProcess->displayCurrentStatus();
?>
