<?php

namespace Subodh\TestPackage;

require_once 'Step.php';

class Workflow {
    public $workflow_name;
    public $workflow_id; 
    public $workflow_head_node = null;
    public $workflow_step_len;
    public $workflow_description;

    public function __construct($name, $workflow_id,$workflow_description="") {
        $this->workflow_name = $name;
        $this->workflow_id = $workflow_id;
        $this->workflow_step_len = 0;
        $this->workflow_description = $workflow_description;
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
        $this->workflow_step_len++;
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
        $this->workflow_step_len++;
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
        $this->workflow_step_len++;
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
                $this->workflow_step_len--;
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
        echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
        echo "\n║                           Workflow Details                              ║";
        echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
        printf("\n");
        printf("║ %-20s : %-48s ║\n", "Workflow Id", $this->workflow_id);
        printf("║ %-20s : %-48s ║\n", "Workflow Name", $this->workflow_name);
        printf("║ %-20s : %-48s ║\n", "Workflow Length", $this->workflow_step_len);
        printf("║ %-20s : %-48s ║\n", "Workflow Description", $this->workflow_description);
        echo "╚═════════════════════════════════════════════════════════════════════════╝";
    
        $step = $this->workflow_head_node;
        while ($step !== null) {
            echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
            echo "\n║                             Step Details                                ║";
            echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
            printf("\n");
            printf("║ %-20s : %-48s ║\n", "Step Id", $step->step_id);
            printf("║ %-20s : %-48s ║\n", "Step Owner", $step->step_owner);
            printf("║ %-20s : %-48s ║\n", "Step Owner Name", $step->step_owner_name);
            printf("║ %-20s : %-48s ║\n", "Step Position", $step->step_position);
            printf("║ %-20s : %-48s ║\n", "On Success", $step->onSuccess ? $step->onSuccess->step_id : "None");
            printf("║ %-20s : %-48s ║\n", "On Failure", $step->onFailure ? $step->onFailure->step_id : "None");
            if ($step->nextStep) {
                printf("║ %-20s : %-48s ║\n", "Next Step", $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                printf("║ %-20s : %-48s ║\n", "Previous Step", $step->previousStep->step_id);
            }
            echo "╚═════════════════════════════════════════════════════════════════════════╝";
            $step = $step->nextStep;
        }
    }
    
}

?>