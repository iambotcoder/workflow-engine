<?php

require_once __DIR__ . '/../Models/step-model.php';


class StepController
{
    private $stepModel;

    public function __construct()
    {
        $this->stepModel = new StepModel();
    }

    public function insert($step)
    {
        // $stepNextStep = is_object($step->step_next_step) ? $step->step_next_step:null;
        // $stepPreviousStep = is_object($step->step_previous_step) ? $step->step_previous_step->step_id_:null;
        // $nextRes = null;
        // $prevRes = null;
        // print_r(" Inside Insert Function ");
        // print("\n Prev Step id: ".$stepPreviousStep);
        // print("\n Current Step id : ".$step->step_id_);
        // print("\n Next Step id : ".$stepNextStep);
        
        // print("\n STEP CONTROLLER \n");
        // print("\n CURRENT STEP : ".$step->step_id_);
        if(!is_null($step->step_next_step))
        {
            // print("\n Inside Next Step ");
            // $nextRes = $this->getBystepId($stepNextStep);
            
            // if($nextRes){
                // $nextRes->step_previous_step = $step->step_id_;
                // $this->update($nextRes);
            // }
            $nextStep = $step->step_next_step;
            // print_r($nextStep);
            
            $nextStep->step_previous_step = $step;
            // print("\n Modified Next Step");
            // print_r($nextStep);
            $this->update($nextStep);
        }
        // print_r("\n Next Step : ".$nextRes);
        
        if(!is_null($step->step_previous_step))
        {
            // print("\n Inside Previous Step ");
            // $prevRes = $this->getBystepId($stepPreviousStep);
            // if($prevRes)
            // {
            //     $prevRes->step_next_step = $step->step_id_;
            //     print(" Updated Prev->nextStep as ".$step->step_id_);
            //     print($prevRes);
            //     $this->update($prevRes);
            // }   
            $previousStep = $step->step_previous_step;
            // print_r($previousStep);
            // print("\n Modified Previous Step");
            $previousStep->step_next_step = $step;
            // print_r($previousStep);
            $this->update($previousStep);
        }
        // print_r("\n Previous Step : ".$prevRes);

        // print("\n=======================================\n");
        // 
        return $this->stepModel->insert($step);
    }

    public function update($step)
    {
        // print("\n Updating Step Controller : ");
        // print_r($step);
        
        return $this->stepModel->update($step);
    }

    public function delete($id)
    {
        return $this->stepModel->delete($id);
    }

    public function get($id)
    {
        return $this->stepModel->get($id);
    }

    public function getBystepId($step_id_)
    {
        return R::findOne('step','step_id_ = ?',[$step_id_]);
    }

    public function getAll()
    {
        return $this->stepModel->getAll();
    }
    public function getAllByWorkflowId($workflow_id_)
    {
        $steps = R::findAll('step','workflow_id_ = ?',[$workflow_id_]);
        return $steps;
    }
}
?>
