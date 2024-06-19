<?php
require_once __DIR__ . '/../Models/workflow.php';

class WorkflowController
{
    private $workflowModel;

    public function __construct()
    {
        $this->workflowModel = new Workflow();
    }

    public function insert($workflow)
    {
        // print("Function called ".$workflow);
        return $this->workflowModel->insert($workflow);
    }

    public function update($workflow)
    {
        return $this->workflowModel->update($workflow);
    }

    public function delete($workflow_id)
    {
        return $this->workflowModel->delete($workflow_id);
    }

    public function get($workflow_id)
    {
        return $this->workflowModel->get($workflow_id);
    }

    public function getAll()
    {
        return $this->workflowModel->getAll();
    }
}
?>
