<?php

namespace YourVendor\WorkflowManager;

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
