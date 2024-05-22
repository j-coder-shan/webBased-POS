<?php

session_start();

require_once 'dbcon.php';


// Function to validate input data
function validate(){
    global $conn;
    $validateData = mysqli_real_escape_string($conn, $inputData);
    return trim($validateData);
}


// Function to redirect to a page
function redirect($url,$status){
    
    #_SESSION['status'] = $status;
    header("Location: $url");
    exit(0);
}

// Function to display alert message
function alertMessage(){

    if(isset($_SESSION['status'])){
        $_SESSION['status'];

        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
             <h6> '.$_SESSION['status'].' </h6>
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

        unset($_SESSION['status']);
    }
}

//Insert record into database
function insert($tableName, $data){
    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'".implode("','", $values)."'";

    $quary = "INSERT INTO $table ($finalColumn) VALUES ($FinalValues)";
    if(!mysqli_query($conn, $quary)) {
        echo "Error: " . mysqli_error($conn);
    }
    $result = mysqli_query($conn, $sql);

    return $result;
}


//Update record in database
function updateRecord($tableName, $id, $data){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = '';

    foreach($data as $column => $value){
        $updateDataString .= $column."='".$value."',";
    }

    $finalUpdateData = substr(trim($updateDataString, 0, -1));

    $quary = "UPDATE $table SET $finalUpdateData WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    return $result;
}


//Get all records from database
function getAll($tableName, $status = NULL){
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status')
    {
        $quary = "SELECT * FROM $table WHERE status = '0'";
    }
    else
    {
        $quary = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $quary);
}

//Get record from database using id
function getByID($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $quary = "SELECT * FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $quary);

    if($result){

        if(mysqli_num_rows($result) == 1){

            $row = mysqli_fetch_assoc($result);
            $response = [
                'status' => 404,
                'data' => $row,
                'message' => 'Record Found!'];
            return $response;

        }else{
                $response = [
                    'status' => 404,
                    'message' => 'NO Data found!'];
                return $response;
            }
    }else{
        $response = [
            'status' => 500,
            'message' => 'Something went wrong!'
        ];
        return $response;
    }
    return mysqli_query($conn, $quary);
}


//Delete record from database using id
function delete($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $quary = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $quary);

    return $result;
}


?>