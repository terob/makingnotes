<?php
include ('config.php');
session_start();

$zapytanie = "UPDATE `user` SET `ip` = '' WHERE user.id = ". $wiersz['id'] ."";
$wynik = $dblink->query($zapytanie);

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

header('Location: index.php');
?>