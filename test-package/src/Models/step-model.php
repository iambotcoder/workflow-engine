<?php

require_once __DIR__ . '/../db_conn.php';
require_once __DIR__.'/../rb-mysql.php';

class StepModel {
    public function __construct()
    {
        // R::setup('mysql:host=localhost;dbname=workflow_engine', 'root', '');
    }
    public function insert($step,$nextStepId=null,$previousStepId=null) {
        // print(" In step model \n");
        // print_r($step);
        // print("\n Step Position : ".$step->step_position);
        // print_r("\n step_next_step : ".is_object($step->step_next_step))?$step->step_next_step:"None";
        // print_r("\n Previous Step : ".is_object($step->step_previous_step))?$step->step_previous_step:"None";
        // print("\n NextStep : ".$step->step_next_step !== null ? $step->step_next_step->id:$step->step_next_step);
        // print("\n Previous Step : ".$step->step_previous_step !== null ? $step->step_previous_step->id:$step->step_previous_step);
        
        // print("\n Inside Step Model : Insert Function ");
        // print_r($step->step_owner_role);
        // print("\n");
        
        $bean = R::dispense('step');
        // print_r($bean);
        $bean->workflow_id_ = $step->workflow_id_;
        $bean->step_id_ = $step->step_id_;
        $bean->step_name = $step->step_name;
        $bean->step_description = $step->step_description;
        $bean->step_owner_role = $step->step_owner_role;
        $bean->step_owner_name = $step->step_owner_name;
        $bean->step_position = $step->step_position;
        $bean->step_on_success = $step->step_on_success;
        $bean->step_on_failure = $step->step_on_failure;
        $bean->step_next_step = is_object($step->step_next_step) ? $step->step_next_step->step_id_:null;
        $bean->step_previous_step = is_object($step->step_previous_step) ? $step->step_previous_step->step_id_:null;
        $id = R::store($bean);

        

        // print("\n NextStep : \n");
        // print_r(is_object($step->step_next_step) ? $step->step_next_step:"None");
        // print("\n previous temp \n");
        // print_r(is_object($step->step_previous_step) ? $step->step_previous_step:"None");
        return $id;
    }


    public function get($id) {
        $step  = R::findOne('step', 'step_id_ = ?', [$id]);
        return $step;
    }

    // Update an existing step
    public function update($step) {
        $bean = R::findOne('step', 'step_id_ = ?', [$step->step_id_]);
        // print("\n");
        // print_r($step->step_id_);
        // print_r($bean);
        if(is_null($bean))
        {
            return ;
        }
        // print("\n Inside Step-model - Update \n Step:");
        // print_r($step);
        // print("\n Bean:");
        // print_r($bean);
        // print("\n");
        if ($bean->id) {


            $isModified = false;

            if ($bean->workflow_id_ != $step->workflow_id_) {
                $bean->workflow_id_ = $step->workflow_id_;
                $isModified = true;
            }
            if ($bean->step_id_ != $step->step_id_) {
                $bean->step_id_ = $step->step_id_;
                $isModified = true;
            }
            if ($bean->step_name != $step->step_name) {
                $bean->step_name = $step->step_name;
                $isModified = true;
            }
            if ($bean->step_description != $step->step_description) {
                $bean->step_description = $step->step_description;
                $isModified = true;
            }
            if ($bean->step_owner_role != $step->step_owner_role) {
                $bean->step_owner_role = $step->step_owner_role;
                $isModified = true;
            }
            if ($bean->step_owner_name != $step->step_owner_name) {
                $bean->step_owner_name = $step->step_owner_name;
                $isModified = true;
            }
            if ($bean->step_position != $step->step_position) {
                $bean->step_position = $step->step_position;
                $isModified = true;
            }
            
            $stepOnSuccess 
            = !is_null($step->step_on_success)
            ? $step->step_on_success->step_id_
            : $step->step_on_success;
            
            $stepOnFailure
            = !is_null($step->step_on_failure)
            ? $step->step_on_failure->step_id_
            : $step->step_on_failure;

            // print("\n=================\n");
            // print_r($step);
            // print("\n=================\n");
            $stepNextStep
            = !is_null($step->step_next_step)
            ? $step->step_next_step->step_id_
            : $step->step_next_step;
            
            $stepPreviousStep
            = !is_null($step->step_previous_step)
            ? $step->step_previous_step->step_id_
            : $step->step_previous_step;

            if ($bean->step_on_success != $stepOnSuccess) {
                $bean->step_on_success = $stepOnSuccess;
                $isModified = true;
            }
            if ($bean->step_on_failure != $stepOnFailure) {
                $bean->step_on_failure = $stepOnFailure;
                $isModified = true;
            }
            if ($bean->step_next_step != $stepNextStep) {
                $bean->step_next_step = $stepNextStep;
                $isModified = true;
            }
            if ($bean->step_previous_step != $stepPreviousStep) {
                $bean->step_previous_step = $stepPreviousStep;
                $isModified = true;
            }
    
            if ($isModified) {
                // print_r("\n Modified Step : ".$step);
                return R::store($bean);
            } else {
                return false; 
            }   
        }
        return false; 
    }
    

    // Delete a step
    public function delete($step) {
        $bean = R::findone('step','step_id_ = ?',[$step->step_id_]);
        if ($bean->id) {
            R::trash($bean);
            return true;
        }
        return false;
    }

    // List all steps
    public function getAll() {
        $steps = R::findAll('step');
        return $steps;
    }

}


// $model = new StepModel();


// $updatedStep = $model->update($updatedStep);

// $model->delete($updatedStep);


// $steps = $model->getAll();
// print_r($steps);

?>
