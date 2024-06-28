<?php
require_once __DIR__ . '/../Models/workflow-model.php';

class WorkflowController
{
    private $workflowModel;

    public function __construct()
    {
        $this->workflowModel = new Workflow();
    }

    public function insert($workflow,$workflow_id_)
    {
        // print("Function called ".$workflow);
        return $this->workflowModel->insert($workflow,$workflow_id_);
    }

    public function update($workflow)
    {
        // print("WORKFLOW CONTROLLER");
        
        return $this->workflowModel->update($workflow);
    }

    public function delete($workflow_id_)
    {
        return $this->workflowModel->delete($workflow_id_);
    }

    public function get($workflow_id_)
    {
        return $this->workflowModel->get($workflow_id_);
    }

    public function getAll()
    {
        return $this->workflowModel->getAll();
    }

    public function getLength($workflow_id_)
    {
        $workflow = $this->workflowModel->get($workflow_id_);
        return $workflow->workflow_step_len;
    }
    public function setLength($workflow_id_,$workflow_len)
    {
        $bean = R::findone('workflow','workflow_id_ = ?',[$workflow_id_]);
        if($bean)
        {
            $bean->workflow_step_len = $workflow_len;
            return R::store($bean);
        }
    }
}
?>
