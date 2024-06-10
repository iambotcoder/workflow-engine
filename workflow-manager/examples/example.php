<?php

namespace YourVendor\WorkflowManager;

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
            print("\nEnter step owner name for step ID " . $current->step_id . ": ");
            $current->step_owner_name = trim(fgets(STDIN));
            $current = $current->nextStep;
        }
    }

    public function getCurrentStage() {
        return $this->current_stage;
    }

    public function displayCurrentStatus() {
        print("\nWork Process Id : " . $this->work_process_id);
        print("\nWork Process Name : " . $this->work_process_name);
        $stage = $this->getCurrentStage();
        $step = $this->workflow->workflow_head_node;
        while ($step !== null && $step->step_position != $stage) {
            $step = $step->nextStep;
        }
        if ($step !== null) {
            print("\nCurrent Stage : " . $stage);
            print("\nStep Id : " . $step->step_id);
            print("\nStep Owner : " . $step->step_owner);
            print("\nStep Owner Name : " . $step->step_owner_name);
            print("\nStep Position : " . $step->step_position);
            print("\nOn Success : " . ($step->onSuccess ? $step->onSuccess->step_id : "None"));
            print("\nOn Failure : " . ($step->onFailure ? $step->onFailure->step_id : "None"));
            if ($step->nextStep) {
                print("\nNext Step : " . $step->nextStep->step_id);
            }
            if ($step->previousStep) {
                print("\nPrevious Step : " . $step->previousStep->step_id);
            }
            $this->horizontalLine("=");
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

    private function horizontalLine($char) {
        print("\n");
        for ($temp = 0; $temp < 50; $temp++) {
            print($char);
        }
    }
}
