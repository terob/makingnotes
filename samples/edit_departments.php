<?php
include ('security.php');
include ('config.php');

function delite_group_witchout_department($dblink) {  
// Get all group name    
$zapytanie1 = 'SELECT * FROM `group1` ';
$wynik1 = $dblink->query($zapytanie1);
while($wiersz1 = $wynik1->fetch_assoc()){


// check if group have no department
$zapytanie2 = '
SELECT departments_group.department_id
FROM departments_group
INNER JOIN group1
on departments_group.group_id = group1.group_id
WHERE group1.group_id = "'. $wiersz1["group_id"] .'"
';
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
// delite group
if($wiersz2['department_id'] == ""){
$zapytanie3 = 'DELETE FROM `group1` WHERE `group_id` = "'. $wiersz1["group_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    
}

}
}

$id =  $_SESSION["id"];
$department_select = '';
$group_select = '';
$komunikat = "<font color='red' >You didn't fill all field or input variable already exists</font>";


if(isset($_POST['submit_new_department'])){
$this_name_dont_egzist = False;    
$new_department = $_POST['new_department'];

$zapytanie = "
SELECT * 
FROM department
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
WHERE department.name = '". $new_department ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['name'] == ""){
    $this_name_dont_egzist = True;
}



    if($new_department != '' && $this_name_dont_egzist == True){

$zapytanie = "SELECT MAX(ID_department) FROM `department`";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$last = $wiersz['MAX(ID_department)'];



$zapytanie = "SELECT MAX(ID_department) FROM `department`";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$last = $wiersz['MAX(ID_department)'];




if ($last == '') 
{
    $last = 0;
}

    $last++;

$zapytanie = "INSERT INTO `user_departments` (`user_id`, `departments_id`) VALUES ('" .$id. "', '" .$last. "');";
$zapytanie = $zapytanie."INSERT INTO `department` (`ID_department`, `name`) VALUES ('".$last."', '".$new_department."');";
$zapytanie = $zapytanie."INSERT INTO `departments_group` (`department_id`, `group_id`) VALUES ('".$last."', '".$last."');";
$zapytanie = $zapytanie."INSERT INTO `group1` (`group_id`, `name`) VALUES ('".$last."', '".$new_department."');";

if (mysqli_multi_query($dblink, $zapytanie)) {
    // OK
    mysqli_close($dblink);
    $dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
    if (!$dblink)    {
        die ('brak poloczenia: '.mysqli_error());
    }
} else {
    echo $zapytanie.'<br>';
}
} else {
    $komunikat_1 = $komunikat;
}
}

if(isset($_POST['submit_delite_department'])){

$delite_department = $_POST['delite_department'];
    if($delite_department != ''){

$zapytanie = "
SELECT id_department
FROM department
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
WHERE department.name= '". $delite_department ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$id_department = $wiersz['id_department'];

$zapytanie = "
DELETE FROM `user_departments` WHERE `departments_id`='". $id_department ."';
DELETE FROM `department` WHERE `ID_department`='". $id_department ."';
DELETE FROM `departments_group` WHERE `department_id`='". $id_department ."';
DELETE FROM `departments_module` WHERE `departments_id` = '". $id_department ."';
";

if (mysqli_multi_query($dblink, $zapytanie)) {
    // OK
    mysqli_close($dblink);
    $dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
    if (!$dblink)    {
        die ('brak poloczenia: '.mysqli_error());
    }
} else {
    echo $zapytanie.'<br>';
}
} else {
    $komunikat_2 = $komunikat;    
}
}

if(isset($_POST['submit_chanege_name_of_department'])){

$department = $_POST['department'];
$chanege_name_of_department = $_POST['chanege_name_of_department'];

$this_name_dont_egzist = False;    

$zapytanie = "
SELECT * 
FROM department
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
WHERE department.name = '". $chanege_name_of_department ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['name'] == ""){
    $this_name_dont_egzist = True;
}


if($department != '' && $chanege_name_of_department != '' && $this_name_dont_egzist == True){

$zapytanie = "
UPDATE `department`
INNER JOIN `user_departments`
on user_departments.departments_id = department.ID_department
SET department.name= '".$chanege_name_of_department."' 
WHERE department.name = '".$department."' AND user_departments.user_id = '".$id."'
";




if (mysqli_query($dblink, $zapytanie)) {
    // OK
} else {
    echo $zapytanie.'<br>';
}
} else {
    $komunikat_3 = $komunikat;     
}
}

if(isset($_POST['submit_chanege_name_of_group'])){

$group = $_POST['group'];
$chanege_name_of_group = $_POST['chanege_name_of_group'];



$this_name_dont_egzist = False;    

$zapytanie = "
SELECT *
FROM `group1` 
INNER JOIN departments_group
on departments_group.group_id = group1.group_id
INNER JOIN department
on department.ID_department = departments_group.department_id
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
WHERE group1.name = '".$chanege_name_of_group."' AND user_departments.user_id = '".$id."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['name'] == ""){
    $this_name_dont_egzist = True;
}



if($group != '' && $chanege_name_of_group != '' && $this_name_dont_egzist == True){

$zapytanie = "
UPDATE `group1` 
INNER JOIN `departments_group`
on departments_group.group_id = group1.group_id
INNER JOIN `department`
on department.ID_department = departments_group.department_id
INNER JOIN `user_departments`
on user_departments.departments_id = department.ID_department
SET group1.name = '".$chanege_name_of_group."'
WHERE group1.name = '".$group."' AND user_departments.user_id = '".$id."'
";

if (mysqli_query($dblink, $zapytanie)) {
    // OK
} else {
    echo $zapytanie.'<br>';
}
} else {
    $komunikat_4 = $komunikat;       
}
}

if(isset($_POST['submit_put_department_to_group'])){
$department = $_POST['department'];
$group = $_POST['group'];

if($department != '' && $group != ''){

$zapytanie = "
SELECT ID_department 
FROM `department` 
INNER JOIN `user_departments`
on user_departments.departments_id = department.ID_department
WHERE department.name='". $department ."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$department_id = $wiersz['ID_department'];


$zapytanie = "
SELECT departments_group.group_id
FROM department 
INNER JOIN departments_group 
on department.ID_department = departments_group.department_id
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
WHERE department.name = '". $department ."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$group_id_stare = $wiersz['group_id'];


$zapytanie = "
SELECT group1.group_id 
FROM `group1` 
INNER JOIN `departments_group`
on departments_group.group_id = group1.group_id
INNER JOIN `department`
on department.ID_department = departments_group.department_id
INNER JOIN `user_departments`
on user_departments.departments_id = department.ID_department
WHERE group1.name ='". $group ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$group_id_nowe = $wiersz['group_id'];

$zapytanie = "UPDATE `departments_group` SET `group_id`= '". $group_id_nowe ."' WHERE `department_id`= '". $department_id ."'";

$wynik = $dblink->query($zapytanie);

$zapytanie = "SELECT `group_id` FROM `group1` WHERE `group_id` = '". $group_id_stare ."'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$czy_istnieje = $wiersz['group_id'];
if($czy_istnieje == ''){
    
$zapytanie = "DELETE FROM `group1` WHERE `group_id` = '". $group_id_stare ."'";
$wynik = $dblink->query($zapytanie);   

}
} else {
     $komunikat_5 = $komunikat;   
}
}


if(isset($_POST['submit_default_name_of_group'])){
 
$department = $_POST['department'];

if($department != ''){
// Get department_id
$zapytanie = " 
SELECT department.ID_department
FROM `user_departments`
INNER JOIN `department`
on department.ID_department = user_departments.departments_id
WHERE department.name='". $department ."' AND user_departments.user_id = '". $id ."'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$department_id = $wiersz['ID_department'];


$zapytanie = "SELECT departments_group.group_id
FROM `department` 
INNER JOIN `departments_group`
on department.ID_department = departments_group.department_id
INNER JOIN `user_departments`
on user_departments.departments_id = department.ID_department
WHERE department.name = '". $department ."' AND user_departments.user_id = '". $id ."'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$group_id = $wiersz['group_id'];

// Sprawdzanie czy group_id.name jest takie samo jak department

$zapytanie = "SELECT name
FROM `group1` 
WHERE group1.group_id = '". $group_id ."'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$group1_name = $wiersz['name'];

if($group1_name != $department){

$zapytanie = "SELECT max(group_id) FROM `group1`";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$group_id_next = $wiersz['max(group_id)'] + 1;    
    
// inser to group i department_group
$zapytanie = 'INSERT INTO `group1` (`group_id`, `name`) VALUES ("'. $group_id_next .'", "'. $department.'");';
$wynik = $dblink->query($zapytanie);   

$zapytanie = 'UPDATE `departments_group` SET `group_id`= "'. $group_id_next .'" WHERE `department_id` = "'. $department_id .'"';
$wynik = $dblink->query($zapytanie);  

}


if (mysqli_query($dblink, $zapytanie)) {
    // OK
} else {
    echo $zapytanie.'<br>';
}
} else {
    $komunikat_6 = $komunikat;     
}
}




$zapytanie = "SELECT department.name
 FROM `department`
 INNER JOIN `user_departments`
 on user_departments.departments_id=department.id_department
 WHERE user_departments.user_id = '". $id ."'";
 
$wynik = $dblink->query($zapytanie);

while($wiersz = $wynik->fetch_assoc())  {
$department_select = $department_select.'<option value="'. $wiersz['name'] .'">'. $wiersz['name'] .'</option>';
}  

$zapytanie = "SELECT DISTINCTROW group1.name
 FROM `group1`
 INNER JOIN departments_group
 on departments_group.group_id = group1.group_id
 INNER JOIN `department`
 on departments_group.group_id = department.ID_department
 INNER JOIN `user_departments`
 on user_departments.departments_id = department.ID_department
 WHERE user_departments.user_id = '". $id ."'";
 
$wynik = $dblink->query($zapytanie);

while($wiersz = $wynik->fetch_assoc())  {
$group_select = $group_select.'<option value="'. $wiersz['name'] .'">'. $wiersz['name'] .'</option>';
}  



delite_group_witchout_department($dblink);

?>
<html>
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
        <title>Edit departments</title>
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
    top: 0;
    left: 0;
    right: 0;
    height: 50px; 
    background: url(./img/footer.jpg);
    background-repeat: no-repeat;
    font-weight: bold;
    color: rgb(255, 153, 51);
    margin-top: 340px;    
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

<div id="box" width='50%'>
<center><b><font size="4">Add new department</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Name:<input type="text" name="new_department" />
<input type="submit" value="OK" name="submit_new_department" /> <?php echo $komunikat_1; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Delite department</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Select department to delite:
<select name="delite_department">
<?php echo $department_select; ?>
</select>
<input type="submit" value="OK" name="submit_delite_department" /> <?php echo $komunikat_2; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change name of department</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Department:
<select name="department">
<?php echo $department_select; ?>
</select>
New name:<input type="text" name="chanege_name_of_department" />
<input type="submit" value="OK" name="submit_chanege_name_of_department" /> <?php echo $komunikat_3; ?>
</form>
</div>
<br>


<div id="box" width='50%'>
<center><b><font size="4">Change name of group</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Group:
<select name="group">
<?php echo $group_select; ?>
</select>
New name:<input type="text" name="chanege_name_of_group" />
<input type="submit" value="OK" name="submit_chanege_name_of_group" /> <?php echo $komunikat_4; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Put department to group</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Department:
<select name = "department">
<?php echo $department_select; ?>
</select>
Group:
<select name = "group">
<?php echo $group_select; ?>
</select>
<input type="submit" value="OK" name="submit_put_department_to_group" /> <?php echo $komunikat_5; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change name of department group to default one</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Department:
<select name = "department">
<?php echo $department_select; ?>
</select>
<input type="submit" value="OK" name="submit_default_name_of_group" /> <?php echo $komunikat_6; ?>
</form>
</div>
<br>

<center><button onclick="myFunction()">Departments</button></center>

<script>
    function myFunction(){
        window.location = 'departments.php'
    }
</script>
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>    		