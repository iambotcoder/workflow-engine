<?php

namespace Subodh\TestPackage;

class Step {
    public $workflow_id_;
    public $step_id_;
    public $step_owner_role;
    public $step_position;
    public $step_name;
    public $step_description;
    public $step_owner_name;
    public $step_next_step = null;
    public $step_previous_step = null;
    public $step_on_success = null;
    public $step_on_failure = null;

    public function __construct($workflow_id_, $step_id_, $step_owner_role, $step_position,$step_name="",$step_description="",$step_owner_name="",$step_next_step=null,$step_previous_step=null,$step_on_success=null,$step_on_failure=null) {
        $this->workflow_id_ = $workflow_id_;
        $this->step_id_ = $step_id_;
        $this->step_owner_role = $step_owner_role;
        $this->step_position = $step_position;
        $this->step_name = $step_name;
        $this->step_description = $step_description;
        $this->step_owner_name = $step_owner_name;
        $this->step_next_step = $step_next_step;
        $this->step_previous_step = $step_previous_step;
        $this->step_on_success = $step_on_success;
        $this->step_on_failure = $step_on_failure;
    }

    // Add other step-specific methods here
}

?>
