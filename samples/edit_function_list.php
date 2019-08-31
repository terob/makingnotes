<?php
include ('security.php');
include ('config.php');
$module_id = $_GET['module_id'];
$department_id = $_GET['department_id'];


$user_id = $_SESSION["id"];


$zapytanie = "
SELECT user_id 
FROM `user_departments` 
WHERE departments_id = '". $department_id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

if ($user_id != $wiersz['user_id']){
    header('Location: department.php');   
}

$function_to_delite = '';
$sql = 'SET SQL_BIG_SELECTS=1;';
$i = 0;
$tresc = '';

$zapytanie_ile = "
SELECT count(*)
FROM module_function
INNER JOIN function
on function.function_id = module_function.function_id
WHERE module_function.module_id = '". $module_id ."'
";

$wynik_ile = $dblink->query($zapytanie_ile);
$wiersz_ile = $wynik_ile->fetch_assoc();
$ile = $wiersz_ile['count(*)'];

if (isset($_POST['update'])){
while($i < $ile){
    $i++;
    $sql = $sql . "
UPDATE function 
INNER JOIN module_function
on module_function.function_id = function.function_id
INNER JOIN module
on module_function.module_id = module.id_module
INNER JOIN departments_module
on departments_module.module_id = module.id_module
INNER JOIN department
on department.ID_department = departments_module.departments_id
INNER JOIN user_departments
on user_departments.departments_id = department.ID_department
SET function.name = '". $_POST[$i] ."'
WHERE function.name = '" .$_POST[$i.'a'] ."' AND user_departments.user_id = '". $user_id ."';
    ";  
}

mysqli_multi_query($dblink, $sql);
mysqli_close($dblink);
$dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');  
}

if (isset($_POST['delite'])){

$zapytanie_delite = "
DELETE FROM `function` WHERE `function_id` = '". $_POST['delite'] ."'
";

$wynik_delite = $dblink->query($zapytanie_delite);
    
}

if (isset($_POST['add'])){


$zapytanie_next_id = "
SELECT max(function_id) FROM `function`
";

$wynik_next_id = $dblink->query($zapytanie_next_id);
$wiersz_next_id = $wynik_next_id->fetch_assoc();
$next_id = $wiersz_next_id['max(function_id)'];
$next_id++;

$zapytanie_add = "
INSERT INTO `function` (`function_id`, `name`, `description`, `solution`, `mark`) VALUES ('". $next_id ."', '". $_POST['add'] ."', '', '', '0');
INSERT INTO `module_function` (`module_id`, `function_id`) VALUES ('". $module_id ."', '". $next_id ."');
";

mysqli_multi_query($dblink, $zapytanie_add);
mysqli_close($dblink);
    
}

$dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
$zapytanie = "
SELECT *
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
GROUP BY function.function_id
";
$wynik = $dblink->query($zapytanie);

$i = 0;
while($wiersz = $wynik->fetch_assoc()){
    $i++;
$tresc = $tresc . $i .' <input type="text" name="'. $i .'" value="'. $wiersz['name'] .'"><input type="hidden" name="'. $i .'a" value="'. $wiersz['name'] .'"><br>';
$function_to_delite = $function_to_delite.'<option value="'. $wiersz['function_id'] .'">'. $wiersz['name'] .'</option>';
}
  
?>
<html>
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
        <title>Edit Function List</title>
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
    margin-top: 27.5%;  
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

<script>
     var department_id = <?php echo $department_id; ?>; 
     var module_id = <?php echo $module_id; ?>;

    function powrut(){
    window.location.replace("function_list.php?id_module="+module_id+"&department_id="+department_id+"&force=yes");
    }
    function modules(){
    window.location.replace("modules.php?department_id="+department_id);
    }    
</script>


<div id="header"><br><div id="welcome">Welcome: <?php echo $_SESSION["e_mail"]; ?></div><div id="logout"><a href="logout.php"><font color="blue">Logout</font></a></div></div><br>
<div id="margin">

Change name of function:
<form action="edit_function_list.php?module_id=<?php echo $module_id; ?>&department_id=<?php echo $department_id; ?>" method="POST">
<?php echo $tresc;?>

<input type="submit" value="Update" name="update">
</form>


<form action="" method="POST">
<center>Delite function: 
<select name="delite">
<?php echo $function_to_delite; ?>
</select>
    <input type="submit" value="OK">
</center>
</form><br>

<form action="" method="POST">
<center>Add function: 
<input type="text" name="add">
    <input type="submit" value="OK">
</center>
</form><br>

<center>
<button onclick="powrut()">Function List</button><br><br>
<button onclick="modules()">Modules</button>
</center>

</div>
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>