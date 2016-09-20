<?php

	$con = new mysqli('localhost','root','','web_service');

	if($_REQUEST['do'] == 'insert'){
		
		$name = isset($_REQUEST['name']) ? mysqli_real_escape_string($con,$_POST['name']) : "";
		$country = isset($_REQUEST['country']) ? mysqli_real_escape_string($con,$_POST['country']) : "";
		$img = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : "";
		
		$query = mysqli_query($con,"INSERT INTO data(name,country,image)VALUES('$name','$country','$img')");
		
		if($query){
			
			if(isset($_GET['path'])){$path=$_GET['path'];}else{$path="images/";}
			
			move_uploaded_file($_FILES['image']['tmp_name'],$path.$_FILES['image']['name']);
		
			$json = array("status" => 1, "msg" => "Done User added!");
		}else{
			$json = array("status" => 0, "msg" => "Error adding user!");
		}
		
		mysqli_close($con);
		
		header('Content-type: application/json');
		echo json_encode($json);
			
	}
	elseif($_REQUEST['do'] == 'view'){
		
		if(isset($_GET['format'])){$format=$_GET['format'];}else{$format="json";}
		
		if(isset($_GET['country'])){$country=$_GET['country'];}else{$country="";}
		if(isset($_GET['id'])){$id=$_GET['id'];}else{$id="";}
		if(isset($_GET['name'])){$name=$_GET['name'];}else{$name="";}
		
		$query = mysqli_query($con,"SELECT * FROM data WHERE country='$country' OR id='$id' OR name='$name'");
		
		$posts = array();
		if(mysqli_num_rows($query)) {
			while($post = mysqli_fetch_assoc($query)) {
				$posts[] = array('post'=>$post);
			}
		}
		
		
		if($format == 'json') {
			header('Content-type: application/json');
			echo json_encode(array('Data'=>$posts));
		}
		else {
			header('Content-type: text/xml');
			echo '<Data>';
			foreach($posts as $index => $post) {
				if(is_array($post)) {
					foreach($post as $key => $value) {
						echo '<',$key,'>';
						if(is_array($value)) {
							foreach($value as $tag => $val) {
								echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
							}
						}
						echo '</',$key,'>';
					}
				}
			}
			echo '</Data>';
		}
		
	}
	elseif($_REQUEST['do'] == 'update'){
		
		if(isset($_GET['id'])){$id=$_GET['id'];}else{$id="";}
		if(isset($_GET['path'])){$path=$_GET['path'];}else{$path="images/";}
		
		if($_REQUEST['do']){
			
			$query_tmp = mysqli_query($con,"SELECT * FROM data WHERE id='$id'") or die(mysqli_error($con));
			
			if(mysqli_num_rows($query_tmp)) {
				while($data = mysqli_fetch_assoc($query_tmp)) {
					
					$pre_name = $data['name'];
					$pre_country = $data['country'];
					$pre_img = $data['image'];
					$file = $path.$data['image'];
				}
			}
			
			$name = isset($_REQUEST['name']) ? mysqli_real_escape_string($con,$_POST['name']) : $pre_name;
			$country = isset($_REQUEST['country']) ? mysqli_real_escape_string($con,$_POST['country']) : $pre_country;
			$img = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : $pre_img;
			
				if($_FILES['image']['name']){
					unlink($file);
					move_uploaded_file($_FILES['image']['tmp_name'],$path.$_FILES['image']['name']);
				}
				
			$query = mysqli_query($con,"UPDATE data SET name='$name', country='$country', image='$img' WHERE id='$id'");	
			
		}
		
	}
	elseif($_REQUEST['do'] == 'delete'){
		
		if(isset($_GET['id'])){$id=$_GET['id'];}else{$id="";}
		
		if($_REQUEST['do']){
			
			if(isset($_GET['path'])){$path=$_GET['path'];}else{$path="images/";}
			
			$query_tmp = mysqli_query($con,"SELECT * FROM data WHERE id='$id'") or die(mysqli_error($con));
			
			if(mysqli_num_rows($query_tmp)) {
				while($data = mysqli_fetch_assoc($query_tmp)) {
					$file = $path.$data['image'];
					unlink($file);
					$query = mysqli_query($con,"DELETE FROM data WHERE id='$id'");
					echo "Record Successfully Deleted...!";
				}
			}
		}
		
	}
?>
