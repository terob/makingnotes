<html>
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
</head>
<body>
<?php

include ('config.php');
$module_id = $_POST["module_id"];
$number_function = $_POST["number_function"];
$odpowiedz = $_POST["odpowiedz"];
$department_id = $_POST["department_id"];

if(isset($module_id) && isset($number_function) && isset($department_id)){

$zapytanie = "
SELECT function.function_id,  function.name
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
GROUP BY function.function_id
";

$i = 0;
$j = -1;

$array = array();
$wynik = $dblink->query($zapytanie);
while($wiersz = $wynik->fetch_assoc()){
 
$array[] = $wiersz['function_id'];
$i++;

if($wiersz['function_id'] == $number_function){
    $j = $i + 1;
}

if($i == $j){
    $next = $wiersz['function_id'];  
}

}

$ile_elementow = count($array);

if(isset($odpowiedz)){
   $zapytanie = "UPDATE `function` SET `mark` = '". $odpowiedz ."' WHERE `function_id` = '". $number_function ."'";
   $wynik = $dblink->query($zapytanie); 
}



if(max($array) == $number_function){
    header('Location: function_list.php?id_module='. $module_id .'&department_id='. $department_id .'&force=yes');
} else {
    header('Location: function.php?module_id='. $module_id .'&department_id='. $department_id .'&function_id='. $next);
}

}

?>
</body>
</html>