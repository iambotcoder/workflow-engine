<?php

use PHPUnit\Framework\TestCase;
use YourVendor\WorkflowManager\Workflow;
use YourVendor\WorkflowManager\Process;

class WorkflowTest extends TestCase {
    public function testAddStep() {
        $workflow = new Workflow("Test Workflow", "wf1");
        $workflow->addStep("st1", "Intern");
        $this->assertNotNull($workflow->workflow_head_node);
        $this->assertEquals("st1", $workflow->workflow_head_node->step_id);
    }

    // Add more tests for other methods
}
