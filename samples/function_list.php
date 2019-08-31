<?php
include ('security.php');
include ('config.php');
?>
<html>
<head>
        <title>Function List</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">


        <link href="./styles/monokai-sublime.css" rel="stylesheet">
        <script src="jquery.min.js"></script>
        <script src="hightlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>

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
    margin-top: 32.5%;  
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
<div id="margin">
<?php

$module_id = $_GET["id_module"];
$department_id = $_GET["department_id"];
$force = $_GET["force"];

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


// Czy pokazać function_list.php
$zapytanie = "
SELECT count(*) 
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$ile = $wiersz['count(*)'];

if($ile == '1' && $force != 'yes'){
  $zapytanie = "
    SELECT function.function_id 
    FROM module_function
    INNER JOIN function
    on module_function.function_id = function.function_id
    WHERE module_function.module_id = '". $module_id ."'
    ";
    $wynik = $dblink->query($zapytanie);
    $wiersz = $wynik->fetch_assoc();
    header('Location: function.php?function_id='.$wiersz['function_id'].'&department_id='.$department_id.'&module_id='.$module_id);
}


$zapytanie = "
SELECT * 
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
GROUP BY function.function_id
";
$wynik = $dblink->query($zapytanie);

$i = 1;
$tresc = '';
while($wiersz = $wynik->fetch_assoc()){
    echo $i.' ';
    
    if($wiersz['mark'] == 0)
    {
      $obrazek = '<img src="./img/question.png" width="25px" height="25px">';  
    }
    if($wiersz['mark'] == 1)
    {
      $obrazek = '<img src="./img/correct.png" width="25px" height="25px">';  
    }
    if($wiersz['mark'] == 2)
    {
      $obrazek = '<img src="./img/incorrect.png" width="25px" height="25px">';  
    }
    
    echo '<a href="function.php?function_id='.$wiersz['function_id'].'&department_id='.$department_id.'&module_id='.$module_id.'">'. $wiersz['name'] .'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $obrazek .' <br>';
    
    //
    
$attachment = '';  
$zapytanie_attachment = "
SELECT * 
FROM function_atachment
INNER JOIN attachment
on function_atachment.atachment_id = attachment.attachment_id
WHERE function_atachment.function_id = '". $wiersz['function_id'] ."'
GROUP BY attachment.attachment_id
";

$wynik_attachment = $dblink->query($zapytanie_attachment);

while ($wiersz_attachment = $wynik_attachment->fetch_assoc()){
    
$attachment = $attachment.'<a href="'. $wiersz_attachment['link'] .'" target="_blank"><img src="./img/'. $wiersz_attachment['type'] .'.png" width="30" height="30"></a> '. $wiersz_attachment['description'] .'<br>';

}

if($attachment != ''){
    $attachment = '<b>Attachments:</b><br>'.$attachment;
}
    
    //
    $tresc = $tresc.'<font size="3" color="red">'.$i.': '.$wiersz['name'].'</font><br><b>Description:</b><br>'.$wiersz['description'].'<b>Solution:</b><br>'.$wiersz['solution'].$attachment;
    $i++;
    $attachment = '';
}
?>
<br>
<center><button onclick="myFunction()">Modules</button></center><br>
<center><button onclick="myFunction2()">Edit function list</button></center><br>
<center><button onclick="show()">Show all function</button></center><br>
<div id="rozwiazanie"><?php echo $tresc; ?></div>
<script>
    document.getElementById('rozwiazanie').hidden = true;
    
    function myFunction(){
        window.location = 'modules.php?department_id=<?php echo $department_id; ?>';
    }
    function myFunction2(){
        window.location = 'edit_function_list.php?module_id=<?php echo $module_id; ?>&department_id=<?php echo $department_id; ?>';
    }   
        
        function show(){
        if(document.getElementById('rozwiazanie').hidden == false) 
        { 
            document.getElementById('rozwiazanie').hidden = true 
        } else
        {
         document.getElementById('rozwiazanie').hidden = false 
        }
        }
</script>
</div>
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center>
</div>
</body>
</html>