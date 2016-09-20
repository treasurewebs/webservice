<?php

// Include confi.php
include_once('confi.php');

if($_SERVER['REQUEST_METHOD'] == "POST"){
	// Get data
	$name = isset($_POST['name']) ? mysqli_real_escape_string($con,$_POST['name']) : "";
	//	$img = isset($_POST['img']) ? mysqli_real_escape_string($con,$_POST['img']) : "";
	
	$img = $_FILES['image']['name'];

	// Insert data into data base
	$sql = "INSERT INTO image(name,image)VALUES('$name','$img')";
	
	$qur = mysqli_query($con,$sql);
	
	if($qur){
		
		$folder="images/";
		move_uploaded_file($_FILES['image']['tmp_name'],$folder.$_FILES['image']['name']);
		
		$json = array("status" => 1, "msg" => "Done User added!");
	}else{
		$json = array("status" => 0, "msg" => "Error adding user!");
	}
}else{
	$json = array("status" => 0, "msg" => "Request method not accepted");
}

mysqli_close($con);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
