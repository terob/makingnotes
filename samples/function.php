<?php
include ('security.php');
include ('config.php');
?>
<html>
<head>
        <title>Function</title>
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
    margin-top: 35.5%;  
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
$function_id = $_GET['function_id'];
$department_id = $_GET['department_id'];
$module_id = $_GET['module_id'];
$i = 0;

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


if(isset($function_id) && isset($department_id)){


$zapytanie = "
SELECT function.function_id,  function.name
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
GROUP BY function.function_id
";


$wynik = $dblink->query($zapytanie);
while($wiersz = $wynik->fetch_assoc()){
    $i++;
    if($wiersz['function_id'] == $function_id){
        $numer = $i;
        break;
    }
}

    
$zapytanie = 'SELECT * FROM `function` WHERE function_id='.$function_id;
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
$tresc = $wiersz['description'];

$zapytanie_ile = "
SELECT count(*)
FROM module_function
INNER JOIN function
on function.function_id = module_function.function_id
WHERE module_function.module_id = '". $module_id ."'
";
$wynik_ile = $dblink->query($zapytanie_ile);
$wiersz_ile = $wynik_ile->fetch_assoc();

}

echo $numer.'/'. $wiersz_ile['count(*)'] .' ';
echo '<button onclick="function1()">Modules</button> ';
echo '<button onclick="function2()">Function list</button> ';
echo '<button onclick="function3()">Edit function</button><br>';

    $solution_empty = 'no';
if ($wiersz['solution'] != ''){
    $solution = '
<textarea rows="10" cols="80" spellcheck=false>
</textarea>

<br>
<button onclick="show()">Show solution</button>  
<div id="rozwiazanie">'. $wiersz['solution'] .'</div>
    ';
} else {
    $solution_empty = 'yes';
}

// ATTACHMENTS

$attachment = '';
$zapytanie_attachment = "
SELECT * 
FROM function_atachment
INNER JOIN attachment
on function_atachment.atachment_id = attachment.attachment_id
WHERE function_atachment.function_id = '". $function_id ."'
GROUP BY attachment.attachment_id
";

$wynik_attachment = $dblink->query($zapytanie_attachment);

while ($wiersz_attachment = $wynik_attachment->fetch_assoc()){
    
$attachment = $attachment.'<a href="'. $wiersz_attachment['link'] .'" target="_blank"><img src="./img/'. $wiersz_attachment['type'] .'.png" width="30" height="30"></a> '. $wiersz_attachment['description'] .'<br>';

}
if($attachment != ''){
    $attachment = '<b>Attachments:</b><br>'.$attachment;
}

?>

<?php echo $tresc;?>


<br>

<?php echo $solution;?>


<script type="text/javascript">
        document.getElementById('rozwiazanie').hidden = true;
        
        function show(){
        if(document.getElementById('rozwiazanie').hidden == false) 
        { 
            document.getElementById('rozwiazanie').hidden = true 
        } else
        {
         document.getElementById('rozwiazanie').hidden = false 
        }
        }
        
 
        function function1(){
            window.location = 'modules.php?department_id=<?php echo $department_id; ?>';
        }
        
        function function2(){
            window.location = 'function_list.php?id_module=<?php echo $module_id; ?>&department_id=<?php echo $department_id; ?>&force=yes'
        }        

        function function3(){
            window.location = 'edit_function.php?module_id=<?php echo $module_id; ?>&department_id=<?php echo $department_id; ?>&function_id=<?php echo $function_id; ?>';      
        }   
</script>

<br>

<form action="mark.php" method="POST">
<?php 
if($solution_empty == 'no'){
echo('<input type="radio" name="odpowiedz" value="1" checked> Correct answer<br>');
echo('<input type="radio" name="odpowiedz" value="2"> Incorrect answer<br>'); 
}
?>
  <input type="hidden" name="number_function" value="<?php echo $function_id; ?>">
  <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
  <input type="hidden" name="department_id" value="<?php echo $department_id; ?>">
  <input type="submit" value="Next">
</form>
<?php echo $attachment;?>
</div>
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>

