<?php
// echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='assets/css/main.css' /></HEAD>";
echo "<HTML><HEAD>";
echo "<meta charset='utf-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link rel='icon' href='assets/img/favicon.ico' type='image/ico' sizes='16x16'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/bootstrap.min.css'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/bootstrap-theme.min.css'>";
echo "<link type='text/css' rel='stylesheet' media='all' href='assets/css/main.css'>";
echo "<script src='assets/js/jquery-3.1.1.min.js'></script>";
echo "<script src='assets/js/bootstrap.min.js'></script>";
echo "<script src='assets/js/api.js'></script>";
echo "</HEAD>";



echo "<body>";
require("a_kapcs.inc.cS7.php");
dbkapcs();

echo "<div id='aktualis' class='tab-pane fade'>";
echo "<h3>Aktuális programjaink</h3>";

//echo "<table><tr>";
//echo "<td>";
	$query="select hkep_id, hkep_link from a_hirdetes_kepek where DATEDIFF(current_date,hkep_ervenyes)<=0 and coalesce(hkep_kezd,current_date)>=current_date and hkep_del=0 and hkep_tip_id in (1045,1046) and substr(hkep_megjelenik,2,1)='I' order by hkep_ervenyes "; 
	$eredm=mysql_query($query);  
	$rekordok = mysql_num_rows($eredm);
	if ($rekordok==0)
		{echo "<p>Jelenleg nincs aktív hirdetés...</p>";}
	else
	{	
		echo "<p>Nagyításhoz, kérem kattintson a képre!</p><br>";
		while($sor=mysql_fetch_array($eredm)) 
		{   echo "<p></p>";
			echo "<img onclick='megnyit_".$sor["hkep_id"]."()' style='cursor:pointer; display: block; margin-left: auto; margin-right: auto;' alt='' src='".$sor["hkep_link"]."' width=160 /><br>";
		 	echo "<script>function megnyit_".$sor["hkep_id"]."() {window.open('".$sor["hkep_link"]."','','width=800, height=500');}</script>"; 
		}
	}

echo "<h4>Korábbi programjaink:</h4>";

	$query="select hkep_id, hkep_link from a_hirdetes_kepek where DATEDIFF(current_date,hkep_ervenyes)>0 and coalesce(hkep_kezd,current_date)>=current_date and hkep_del=0 and hkep_tip_id in (1045,1046) and substr(hkep_megjelenik,2,1)='I' order by hkep_ervenyes desc"; 
	$eredm=mysql_query($query);  
	$rekordok = mysql_num_rows($eredm);
	if ($rekordok==0)
		{echo "<p>Nincs korábbi megjeleíthetõ program...</p>";}
	else
	{
		while($sor=mysql_fetch_array($eredm)) 
		{   echo "<p></p>";
			echo "<img onclick='megnyit_".$sor["hkep_id"]."()' style='cursor:pointer; display: block; margin-left: auto; margin-right: auto;' alt='' src='".$sor["hkep_link"]."' width=160 /><br>";
		 	echo "<script>function megnyit_".$sor["hkep_id"]."() {window.open('".$sor["hkep_link"]."','','width=800, height=500');}</script>"; 
		}
	}


//echo "</td>";
//echo "</tr></table>";


//					<p>Keresztény országértékelõ - 2016 (<a href='assets/img/orszagertekelo2016.png'>plakát megtekintése</a>)</p>
//					<p>Cornelius-díj átadóünnepség - 2016 (<a href='assets/img/Cornelius2016.jpg'>plakát megtekintése</a>)</p>

echo "</div>";

echo "</body></HTML>";
?>
