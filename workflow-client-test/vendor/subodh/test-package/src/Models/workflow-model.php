<?php

require_once __DIR__ . '/../db_conn.php';
require_once __DIR__ . '/../rb-mysql.php'; 

class Workflow
{
    public function __construct()
    {
        // R::setup('mysql:host=localhost;dbname=workflow_engine', 'root', '');
    }

    public function insert($workflow,$workflow_id_)
    {
        
        $bean = R::dispense('workflow');
        // $bean->workflow_id_ = 'wf'.$this->getNextId();
        $bean->workflow_id_ = $workflow_id_;
        $bean->workflow_name = $workflow->workflow_name;
        $bean->workflow_description = $workflow->workflow_description;
        $bean->workflow_step_len = $workflow->workflow_step_len;
        return R::store($bean);
    }

    public function update($workflow) {
        $bean = R::findOne('workflow', 'workflow_id_ = ?', [$workflow->workflow_id_]);
        
        if ($bean->id) {
            $isModified = false;
    
            if ($bean->workflow_name != $workflow->workflow_name) {
                $bean->workflow_name = $workflow->workflow_name;
                $isModified = true;
            }
            if ($bean->workflow_description != $workflow->workflow_description) {
                $bean->workflow_description = $workflow->workflow_description;
                $isModified = true;
            }
            if ($bean->workflow_step_len != $workflow->workflow_step_len) {
                $bean->workflow_step_len = $workflow->workflow_step_len;
                $isModified = true;
            }
    
            
            if ($isModified) {
                return R::store($bean);
            } else {
                return false; 
            }
        }
        return false; 
    }
    

    public function delete($workflow)
    {
        $bean = R::findone('workflow','workflow_id_ = ?',[$workflow->workflow_id_]);
        if ($bean->id) {
            R::trash($bean);
            return true;
        }
        return false;
    }

    public function get($workflow_id_)
    {
        return R::findone('workflow','workflow_id_ = ?',[$workflow_id_]);
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
