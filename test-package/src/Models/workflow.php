<?php
// require_once '../../src/rb-mysql.php';
require_once __DIR__ . '/../rb-mysql.php'; // Adjusted path to rb.php

class Workflow
{
    public function __construct()
    {
        R::setup('mysql:host=localhost;dbname=workflow_engine', 'root', '');
    }

    public function insert($workflow)
    {
        // print_r($workflow);
        $bean = R::dispense('workflow');
        // print("Inside Insert");
        // print($workflow->workflow_name);
        // print_r($bean);
        // $bean->id = $this->getNextId();
        $bean->workflow_id_ = 'wf'.$this->getNextId();
        $bean->workflow_name = $workflow->workflow_name;
        $bean->workflow_description = $workflow->workflow_description;
        $bean->workflow_step_len = $workflow->workflow_step_len;
        // print_r($bean);
        return R::store($bean);
    }

    public function update($workflow)
    {
        // print_r($workflow);
        $bean = R::findone('workflow','workflow_id_ = ?',[$workflow->workflow_id]);
        if ($bean->id) {
            $bean->workflow_name = $workflow->workflow_name;
            $bean->workflow_description = $workflow->workflow_description;
            $bean->workflow_step_len = $workflow->workflow_step_len;
            // $bean->updated_at = R::isoDateTime();
            return R::store($bean);
        }
        return false;
    }

    public function delete($workflow)
    {
        $bean = R::findone('workflow','workflow_id_ = ?',[$workflow->workflow_id]);
        if ($bean->id) {
            R::trash($bean);
            return true;
        }
        return false;
    }

    public function get($workflow_id)
    {
        return R::load('workflow', $workflow_id);
    }

    public function getAll()
    {
        return R::findAll('workflow');
    }

    public function getNextId()
    {
        $maxId = R::getCell('SELECT MAX(id) FROM workflow');
        if ($maxId === null) {
            return 1;
        }
        return $maxId + 1;
    }
}
?>
