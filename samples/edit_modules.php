<?php

include ('config.php');
include ('security.php');

$id = $_SESSION["id"];

function delite_category_witchout_module($dblink) {  

// Get all category name    
$zapytanie1 = 'SELECT * FROM `category`';
$wynik1 = $dblink->query($zapytanie1);
while($wiersz1 = $wynik1->fetch_assoc()){


// check if category have no module
$zapytanie2 = '
SELECT category.category_id
FROM module_category
INNER JOIN category
on module_category.category_id = category.category_id
WHERE module_category.category_id = "'. $wiersz1["category_id"] .'"
';
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
// delite category
if($wiersz2['category_id'] == ""){
$zapytanie3 = 'DELETE FROM `category` WHERE `category_id` = "'. $wiersz1["category_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    
}

}

}



function delite_subcategory_witchout_module($dblink) {  

// Get all subcategory   
$zapytanie1 = 'SELECT * FROM `subcategory`';
$wynik1 = $dblink->query($zapytanie1);
while($wiersz1 = $wynik1->fetch_assoc()){


// Check if subcategory have no module
$zapytanie2 = '
SELECT subcategory.subcategory_id
FROM subcategory
INNER JOIN module_subcategory
on subcategory.subcategory_id = module_subcategory.subcategory_id
WHERE module_subcategory.subcategory_id = "'. $wiersz1["subcategory_id"] .'"
';
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
// Delite from atachment
if($wiersz2['subcategory_id'] == ""){
    
$zapytanie3 = 'DELETE FROM `subcategory` WHERE `subcategory_id` = "'. $wiersz1["subcategory_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    

}

}

}


function delite_function_witchout_module($dblink) {  

// Get all function.function_id    
$zapytanie1 = 'SELECT * FROM `function`';
$wynik1 = $dblink->query($zapytanie1);
while($wiersz1 = $wynik1->fetch_assoc()){


// Check if function have no module
$zapytanie2 = '
SELECT function.function_id
FROM function
INNER JOIN module_function
on module_function.function_id = function.function_id
WHERE module_function.function_id = "'. $wiersz1["function_id"] .'"
';
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
// Delite category
if($wiersz2['function_id'] == ""){
    
$zapytanie3 = 'DELETE FROM `function` WHERE `function_id` = "'. $wiersz1["function_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    

// Geting atachment id

$zapytanie3 = 'DELETE FROM `function_atachment` WHERE `function_id` = "'. $wiersz1["function_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    
}

}


}




function delite_atachment_witchout_function($dblink) {  

// Get all atachment   
$zapytanie1 = 'SELECT * FROM `attachment`';
$wynik1 = $dblink->query($zapytanie1);
while($wiersz1 = $wynik1->fetch_assoc()){


// Check if atachment have no function
$zapytanie2 = '
SELECT attachment.attachment_id
FROM attachment
INNER JOIN function_atachment
on attachment.attachment_id = function_atachment.atachment_id
WHERE function_atachment.atachment_id = "'. $wiersz1["attachment_id"] .'"
';
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
// Delite from atachment
if($wiersz2['attachment_id'] == ""){
    
$zapytanie3 = 'DELETE FROM `attachment` WHERE `attachment_id` = "'. $wiersz1["attachment_id"] .'"';
$wynik3 = $dblink->query($zapytanie3);    

}

}

}





$department_id = $_GET['department_id'];
$komunikat = "<font color='red' >You didn't fill all field or input variable already exists</font>";



if(isset($_POST['add_new_module'])){
$module_name = $_POST['module_name'];
$category_name = $_POST['category'];
$subcategory_name = $_POST['subcategory'];
$description = $_POST['description'];    


$this_name_dont_egzist = False;    

$zapytanie = "
SELECT *
FROM `module`
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE module.name = '". $module_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['id_module'] == ""){
    $this_name_dont_egzist = True;
}

    
    if($module_name != '' && $category_name != '' && $subcategory_name != '' && $description != '' && $this_name_dont_egzist == True){


$zapytanie = "SELECT MAX(module_id) FROM `departments_module`";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$module_id = $wiersz['MAX(module_id)'];

if ($module_id == '') 
{
    $module_id = 0;
}
$module_id++;

$zapytanie = "
SELECT category.category_id 
FROM `category`
INNER JOIN `module_category`
on module_category.category_id = category.category_id
INNER JOIN `module`
on module.id_module = module_category.module_id
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE category.name = '". $category_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$category_id = $wiersz['category_id'];

$zapytanie = "
SELECT subcategory.subcategory_id 
FROM `subcategory` 
INNER JOIN `module_subcategory`
on subcategory.subcategory_id = module_subcategory.subcategory_id
INNER JOIN `module`
on module.id_module = module_subcategory.module_id
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE subcategory.name = '". $subcategory_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$subcategory_id = $wiersz['subcategory_id'];

if($category_id == ''){
  $zapytanie_category_id = "
  INSERT INTO `category` (`category_id`, `name`) VALUES ('". $module_id ."', '". $category_name ."');
  INSERT INTO `module_category` (`module_id`, `category_id`) VALUES ('". $module_id ."', '". $module_id ."');
  ";  
} else {
    $zapytanie = "SELECT `category_id` FROM `category` WHERE `name` = '". $category_name ."'";
    $wynik = $dblink->query($zapytanie);
    $wiersz = $wynik->fetch_assoc();
  $zapytanie_category_id = "
  INSERT INTO `module_category` (`module_id`, `category_id`) VALUES ('". $module_id ."', '". $wiersz['category_id'] ."');
  ";      
    
    
}
if($subcategory_id == ''){
  $zapytanie = $zapytanie_category_id."
  INSERT INTO `module_subcategory` (`module_id`, `subcategory_id`) VALUES ('". $module_id ."', '". $module_id ."');
  INSERT INTO `subcategory` (`subcategory_id`, `name`) VALUES ('". $module_id ."', '". $subcategory_name ."');
  ";
} else {
  $zapytanie = "SELECT `subcategory_id` FROM `subcategory` WHERE `name` = '". $subcategory_name ."'";
  $wynik = $dblink->query($zapytanie);
  $wiersz = $wynik->fetch_assoc();
  $zapytanie = $zapytanie_category_id."
  INSERT INTO `module_subcategory` (`module_id`, `subcategory_id`) VALUES ('". $module_id ."', '". $wiersz['subcategory_id'] ."');
  ";         
}

$zapytanie = $zapytanie."
INSERT INTO `departments_module` (`departments_id`, `module_id`) VALUES ('". $department_id ."', '". $module_id ."');
INSERT INTO `module` (`id_module`, `name`, `description`) VALUES ('". $module_id ."', '". $module_name ."', '". $description ."');
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
    $komunikat_1 = $komunikat;     
}
}


if(isset($_POST['chanege_name_of_module'])){
      
$module = $_POST['module'];
$new_name_of_module = $_POST['new_name_of_module'];


$this_name_dont_egzist = False;    

$zapytanie = "
SELECT *
FROM `module`
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE module.name = '". $new_name_of_module ."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['name'] == ""){
    $this_name_dont_egzist = True;
}
  
    if($module != '' && $new_name_of_module != ''  && $this_name_dont_egzist == True){
  
$zapytanie = "
UPDATE `module` 
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
SET module.name = '".$new_name_of_module."' 
WHERE module.name = '".$module."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
} else {
    $komunikat_2 = $komunikat;    
}
}

if(isset($_POST['chanege_description_of_module'])){
$module = $_POST['module'];
$description = $_POST['description'];
    if($module != '' && $description != ''){
    
$zapytanie = "
UPDATE `module` 
INNER JOIN departments_module
on departments_module.module_id = module.id_module
INNER JOIN department
on department.ID_department = departments_module.departments_id
INNER JOIN user_departments
on department.ID_department = user_departments.departments_id
SET `description`= '".$description."' 
WHERE module.name = '".$module."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);   
} else {
    $komunikat_3 = $komunikat;    
}
}

if(isset($_POST['change_name_of_category'])){
$category = $_POST['category'];
$new_name_of_category = $_POST['new_name_of_category'];


$this_name_dont_egzist = False;    

$zapytanie = "
SELECT * 
FROM `category`
INNER JOIN `module_category`
on module_category.category_id = category.category_id
INNER JOIN `module`
on module.id_module = module_category.module_id
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE category.name = '". $new_name_of_category ."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if($wiersz['category_id'] == ""){
    $this_name_dont_egzist = True;
}


    if($category != '' && $new_name_of_category != '' && $this_name_dont_egzist == True){

$zapytanie = "
UPDATE `category` 
INNER JOIN `module_category`
on module_category.category_id = category.category_id
INNER JOIN `module`
on module.id_module = module_category.module_id
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
SET category.name = '".$new_name_of_category."' 
WHERE category.name = '".$category."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
} else {
    $komunikat_4 = $komunikat; 
}
}

if(isset($_POST['change_name_of_subcategory'])){
    
$subcategory = $_POST['subcategory'];
$new_name_of_subcategory = $_POST['new_name_of_subcategory'];

$this_name_dont_egzist = False;    

$zapytanie = "
SELECT *
FROM `subcategory` 
INNER JOIN `module_subcategory`
on subcategory.subcategory_id = module_subcategory.subcategory_id
INNER JOIN `module`
on module.id_module = module_subcategory.module_id
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE subcategory.name = '". $new_name_of_subcategory ."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();


if($wiersz['name'] == ""){
    $this_name_dont_egzist = True;
}


if($subcategory != '' && $new_name_of_subcategory != '' &&  $this_name_dont_egzist == True){
echo $zapytanie."<br>";
echo $this_name_dont_egzist;    

$zapytanie = "
UPDATE `subcategory`
INNER JOIN `module_subcategory`
on module_subcategory.subcategory_id = subcategory.subcategory_id
INNER JOIN `module` 
on module_subcategory.module_id = module.id_module
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
SET subcategory.name = '".$new_name_of_subcategory."' 
WHERE  subcategory.name = '".$subcategory."' AND user_departments.user_id = '". $id ."'
";

$wynik = $dblink->query($zapytanie);  
} else {
    $komunikat_5 = $komunikat;     
}
}

if(isset($_POST['connect_module_witch_category'])){

$module_name = $_POST['module'];
$category_name = $_POST['category'];

    if($module_name != '' && $category_name != ''){
$zapytanie = "
SELECT module_category.category_id, module_category.module_id
FROM `module_category` 
INNER JOIN `module`
on module_category.module_id = module.id_module
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE module.name = '". $module_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$old_category_id = $wiersz['category_id'];
$module_id = $wiersz['module_id'];

$zapytanie = "
SELECT category.category_id 
FROM `category`
INNER JOIN `module_category`
on module_category.category_id = category.category_id
INNER JOIN `module` 
on module_category.module_id = module.id_module
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE category.name = '". $category_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$new_category_id = $wiersz['category_id'];

$zapytanie = "
UPDATE `module_category` SET `category_id`= '". $new_category_id ."' WHERE `module_id` = '". $module_id ."'
";
$wynik = $dblink->query($zapytanie);

//
$zapytanie = "SELECT `category_id` FROM `module_category` WHERE `category_id` = '". $old_category_id . "'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$czy_istnieje = $wiersz['category_id'];
if($czy_istnieje == ''){
    
$zapytanie = "DELETE FROM `category` WHERE `category_id` = '". $old_category_id ."'";
$wynik = $dblink->query($zapytanie);   

}
//
} else {
    $komunikat_6 = $komunikat;     
}
}

if(isset($_POST['connect_module_witch_subcategory'])){

$module_name = $_POST['module'];
$subcategory_name =  $_POST['subcategory'];
    if($module_name != '' & $subcategory_name != ''){
$zapytanie = "
SELECT module_subcategory.subcategory_id, module.id_module
FROM `module` 
INNER JOIN `module_subcategory`
on module_subcategory.module_id = module.id_module
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE module.name = '". $module_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$old_subcategory_id = $wiersz['subcategory_id'];
$module_id = $wiersz['id_module'];

$zapytanie = "
SELECT subcategory.subcategory_id 
FROM `subcategory` 
INNER JOIN `module_subcategory`
on module_subcategory.subcategory_id = subcategory.subcategory_id
INNER JOIN `module`
on module_subcategory.module_id = module.id_module
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE subcategory.name = '". $subcategory_name ."'  AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$new_subcategory_id = $wiersz['subcategory_id'];

$zapytanie = "
UPDATE `module_subcategory` SET `subcategory_id`= '". $new_subcategory_id ."' WHERE `module_id` = '". $module_id ."'
";

$wynik = $dblink->query($zapytanie);

//
$zapytanie = "SELECT `subcategory_id` FROM `module_subcategory` WHERE `subcategory_id` = '". $old_subcategory_id . "'";

$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$czy_istnieje = $wiersz['subcategory_id'];
if($czy_istnieje == ''){
    
$zapytanie = "DELETE FROM `subcategory` WHERE `subcategory_id` = '". $old_subcategory_id ."'";
$wynik = $dblink->query($zapytanie);   

}
//	  
} else {
    $komunikat_7 = $komunikat;      
}
}

if(isset($_POST['delite_module'])){
    
$module_name = $_POST['module'];

if($module_name != ''){

$zapytanie = "
SELECT module.id_module 
FROM `module` 
INNER JOIN `departments_module`
on departments_module.module_id = module.id_module
INNER JOIN `department`
on department.ID_department = departments_module.departments_id
INNER JOIN `user_departments`
on department.ID_department = user_departments.departments_id
WHERE module.name='". $module_name ."' AND user_departments.user_id = '". $id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

$id_module = $wiersz['id_module'];

$zapytanie = "
DELETE FROM `module` WHERE `id_module` = '". $id_module ."';
DELETE FROM `module_category` WHERE `module_id` = '". $id_module ."';
DELETE FROM `module_function` WHERE `module_id` = '". $id_module ."';
DELETE FROM `module_subcategory` WHERE `subcategory_id` = '". $id_module ."';
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
    $komunikat_8 = $komunikat;     
}
}


$module_names = '';
$category_names = '';
$subcategory_names = '';

 $zapytanie = "
 SELECT module.name
 FROM departments_module
 INNER JOIN module
 on module.id_module = departments_module.module_id
 WHERE departments_module.departments_id = '". $department_id ."'
 ";
 $wynik = $dblink->query($zapytanie);
 while($wiersz = $wynik->fetch_assoc()){
     $module_names = $module_names.'<option value="'. $wiersz['name'] .'">'. $wiersz['name'] .'</option>';
 }
 
 $zapytanie = "
 SELECT DISTINCTROW category.name
 FROM departments_module
 INNER JOIN module
 on module.id_module = departments_module.module_id
 INNER JOIN module_category
 on module.id_module = module_category.module_id
 INNER JOIN category
 on category.category_id = module_category.category_id
 WHERE departments_module.departments_id = '". $department_id ."'
 ";
 $wynik = $dblink->query($zapytanie);
 while($wiersz = $wynik->fetch_assoc()){
     $category_names = $category_names.'<option value="'. $wiersz['name'] .'">'. $wiersz['name'] .'</option>';
 }
 
 $zapytanie = "
 SELECT DISTINCTROW subcategory.name
 FROM departments_module
 INNER JOIN module
 on module.id_module = departments_module.module_id
 INNER JOIN module_subcategory
 on module_subcategory.module_id = module.id_module
 INNER JOIN subcategory
 on subcategory.subcategory_id = module_subcategory.subcategory_id
 WHERE departments_module.departments_id = '". $department_id ."'
 ";
 $wynik = $dblink->query($zapytanie);
 while($wiersz = $wynik->fetch_assoc()){
     $subcategory_names = $subcategory_names.'<option value="'. $wiersz['name'] .'">'. $wiersz['name'] .'</option>';
 }


/*
delite_category_witchout_module($dblink);
delite_subcategory_witchout_module($dblink);
delite_function_witchout_module($dblink);
delite_atachment_witchout_function($dblink);
*/
?>
<html>
<head>
        <title>Edit Modules</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
<style>
#margin {
    margin-right: 50px;
    margin-left: 50px;
    line-height: 25px;
}
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
    margin-top: 5.5%;
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
<script>
    function myFunction(){
        window.location = 'modules.php?department_id=<?php echo $department_id; ?>'
    } 
</script>

<div id="box" width='50%'>
<center><b><font size="4">Add new module</font></b></center>
<div id="dot"></div>
<form action="" method="POST" name="Form_add_new_module">
Module:<input type="text" name="module_name" /><br>
Category:<input type="text" name="category" value = ""/><br>
Subcategory:<input type="text" name="subcategory" value = ""/><br>
Description:<input type="text" name="description" size="50" value=""/><br>
<input type="submit" value="OK" name="add_new_module" /> <?php echo $komunikat_1; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change name of module</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Module:
<select name="module">
<?php echo $module_names; ?>
</select>
New name:<input type="text" name="new_name_of_module" />
<input type="submit" value="OK" name="chanege_name_of_module" /> <?php echo $komunikat_2; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change description of module</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Module:
<select name="module">
<?php echo $module_names; ?>
</select>
Description:<input type="text" name="description" />
<input type="submit" value="OK" name="chanege_description_of_module" /> <?php echo $komunikat_3; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change name of category</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Category:<select name="category">
<?php echo $category_names; ?>
</select>
New name:<input type="text" name="new_name_of_category" />
<input type="submit" value="OK" name="change_name_of_category" /> <?php echo $komunikat_4; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Change name of subcategory</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Subcategory:<select name="subcategory">
<?php echo $subcategory_names; ?>
</select>
New name:<input type="text" name="new_name_of_subcategory" />
<input type="submit" value="OK" name="change_name_of_subcategory" /> <?php echo $komunikat_5; ?>
</form>
</div>
<br>


<div id="box" width='50%'> 
<center><b><font size="4">Connect module with category</font></b></center>
<div id="dot"></div>
<form action="" method="POST">
Module:
<select name="module">
<?php echo $module_names; ?>
</select>
Category:
<select name="category">
<?php echo $category_names; ?>
</select>
<input type="submit" value="OK" name="connect_module_witch_category" /> <?php echo $komunikat_6; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Connect module with subcategory</font></b></center> 
<div id="dot"></div>
<form action="" method="POST">
Module:
<select name="module">
<?php echo $module_names; ?>
</select>
Subcategory:
<select  name="subcategory">
<?php echo $subcategory_names; ?>
</select>
<input type="submit" value="OK" name="connect_module_witch_subcategory" /> <?php echo $komunikat_7; ?>
</form>
</div>
<br>

<div id="box" width='50%'>
<center><b><font size="4">Delite Module</font></b></center> 
<div id="dot"></div>
<form action="" method="POST">
Module:
<select name="module">
<?php echo $module_names; ?>
</select>
<input type="submit" value="OK" name="delite_module" /> <?php echo $komunikat_8; ?>
</form>
</div>
<br>

<center><button onclick="myFunction()">Modules</button></center><br>

<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>    