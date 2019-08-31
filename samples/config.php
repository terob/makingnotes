<?php
$dblink = mysqli_connect('mysql.cba.pl', 'terbis', 'yntrbvqadv', 'python_nauka_cba_pl');
if (!$dblink)	{
	die ('brak poloczenia: '.mysqli_error());
}

?>
