<?php

 session_start();
 if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();

if(isset($_POST['felvesz']))
{	
	if ( ($_POST[regi]!=$_SESSION["userjsz"]) )	{	echo "Az r�gi jelsz� helytelen! (HIBA)";	}
	else 	{
	
		if ( ($_POST[uj1]!=$_POST[uj2]) )	{		echo "Az �j jelsz� k�t beg�pel�se nem azonos! (HIBA)";	}
		else 	{
			$query="update a_felhasznalok set fel_jelszo='".$_POST[uj1]."' where fel_id=".$_SESSION["userid"];
			mysql_query($query) or die ($query);	}
	}
}


$szin='#669999';

echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<form action='' name='felvesz' method='post'>";
echo "<table border='3' bgcolor='".$szin."' align='center'><tr bgcolor='#D8D8D8'><th colspan='3'>Jelsz� m�dos�t�s</th></tr>";

echo "<tr><td>R�gi jelsz�:</td><td><input type='password' name='regi' size='20' maxlength='250' value=''></td>";
echo "<td rowspan='2'><input type='submit' name='felvesz' value='M�dos�t�s'></td></tr>";
echo "<tr><td>�j jelsz�:</td><td><input type='password' name='uj1' size='20' maxlength='250' value=''></td></tr>";
echo "<tr><td>�j jelsz� ism�t:</td><td><input type='password' name='uj2' size='20' maxlength='250' value=''></td></tr>";
echo "</table>";
echo "</form>";

echo "</HTML>";
?>