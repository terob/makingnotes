<?php
include ('security.php');
include ('config.php');
?>
<html>
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Language" content="pl">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
        
        <link href="./styles/monokai-sublime.css" rel="stylesheet">
        <script src="jquery.min.js"></script>
        <script src="hightlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
        <script>hljs.initHighlighting();</script>
        <script src="../ckeditor.js"></script>
        <title>Edit function</title>
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

$module_id = $_GET["module_id"];
$department_id = $_GET["department_id"];
$function_id = $_GET["function_id"];
$tryb = $_GET["tryb"];
$editor1 = $_POST["editor1"];
$nazwa2 = $_POST['nazwa2'];
$delite = $_POST['delite'];
$link = $_POST['link'];
$link_description = $_POST['link_description'];
$link_type = $_POST['link_type'];
$attachment_function_id = $_POST['attachment_function_id'];
$attachment_id = $_POST['attachment_id'];

    if($tryb != "description" && $tryb != "solution"){
        $tryb = "description";    
    }

if(isset($editor1)){
$editor1 = addslashes($editor1);
if($tryb == 'description'){
    $zapytanie = "UPDATE `function` SET `name`= '". $nazwa2 ."',`description`= '". $editor1 ."' WHERE function_id = '". $function_id ."'";  
} else {
    $zapytanie = "UPDATE `function` SET `name`= '". $nazwa2 ."',`solution`= '". $editor1 ."' WHERE function_id = '". $function_id ."'";    
}

$wynik = $dblink->query($zapytanie);


}


if(isset($module_id) && isset($department_id) && isset($function_id)){
    
    
// lista zapytan
$next = $function_id;
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

$previous = $function_id;
$array = array();
$wynik = $dblink->query($zapytanie);

while($wiersz = $wynik->fetch_assoc()){
 
$array[] = $wiersz['function_id'];
$i++;


if($wiersz['function_id'] == $function_id){
    $j = $i + 1;
    
    $i = $i - 2;
  
        if($array[$i] == ''){
            $previous = $function_id;          
        } else {
            $previous = $array[$i]; 
        }
    $i = $i + 2;
}

if($i == $j){
    $next = $wiersz['function_id']; 
}
}



// koniec listy zapytań

$zapytanie = "SELECT * FROM `function` WHERE `function_id` = '". $function_id ."'";
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();

echo '<input type="text" id="nazwa" name="nazwa" value="'. $wiersz['name'] . '">';


echo '<br>';
echo '<button onclick="left()">&larr;</button>';
echo '<button onclick="right()">&rarr;</button>';


$zapytanie_ile = "
SELECT count(*)
FROM module_function
INNER JOIN function
on function.function_id = module_function.function_id
WHERE module_function.module_id = '". $module_id ."'
";

$wynik_ile = $dblink->query($zapytanie_ile);
$wiersz_ile = $wynik_ile->fetch_assoc();
$wiersz_ile = $wiersz_ile['count(*)'];


$i = 0;
$zapytanie_lista = "
SELECT function.function_id,  function.name
FROM module_function
INNER JOIN function
on module_function.function_id = function.function_id
WHERE module_function.module_id = '". $module_id ."'
GROUP BY function.function_id
";


$wynik_lista = $dblink->query($zapytanie_lista);
while($wiersz_lista = $wynik_lista->fetch_assoc()){
    $i++;
    if($wiersz_lista['function_id'] == $function_id){
        $numer = $i;
        break;
    }
}

echo $numer.'/'.$wiersz_ile;



if($tryb == 'solution'){
$tresc = $wiersz['solution'];
$styl2 = 'style="background-color: #FFFFC0"';
$styl1 = "";
} else {
$tresc = $wiersz['description'];
$styl1 = 'style="background-color: #FFFFC0"'; 
$styl2 = "";
}

}


// ATTACHMENT

if($link != '' && $link_description != ''){

    $zapytanie_insert = "
INSERT INTO `function_atachment` (`function_id`, `atachment_id`) VALUES ('". $attachment_function_id  ."', '". $attachment_id ."');
INSERT INTO `attachment` (`attachment_id`, `link`, `description`, `type`) VALUES ('". $attachment_id ."', '". $link ."', '". $link_description ."', '". $link_type ."');
    ";
    
    if (mysqli_multi_query($dblink, $zapytanie_insert)) {
    // OK
    mysqli_close($dblink);
    $dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
    if (!$dblink)    {
        die ('brak poloczenia: '.mysqli_error());
    }

}
}

if($delite != ''){
    
    $zapytanie_delite = "
    DELETE FROM `function_atachment` WHERE `atachment_id` = '". $delite ."';
    DELETE FROM `attachment` WHERE `attachment_id`= '". $delite ."';
    ";
    
    if (mysqli_multi_query($dblink, $zapytanie_delite)) {
    // OK
    mysqli_close($dblink);
    $dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
    if (!$dblink)    {
        die ('brak poloczenia: '.mysqli_error());
    }
    }

}



$attachment = '';
$attachment_delite = '';
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

$attachment_delite = $attachment_delite.'<option value="'. $wiersz_attachment['attachment_id'] .'">'. $wiersz_attachment['description'] .'</option>';

}


$zapytanie_attachment_number = "
SELECT count(*) FROM `function_atachment` WHERE `function_id` = '". $function_id ."'
";

$wynik_attachment_number = $dblink->query($zapytanie_attachment_number);
$wiersz_attachment_number = $wynik_attachment_number->fetch_assoc();

$zapytanie_attachment_id_next = "SELECT max(atachment_id) FROM `function_atachment`";
$wynik_attachment_id_next = $dblink->query($zapytanie_attachment_id_next);
$wiersz_attachment_id_next = $wynik_attachment_id_next->fetch_assoc();
$attachment_id_next = $wiersz_attachment_id_next['max(atachment_id)'] + 1;

?>
<br>
<button onclick="tresc()" <?php echo $styl1; ?>>Description</button>
<button onclick="rozwiazanie()"<?php echo $styl2; ?>>Solution</button>
<form action="edit_function.php?module_id=<?php echo $module_id; ?>&department_id=<?php echo $department_id; ?>&function_id=<?php echo $function_id; ?>&tryb=<?php echo $tryb; ?>" method="post">

<textarea cols="80" id="editor1" name="editor1" rows="10">
<?php echo $tresc; ?>
</textarea>
<input type="hidden" id="nazwa2" name="nazwa2" value="">
<input type="submit" value="Save" onclick="myFunction()">
</form>
<center><button onclick="powrut()">Function list</button></center><br>
<center><button onclick="modules()">Modules</button></center><br>
        <script>
            // This call can be placed at any point after the
			// <textarea>, or inside a <head><script> in a
			// window.onload event handler.

			// Replace the <textarea id="editor"> with an CKEditor
			// instance, using default configurations.
CKEDITOR.replace( 'editor1',
    {
        filebrowserBrowseUrl : '/ckeditor/plugins/Filemanager-master/index.php',
        filebrowserImageBrowseUrl : '/ckeditor/plugins/Filemanager-master/index.php?exclusiveFolder=<?php echo $_SESSION["e_mail"];  ?>/',
    });


		</script>
<script>
     var module_id =    <?php echo $module_id; ?>;
     var next = <?php echo $next; ?>;
     var max = <?php echo $wiersz_ile; ?>;  
     var department_id = <?php echo $department_id; ?>; 
     var next = <?php echo $next; ?>; 
     var previous = <?php echo $previous; ?>; 
     var function_id = <?php echo $function_id; ?>;
    function left(){     
     window.location.replace("edit_function.php?module_id="+module_id+"&department_id="+department_id+"&function_id="+previous);     
        } 
    function right(){      
     window.location.replace("edit_function.php?module_id="+module_id+"&department_id="+department_id+"&function_id="+next);    
        }
    function tresc(){
    window.location.replace("edit_function.php?module_id="+module_id+"&department_id="+department_id+"&function_id="+function_id+"&tryb=description");
    }
    function rozwiazanie(){
    window.location.replace("edit_function.php?module_id="+module_id+"&department_id="+department_id+"&function_id="+function_id+"&tryb=solution");
    }
    function powrut(){
    window.location.replace("function_list.php?id_module="+module_id+"&department_id="+department_id);
    }
    function myFunction(){
    var x = document.getElementById("nazwa").value;
    document.getElementById("nazwa2").value = x;
    }
    function modules(){
    window.location.replace("modules.php?department_id="+department_id);
    }    
</script>
<center><b>ATTACHMENTS:</b></center>
<?php echo $attachment; ?>

<form action="" method="POST">
<center>Delite attachment:
<select name="delite">
<?php echo $attachment_delite; ?>
</select>
    <input type="submit" name="delite_atachment" value="OK">
</center>
</form>
<form action="" method="POST">
<b><?php echo $wiersz_attachment_number['count(*)'] + 1; ?>:</b><br>
Link: <input type="text" name="link" value=""><br>
Description: <input type="text" name="link_description" value=""><br>
Link type: <select name = "link_type">
  <option value="1">Image</option>
  <option value="2">Video</option>
  <option value="3">Directory</option>
  <option value="4">PDF</option>
  <option value="5">MP3</option>
  <option value="6">Website</option>
</select><br>
<input type="hidden" name="attachment_function_id" value="<?php echo $function_id ?>">
<input type="hidden" name="attachment_id" value="<?php echo $attachment_id_next?>">
<input type="submit" name="atachment" value="ADD">
</form>
</div>
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>	