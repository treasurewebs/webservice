<?php
/* require the user as the parameter */
//if(isset($_GET['user']) && intval($_GET['user'])) {

	if(isset($_GET['country'])){$country=$_GET['country'];}else{$country="";}
	if(isset($_GET['name'])){$name=$_GET['name'];}else{$name="";}
	if(isset($_GET['email'])){$email=$_GET['email'];}else{$email="";}
	if(isset($_GET['id'])){$id=$_GET['id'];}else{$id="";}
	/* soak in the passed variable or set our own */
	$format = 'json';
	//$id = intval($_GET['uid']); //no default

	/* connect to the db */
	$link = new mysqli('localhost','root','','tuts_rest') or die('Cannot connect to the DB');

	/* grab the posts from the db */
	$query = "SELECT * FROM users WHERE country='$country' OR name='$name' OR email='$email' OR id='$id'";
	$result = mysqli_query($link,$query);

	/* create one master array of the records */
	$posts = array();
	if(mysqli_num_rows($result)) {
		while($post = mysqli_fetch_assoc($result)) {
			$posts[] = array('post'=>$post);
		}
	}

	/* output in necessary format */
	if($format == 'json') {
		header('Content-type: application/json');
		echo json_encode(array('posts'=>$posts));
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

	/* disconnect from the db */
	@mysqli_close($link);
//}
?>
