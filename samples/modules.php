<?php
include ('config.php');
include ('security.php');

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


?>
<html>
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
  <title>Modules</title>        
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
    margin-top: 590px;
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
<center><button onclick="myFunction()">Departments</button></center><br>
<center><button onclick="myFunction2()">Edit modules</button></center><br>
<center><button onclick="myFunction3()">Reset</button></center><br>
<center><h1><?php echo $wiersz['name']; ?></h1></center>
<script>
    function myFunction(){
        window.location = 'departments.php';
    }
    function myFunction2(){
        window.location = 'edit_modules.php?department_id=<?php echo $department_id ?>';
    }   
    function myFunction3(){
        window.location = 'modules.php?reset=reset&department_id=<?php echo $department_id?>';
    }  
</script>
<div id="margin">
<?php

$reset = $_GET["reset"];


// sprwdzenie czy nacinieto przycisk reset
if($reset=='reset'){
    $zapytanie = "
UPDATE function
INNER JOIN module_function
on function.function_id = module_function.function_id
INNER JOIN module
on module.id_module = module_function.module_id
INNER JOIN departments_module
on departments_module.module_id = module.id_module
SET mark='0'
WHERE departments_module.departments_id = '". $department_id ."'
    ";
    $wynik = $dblink->query($zapytanie);

}

# glowne zapytanie

$zapytanie = "
SELECT DISTINCTROW module.id_module as id_module, module.name as module_name, module.description as module_description, category.name as category_name, subcategory.name as subcategory_name
FROM departments_module
INNER JOIN module
on module.id_module = departments_module.module_id
INNER JOIN module_category
on module_category.module_id = module.id_module
INNER JOIN category
on category.category_id = module_category.category_id
INNER JOIN module_subcategory
on module_subcategory.module_id = module.id_module
INNER JOIN subcategory
on subcategory.subcategory_id = module_subcategory.subcategory_id
WHERE departments_module.departments_id = '". $department_id ."'
ORDER BY category.category_id
";

$wynik = $dblink->query('SET SQL_BIG_SELECTS=1');

$wynik = $dblink->query($zapytanie);
$kat2 = '';
$pod2 = '';

while($wiersz = $wynik->fetch_assoc())  {
    
$kat = $wiersz['category_name'];
$pod = $wiersz['subcategory_name'];

            if($kat != $kat2)
            {
                echo '<h1>'.$kat.'</h1><br>';
                $kat2 = $kat;
            }
            
            if($pod != $pod2)
            {
                echo '<b>'.$pod.'</b><br>';
                $pod2 = $pod;
            }    
    



echo "<a href='function_list.php?id_module=". $wiersz['id_module'] ."&department_id=". $department_id ."'>".$wiersz['module_name']."</a>";


$zapytanie2 = "
SELECT COUNT(*) 
FROM module_function
INNER JOIN function
on function.function_id = module_function.function_id
WHERE module_function.module_id = '". $wiersz['id_module'] ."'
";
$wynik2 = $dblink->query($zapytanie2);
$wiersz2 = $wynik2->fetch_assoc();
if ($wiersz2['COUNT(*)'] > 0){
echo ' '.$wiersz['module_description'].' ';
} else{  
echo ' <font color="red">'.$wiersz['module_description'].'</font> ';     
}

echo '<br>';
}


?>
</div>

<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>		