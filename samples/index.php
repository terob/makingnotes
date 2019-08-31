<?php
session_start();
include ('config.php');
if ($_SESSION["id"] != ''){
    header('Location: departments.php');   
}

$e_mail = $_POST["e_mail"];
$password = $_POST["password"];

if(isset($_POST['editor1'])){
    
$zapytanie = 'SELECT * FROM user WHERE e_mail="'. $e_mail .'"';
$wynik = $dblink->query($zapytanie);
$wiersz = $wynik->fetch_assoc();
if($wiersz['password'] == md5($password) && $wiersz['active'] == 'Yes'){
   $_SESSION["id"] = $wiersz['id'];
   $_SESSION["e_mail"] = $e_mail;
     
    $zapytanie = "UPDATE `user` SET `ip` = '". $_SERVER['REMOTE_ADDR'] ."' WHERE user.id = ". $wiersz['id'] ."";
    $wynik = $dblink->query($zapytanie);
      
   
   header('Location: departments.php');
    } else {
   $text = '<font color="red">Password or e-mail incorrect</font><br>';
    }
}

?>
<!DOCTYPE html>
<html >
<head>
<link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
  <meta charset="UTF-8">
  <title>Home Page</title>
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
}
 </style> 
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
<div id="header"></div><br>
  <center>
<iframe width="1280" height="720" src="http://www.youtube.com/embed/OGGTmz6GK_4?" frameborder="0" allowfullscreen></iframe>  
  </center>
<div class="container">
	<section id="content">
		<form action="" method="POST" name="Form_Login">
			<h1>Login:</h1>
			<div>
				<input type="text" placeholder="E-mail" required="" id="username" name="e_mail" />
			</div>
			<div>
				<input type="password" placeholder="Password" required="" id="password" name="password" />
			</div>
			<div>
				<input type="submit" name="editor1" value="Log in" />
				<a href="forgot_password.php">Forgot password?</a>
				<a href="register.php">Register</a>
			</div>
		</form><!-- form -->
        <?php echo $text; ?>
	</section><!-- content -->
</div><!-- container -->
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
</body>
</html>
