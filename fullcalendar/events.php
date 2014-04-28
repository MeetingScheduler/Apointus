<?php
	ob_start();
    session_start();
// List of events
 $json = array();

 // Query that retrieves events
 $requete = "SELECT * FROM evenement WHERE User_id = '$_SESSION[user_id]' ORDER BY id";

 // connection to the database
 try {
 $bdd = new PDO('mysql:host=localhost;dbname=fullcalendar', 'root', '');
 } catch(Exception $e) {
 	echo 'unable';
  exit('Unable to connect to database.');
 }
 // Execute the query
 $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

 // sending the encoded result to success page
 echo json_encode($resultat->fetchAll(PDO::FETCH_ASSOC));

?>