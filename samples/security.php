<?php
$ip = $_SERVER['REMOTE_ADDR'];
include('config.php');
session_start();

$zapytanie = "SELECT ip FROM `user` WHERE id = '". $_SESSION["id"] ."'";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if ($_SESSION["id"] == '' && $wiersz['ip'] != $ip){
    header('Location: index.php');   
}

?>