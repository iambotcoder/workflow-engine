<?php
namespace Subodh\TestPackage;

require_once __DIR__ . '/vendor/autoload.php';


use Subodh\TestPackage\Workflow;
use Subodh\TestPackage\Process;


$workFlow = createWorkflow("Intern-WorkFlow", "wf1");

$workFlow->addStep("st1", "Intern");
$workFlow->addStep("st2", "FLA");
$workFlow->addStep("st3", "SLA");
$workFlow->addStep("st4", "HR");

$workFlow->display();

?>