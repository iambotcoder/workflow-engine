<?php
    function horizontalLine()
    {
        print("\n_________________________________________________________________________________________________________________");
    }
    class Step
    {
        public $workflow_id;
        public $step_id;
        public $step_owner;
        public $nextStep;
        public $previousStep;
        public function __construct($workflow_id,$step_id,$step_owner,$nextStep=null,$previousStep=null)
        {
            $this->workflow_id = $workflow_id;
            $this->step_id = $step_id;
            $this->step_owner = $step_owner;
            $this->nextStep = $nextStep;
            $this->previousStep = $previousStep;
        }
    }
    class Workflow {
        public $workflow_name;
        public $workflow_id;
        public $workflow_head_node;

        public function __construct($name,$workflow_id)
        {
            $this->workflow_name = $name;
            $this->workflow_id = $workflow_id;
            $this->workflow_head_node = null;
        }

        public function addStep($workflow_id,$step_id,$step_owner)
        {
            $current_workflow_head_node = $this->workflow_head_node;
            $step = new Step($workflow_id,$step_id,$step_owner);
            if($current_workflow_head_node == null)
            {
                $this->workflow_head_node = & $step;
            }
            else
            {
                $current = $this->workflow_head_node;
                while($current != null && $current->nextStep != null)
                {
                    $current = $current->nextStep;
                }
                $current->nextStep = &$step;
                $step->previousStep = &$current;
            }
        }
        public function display()
        {
            print( "\n Workflow Id : ".$this->workflow_id);
            print( "\n Workflow Name : " .$this->workflow_name);
            $step = $this->workflow_head_node;
            while($step != null)
            {
                // workflow_id,$step_id,$step_owner,$nextStep=null,$previousStep=null
                print( "\n Work flow id : ".$step->workflow_id);
                print( "\n step id : ".$step->step_id);
                print( "\n step owner : ".$step->step_owner);
                if($step->nextStep)
                print( "\n next step : ".$step->nextStep->step_id);
                if($step->previousStep)
                print( "\n previous step : ".$step->previousStep->step_id);    
                $step = $step->nextStep;
                horizontalLine();
            }
            
        }
    }

    class Process 
    {
        public $workflow;
        public $current_workflow_stage;

        public function getStage()
        {
            return $this->current_workflow_stage;
        }
        public function getStatus()
        {
            $step = $this->workflow->workflow_head_node;
            $temp = $this->current_workflow_stage;
            print(" \n ".$temp);
            while($temp>0)
            {
                $step = $step->nextStep;
                $temp = $temp-1;
            }

            // current WorkFlow Position
            print( "\n Work flow id : ".$step->workflow_id);
            print( "\n step id : ".$step->step_id);
            print( "\n step owner : ".$step->step_owner);
            if($step->nextStep)
            print( "\n next step : ".$step->nextStep->step_id);
            if($step->previousStep)
            print( "\n previous step : ".$step->previousStep->step_id);    
            $step = $step->nextStep;
            horizontalLine();

        }
        public function onAccept()
        {
            $this->current_workflow_stage++;

        }
        public function onReject()
        {
            return $this->current_workflow_stage;
        }
        public function onRevoke()
        {
            return $this->current_workflow_stage;
        }
        public function __construct($workflow,)
        {
            $this->workflow = $workflow;
            $this->current_workflow_stage = 1;
        }

    }

    $InterWorkflow = new Workflow("Intern-WorkFlow-Subodh",1);
    $InterWorkflow->display();
    horizontalLine();
    $InterWorkflow->addStep($InterWorkflow->workflow_id,1,"Intern");
    $InterWorkflow->display();
    horizontalLine();
    $InterWorkflow->addStep($InterWorkflow->workflow_id,2,"FLA");
    horizontalLine();
    $InterWorkflow->addStep($InterWorkflow->workflow_id,3,"HR");
    $InterWorkflow->display();
    horizontalLine();
    print("\n Process ");
    $workProcess = new Process($InterWorkflow);
    $workProcess->getStatus();


?>