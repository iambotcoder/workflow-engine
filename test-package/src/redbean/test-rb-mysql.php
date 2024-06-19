<?php
require_once "rb-mysql.php";

try{
    R::setup('mysql:host=localhost;dbname=workflow_engine','root','');
    
    $workflows = R::findAll('workflow');
    // print_r($workflows);
    foreach ($workflows as $workflow)
    {
        print("\n".$workflow->workflow_id);
        print("\n".$workflow->workflow_name);
        print("\n".$workflow->workflow_description);
        print("\n".$workflow->workflow_step_len);
        print("\n".$workflow->workflow_created_at);
        print("\n".$workflow->workflow_updated_at);
    }
    print("Database Connected Successfuly");
}
catch(Exception $e){
    die($e);
}
?>