<?php
	include ("dbconfig.php");

	if (isset($_POST['members'])) {
		$ring_id = $_POST['ring_id'];

		$ids = array_keys($_POST['members']);

		foreach ($ids as $id) {
			mysqli_query($con, "INSERT INTO membership(R_ID, User_id) VALUES($ring_id, $id)");

		}

	}
	header('location: profile.php');

?>