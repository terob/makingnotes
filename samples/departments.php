<?php
include ('security.php');
include ('config.php');

$zapytanie_group_name = "
 SELECT DISTINCTROW group1.name
 FROM group1
 INNER JOIN departments_group
 on departments_group.group_id = group1.group_id
 INNER JOIN department
 on departments_group.group_id = department.ID_department
 INNER JOIN user_departments
 on user_departments.departments_id = department.ID_department
 WHERE user_departments.user_id = '". $_SESSION["id"] ."';
";

$wynik_group_name = $dblink->query($zapytanie_group_name);


$text = '';

while($wiersz_group_name = $wynik_group_name->fetch_assoc()){ 
    $text = $text.'<div id="box" width="50%">
             <center><b><font size="4">'. $wiersz_group_name['name'] .'</font></b></center>
             <div id="dot"></div>
             <ul>'; 
             
    $zapytanie_department_name = "
        SELECT department.name, department.id_department
        FROM user_departments
        INNER JOIN department
        on user_departments.departments_id = department.ID_department
        INNER JOIN departments_group
        on department.ID_department = departments_group.department_id
        INNER JOIN group1
        on group1.group_id = departments_group.group_id
        WHERE group1.name = '". $wiersz_group_name['name'] ."' AND user_departments.user_id = '". $_SESSION["id"] ."'
        ";  
        
    $wynik_department_name = $dblink->query($zapytanie_department_name);        
        
    while($wiersz_department_name = $wynik_department_name->fetch_assoc()){
           
        $text = $text.'<li><a href="modules.php?department_id='. $wiersz_department_name['id_department'] .'"><font color="blue">'. $wiersz_department_name['name'] .'</font></a></li>';    
    }
    
    $text = $text.'</ul>
             </div>
             <br>';
}

?>
<html>
<head>
        <title>Departments</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
<style>
#header {
    top: 0;
    left: 0;
    right: 0;
    height: 50px; 
    background: url(./img/header.jpg);
    background-repeat: no-repeat;
}
#footer  {
    margin-top: 690px;
    height: 50px; 
    background: url(./img/footer.jpg);
    background-repeat: no-repeat;
    font-weight: bold;
    color: rgb(255, 153, 51);

}
#welcome  {
    position: absolute;
    left: 5%;
    font-weight: bold;
    color: rgb(255, 153, 51);
}
#logout  {
    position: absolute;
    right: 5%;
    font-weight: bold;
}
#dot {

  border-bottom: 1px solid #eee;
}
#box {
    width: 50%;
    margin: auto;
    background-image: url("img/box.jpg");
}

</style>
</head>
<body>
<div id="header"><br><div id="welcome">Welcome: <?php echo $_SESSION["e_mail"]; ?></div><div id="logout"><a href="logout.php"><font color="blue">Logout</font></a></div></div><br>

<?php echo $text; ?>

<center><button onclick="myFunction()">Edit departments</button></center>

<script>
    function myFunction(){
        window.location = 'edit_departments.php'
    }
</script>
<br>
<div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>            	