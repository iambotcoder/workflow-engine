<?php

namespace Subodh\TestPackage;

use \mysqli;

$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "workflow_engine"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Create operation
function insertData($tableName, $data) {
    // print(gettype($data));
    // return "";
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    global $conn;
    $keys = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $sql = "INSERT INTO $tableName ($keys) VALUES ($values)";
    if ($conn->query($sql) === TRUE) {
        if($tableName === 'step')
        {
            updateWorkFlowLength($data['workflow_id']);
        }
        return "New record created successfully";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Read operation
function readData($tableName, $condition = "") {
    global $conn;
    $sql = "SELECT * FROM $tableName";
    if (!empty($condition)) {
        $sql .= " WHERE $condition";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return "No records found";
    }
}

// Update operation
function updateData($tableName, $data, $condition) {
    global $conn;
    $data['updated_at'] = date('Y-m-d H:i:s');
    $setClause = "";
    foreach ($data as $key => $value) {
        $setClause .= "$key = '$value', ";
    }
    // print("\n -- ".$setClause);
    $setClause = rtrim($setClause, ", ");
    $sql = "UPDATE $tableName SET $setClause WHERE $condition";
    if ($conn->query($sql) === TRUE) {
        return "Record updated successfully";
    } else {
        return "Error updating record: " . $conn->error;
    }
}

// Delete operation
function deleteData($tableName, $condition=true) {
    global $conn;
    $sql = "DELETE FROM $tableName WHERE $condition";
    if ($conn->query($sql) === TRUE) {
        return "Record deleted successfully";
    } else {
        return "Error deleting record: " . $conn->error;
    }
}

function countOfRowsInTable($tableName, $condition=true)
{
    global $conn;
    $sql = "SELECT COUNT(*) As count FROM $tableName WHERE $condition";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'];
    } else {
        die('Query Error: ' . $conn->error);
    }
}
// print("Count : ". countOfRowsInTable($conn,'workflow'));
function updateWorkFlowLength($workflow_id)
{
    // print($workflow_id);
    $workflow_id = (int)$workflow_id;
    global $conn;
    $tupleCount = countOfRowsInTable('step',"workflow_id = '$workflow_id'");
    $data = array();
    $data['workflow_step_len'] = $tupleCount;

    // updateData($conn, $tableName, $data, $condition)
    updateData("workflow", $data,"workflow_id = '$workflow_id'");
    // print($tupleCount);
}
function displayWorkflow($WorkFlowWithSteps)
{
    $workflow = $WorkFlowWithSteps[0][0];
    $steps = $WorkFlowWithSteps[1];
        echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
        echo "\n║                           Workflow Details                              ║";
        echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
        printf("\n");
        printf("║ %-20s : %-48s ║\n", "Workflow Id", $workflow['workflow_id']);
        printf("║ %-20s : %-48s ║\n", "Workflow Name", $workflow['workflow_name']);
        printf("║ %-20s : %-48s ║\n", "Workflow Length", $workflow['workflow_step_len']);
        echo "╚═════════════════════════════════════════════════════════════════════════╝";
        foreach($steps as $key => $value)
        {
            $step = $value;
            echo "\n╔═════════════════════════════════════════════════════════════════════════╗";
            echo "\n║                             Step Details                                ║";
            echo "\n╠═════════════════════════════════════════════════════════════════════════╣";
            printf("\n");
            printf("║ %-20s : %-48s ║\n", "Step Id", $step['step_id']);
            printf("║ %-20s : %-48s ║\n", "Step Owner", $step['step_owner_role']);
            printf("║ %-20s : %-48s ║\n", "Step Owner Name", $step['step_owner_name']);
            printf("║ %-20s : %-48s ║\n", "Step Position", $step['step_position']);
            printf("║ %-20s : %-48s ║\n", "On Success", $step['step_on_success'] ? $step['step_on_success'] : "None");
            printf("║ %-20s : %-48s ║\n", "On Failure", $step['step_on_failure'] ? $step['step_on_failure'] : "None");
            if ($step['step_next_step']) {
                printf("║ %-20s : %-48s ║\n", "Next Step", $step['step_next_step']);
            }
            if ($step['step_previous_step']) {
                printf("║ %-20s : %-48s ║\n", "Previous Step", $step['step_previous_step']);
            }
            echo "╚═════════════════════════════════════════════════════════════════════════╝";
        }
}

// displayWorkflow();
// manageWorkflow();

$step_data = [
    'workflow_id' => 1,
    'step_id' => 1,
    'step_name' => 'Initial Step___' ,
    'step_description' => 'This is the initial step of the workflow.',
    'step_owner_role' => 'Admin_',
    'step_owner_name' => 'John Doe__',
    'step_position' => 1,
    'step_on_success' => 2,
    'step_on_failure' => null,
    'step_next_step' => 2,
    'step_previous_step' => null,
];

// insertData('step',$step_data);

// print(updateWorkFlowLength(1));
// deleteData('step',"workflow_id = 1");

// updateData('step',$step_data,"step_id = '1'");
$conn->close();
?>
