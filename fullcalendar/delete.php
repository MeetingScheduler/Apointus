<?php
$sql = "DELETE from evenement WHERE id=".$id;
$q = $bdd->prepare($sql);
$q->execute();
?>