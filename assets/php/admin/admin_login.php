<?php
if(isset($_POST["gomb"]))
{
	require("../a_kapcs.inc.cS7.php");
	dbkapcs();
	$query="select * from a_felhasznalok where 
				fel_nev='$_POST[neve]' && 
				fel_jelszo='$_POST[jelszo]'";
				/*md5('$_POST[jelszo]')*/
	$eredm=mysql_query($query);
	if(mysql_num_rows($eredm)==0)
	{
		echo "Hibás bejelentkezés!";
	}
	else
	{
		$sor=mysql_fetch_array($eredm);
		session_start();
		$_SESSION["userid"]=$sor["fel_id"];
		$_SESSION["userjsz"]=$sor["fel_jelszo"];
		$_SESSION["nev"]=$sor["fel_nev"];
		$_SESSION["jog"]=$sor["fel_jog"];

		$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='egy'";
		mysql_query($query)  or die ("A törlés sikertelen!". $query);		

		header("location: admin.php");
	}
}

$szin='#669999';

echo "<HTML><HEAD class='frame_kozep'><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body>";
echo "<form action='' name='auth' method='post'><table border='3' bgcolor='".$szin."' align='center'>";
echo "<tr><td>Felhasználói név:</td><td><input type='text' name='neve' size='15' maxlength='15'></td></tr>";
echo "<tr><td>Jelszó:</td><td><input type='password' name='jelszo' size='10' maxlength='10'></td></tr>";
echo "<tr><td colspan='2'><input type='submit' name='gomb' value='Bejelentkezés'></td></tr>";
echo "</table></form>";
echo "</body></HTML>";
?>
