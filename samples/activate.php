<?php
$text = '';
$active = $_GET["active"];

if(isset($active)){

include ('config.php');

$zapytanie = 'SELECT * FROM user WHERE active="'. $active .'"';
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc(); 

if ($wiersz['id'] == ''){
    $text = 'This link is incorrect or account has already been activated';
} else {
    $query_update = "UPDATE `user` SET `active` = 'Yes' WHERE id = ".$wiersz['id'];
    $query_result = $dblink->query($query_update);
    $text = 'Your account has been activated<br>You can go to <a href="https://www.makingnotes.ovh">home page</a> and log in';
}
}
?>

<html lang="pl-PL">
<head>
    <title>Activate</title>
    <meta charset="utf-8">
    <title>Activate account</title>
    <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
</head>

<body>
<?php echo $text; ?>
</body>
</html>