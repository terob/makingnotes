<?php
include ('config.php');

$text = '';

$e_mail = $_POST["e_mail"];
if ($e_mail != ''){
    $zapytanie = 'SELECT * FROM user WHERE e_mail="'. $e_mail .'"';
    $wynik = $dblink->query($zapytanie);
    $wiersz = $wynik->fetch_assoc();
    if ($wiersz['id'] != ''){
        
        $password = '';
        $words = preg_split('//', 'abcghimnostuyz0456', -1);
        shuffle($words);
        foreach($words as $word) {
            $password = $password.$word;
        }
        
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <administrator@simon.otreba.co.uk>' . "\r\n";
        $to      = $e_mail;
        $subject = 'New password';
        $message = '
          <html>
          <head>
          </head>
          <body>
          <p>Your new password is '. $password .'</p>
          <p>Best regards</p>
          <p>Simon Otreba</p>
          </body>
          </html>
          ';
          
        mail($to, $subject, $message, $headers);
        
        $text = "A new password has been sent to your e-mail address";
        
        $zapytanie_zmien = "UPDATE `user` SET `password` = '" .md5($password). "' WHERE `e_mail` = '".$e_mail."'";
        $wynik_zmien = $dblink->query($zapytanie_zmien);
        
    } else {
      $text = "This e-mail don't exists";  
    }
} 

?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Forgot password</title>
  <link rel="icon" href="http://www.iconeasy.com/icon/ico/Hardware/Summer%20Collection/Web.ico">
  <link rel="stylesheet" href="css/style.css">
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
    margin-top: 415px;
    height: 50px; 
    background: url(./img/footer.jpg);
    background-repeat: no-repeat;
    font-weight: bold;
    color: rgb(255, 153, 51);
}
</style> 
</head>

  <body>
<div id="header"></div><br>
<div class="container">
    <section id="content">
		<form action="" method="POST">
			<h1>Recover:</h1>
			<div>
				<input type="text" placeholder="E-mail" required="" id="username" name="e_mail"/>
			</div>
			<div>
				<input type="submit" value="Recover" />
			</div>
		</form><!-- form -->
<font color="red"><?php echo $text; ?></font><br>
	</section><!-- content -->
</div><!-- container -->
<br><div id="footer"><br><center>Copyright &#9400; 2017 Simon Otreba</center></div>
  
    <script src="js/index.js"></script>

</body>
</html>