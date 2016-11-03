<?php 

	require_once(__DIR__.'/variables.php');

	/* Setting a database connection */
	$con = mysqli_connect(DB_HOST ,DB_USER, DB_PASS, DB_NAME);

	if ($con->connect_error) {
  		// echo "Error";
  	} else {
  		// echo "Success";
  	}

  	/* END == Setting a database connection */
?>