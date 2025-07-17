<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header("Location: index.php");
    exit;
}
//!!!!!!!!!!!!!!!!!!POUR LE SERVEUR!!!!!!!!!!!!!!!!!!!!!!!!



	  $con = mysqli_connect("localhost","2229993","ookahzucheehahp");
	  mysqli_select_db($con,"casarm_stages2024");

?>
<?php
/*

//!!!!!!!!!!!!!!!!!!!!!!!!!POUR XAMPP!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



	// Establish connection with the database
	$con = mysqli_connect("localhost", "root", "", "employes");

	// Check if the connection is successful
	if (mysqli_connect_errno()) {
		// Connection failed, display the error
		die("Connection failed: " . mysqli_connect_error());
	}

*/
?>