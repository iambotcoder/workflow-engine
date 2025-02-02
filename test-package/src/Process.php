<?php

namespace Subodh\TestPackage;

require_once 'Step.php';

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
            if ($current->step_next_step !== null) {
                $current->step_on_success = $current->step_next_step;
            }
            if ($current->step_previous_step !== null) {
                $current->step_on_failure = $current->step_previous_step;
            }
            print("\nEnter step owner name for step ID " . $current->step_id_ . " with Step owner as " . $current->step_owner_role . " : ");
            $current->step_owner_name = trim(fgets(STDIN));
            $current = $current->step_next_step;
        }
    }

    public function getCurrentStage() {
        return $this->current_stage;
    }

    public function getProcessName() {
        return $this->work_process_name;
    }

    public function displayCurrentStatus() {
        echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
        echo "\n║                   Current Workflow Status                               ║";
        echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
        printf("\n");
        // Process current stage and name
        printf("║ %-25s : %-43s ║", "Process Current Stage", $this->getCurrentStage());
        printf("\n║ %-25s : %-43s ║", "Process Current Name", $this->getProcessName());
    
        $step = $this->workflow->workflow_head_node;
        $temp = $this->current_stage - 1;
        if ($temp < 0) {
            echo "\n║ Status                : Revoked Workflow                                ║";
        } else {
            while ($temp > 0 && $step !== null) {
                $step = $step->step_next_step;
                $temp--;
            }
            if ($step !== null) {
                // Current workflow position
                // printf("\n║ %-25s : %-43s ║", "Current Workflow Position", "");
                printf("\n║ %-25s : %-43s ║", "Workflow Id", $step->workflow_id_);
                printf("\n║ %-25s : %-43s ║", "Step Id", $step->step_id_);
                printf("\n║ %-25s : %-43s ║", "Step Owner", $step->step_owner_role);
                printf("\n║ %-25s : %-43s ║", "Step Owner Name", $step->step_owner_name);
                printf("\n║ %-25s : %-43s ║", "Step Position", $step->step_position);
                printf("\n║ %-25s : %-43s ║", "On Success", $step->step_on_success ? $step->step_on_success->step_id_ : "None");
                printf("\n║ %-25s : %-43s ║", "On Failure", $step->step_on_failure ? $step->step_on_failure->step_id_ : "None");
                if ($step->step_next_step) {
                    printf("\n║ %-25s : %-43s ║", "Next Step", $step->step_next_step->step_id_);
                }
                if ($step->step_previous_step) {
                    printf("\n║ %-25s : %-43s ║", "Previous Step", $step->step_previous_step->step_id_);
                }
                echo "\n╚═════════════════════════════════════════════════════════════════════════╝";
            }
        }
    }

    

    public function acceptStep() {
        if ($this->current_stage >= $this->workflow->workflow_step_len) {
            print("\nError: Already at the last stage, cannot accept the step.");
            return false;
        }
        $this->current_stage++;
        print("\nSuccess: Moved to the next stage. Current stage is now " . $this->current_stage);
        return true;
    }

    public function rejectStep() {
        if ($this->current_stage <= 0) {
            print("\nError: Already at the initial stage, cannot reject the step.");
            return false;
        }
        $this->current_stage = 0;
        print("\nSuccess: Step rejected. Current stage is now " . $this->current_stage);
        return true;
    }

    public function revokeStep() {
        if ($this->current_stage <= 1) {
            print("\nError: Already at the beginning stage, cannot revoke the step.");
            return false;
        }
        $this->current_stage--;
        print("\nSuccess: Step revoked. Current stage is now " . $this->current_stage);
        return true;
    }

    public function resetStage() {
        if ($this->current_stage == 1) {
            print("\nError: Already at the beginning stage.");
            return false;
        }
        $this->current_stage = 1;
        print("\nSuccess: Stage reset. Current stage is now " . $this->current_stage);
        return true;
    }
}
?>
