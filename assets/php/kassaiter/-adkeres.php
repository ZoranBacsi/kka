<?php

echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /><style> td {border-top: solid black;} p {font-size: 12pt;} </style> </HEAD>";
echo "<body onload=meret()><script>";
echo "function meret()";
echo "{window.parent.document.getElementById('onlineradio').style.height = document.getElementById('dtable').offsetHeight;}";
echo "</script>";

require("a_kapcs.inc.cS7.php");
dbkapcs();


echo "<p style='text-align:center;'><img src='/images/stories/Cikkekhez/hirdetes/ad-keres.png' alt='' align='middle'/></p>";
echo "<p>Ez az oldal arra szolgál, hogy közösségünk tagjai<br>";
echo "- a számukra szükségtelen, de hasznos eszközöket a többiek számára felajánlhassák<br>";
echo "- a számukra szükséges segítségeket felrakhassák, melyben a többiek segítségére lehetnek.</p>";
//echo "<p><b>A hirdetések feladásához, felajánlások igénybevételéhez szükség van az atya, vagy a választott egyháztestületi tag jóváhagyásához (a regisztrációhoz szükséges adatokat nekik kell megadni (Név, email cím)). A jóváhagyás/regisztrációval biztosítjuk, hogy közösségünk tagja vehesse csak igénybe a felajánlásokat.</b></p>";
//a regisztrált e-mail címrõl a 
echo "<p><b>A felajánlások igénybevételéhez egy levelet kell írni <a href='mailto:csernyilaszlo.kassaiter@gmail.com?Subject=ad-keres'>csernyilaszlo.kassaiter@gmail.com</a> címre a megfelelõ hirdetés hivatkozási számával.</b></p>";
//echo "<h3>A felajánló az ingyenes tárgy átadásakor kérheti az adott évi egyházi hozzájárulás igazolását!</h3>";



echo "<p><h2><strong>Hirdetés beküldése:</strong></h2></p>";
echo "<p>A felajánlás/szükséglet megjelenítéséhez a következõ adatok szükségesek (a * jelölt kötelezõ):<br>";
echo "- Megnevezés (*) : (mit ajánl fel, avagy mire van szüksége)<br>";
echo "- Fénykép : (lehetõleg jpg formátumban)<br>";
echo "- Határidõ : (A hirdetés eddig lesz fent. Amennyiben nem adja meg, akkor addig, míg nem jelzi újra, hogy már nem aktuális a hirdetés.)<br>";
echo "- Hírdetõ neve (*) : (nem kerül megjelenítésre!)<br>";
echo "- Hírdetõ e-mail címe (*): (Nem kerül megjelenítésre! Amennyiben jelentkezik valaki a hirdetésre, erre a címre fogjuk a megkeresést továbbítani!)<br>";
echo "- Típus : (Amennyiben szükségletrõl van szó, akkor ingyenesen/megegyezés alapján van rá szüksége.)</p>";

echo "<h2><strong>Hirdetések:</strong></h2>";

if ($_GET['mitszoveg']=="") {$mitszoveg='';}
		else {$mitszoveg=$_GET['mitszoveg'];}
if ($_GET['tipus']=="") {$tipus='';}
		else {$tipus=$_GET['tipus'];}

if ($mitszoveg=='') 
	 {	$where=" (1=1 and ";	}
else {	$where=" (adk_mit like '%".$mitszoveg."%' and ";	}
if ($tipus=='') 
	 {	$where=$where." 1=1) ";	}
else {	$where=$where." adk_ad='".$tipus."') ";	}

echo "<form action='adkeres.php' name='szures' method='get'>";
echo "<table border='0' bgcolor='gray'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th>Szövegrészlet</th><th>Felajánlás</th>";
echo "<tr><td><input type='text' name='mitszoveg' value='".$mitszoveg."'></td>";
//echo "<td></td>";
echo "<td><SELECT name='tipus'>";
if ($tipus=='')  { echo "<OPTION value=''  selected></OPTION>";}  else { echo "<OPTION value='' ></OPTION>";}
if ($tipus=='I') { echo "<OPTION value='I' selected>I</OPTION>";} else { echo "<OPTION value='I'>I</OPTION>";}
if ($tipus=='N') { echo "<OPTION value='N' selected>N</OPTION>";} else { echo "<OPTION value='N'>N</OPTION>";}
echo "</SELECT></td>";
echo "</tr></table>";
echo "</th><th width='10%' >";
echo "<input type='submit' name='szures' value='Szûrés'>";
echo "</th></tr></table>";
echo "</form>";


$query="SELECT adk_id, adk_mit, coalesce(adk_kep,'nincs.png') as adk_kep, case when adk_ar is null then 'megegyezés szerint' when adk_ar=0 then 'ingyen' else cast(adk_ar as char) end as adk_ar FROM a_adkeres where adk_del=0 and coalesce(adk_meddig,current_date())>=current_date() and ".$where." ORDER BY adk_meddig desc, adk_feldate desc"; 
$query_eredm=mysql_query($query);  

echo "<table><tbody>";
while($eredm=mysql_fetch_array($query_eredm)) 
{
	if (($eredm["adk_kep"]==null) || ($eredm["adk_kep"]=='nincs.png'))
	{$magas='25%'; 
	 $szeles='25%';}
	else
	{$magas='50%'; 
	 $szeles='50%';}

	echo "<tr><td style='width: 50%;' align='left'><p>".$eredm["adk_mit"]."<br><br>Díjazás : ".$eredm["adk_ar"]."<br><br>Hivatkozási szám: *".$eredm["adk_id"]."*<p></td>";
	echo "<td style='width: 50%;'><img alt='".$eredm["adk_kep"]."' src='".$eredm["adk_kep"]."' width='".$szeles."' height='".$magas."' /></td></tr>";
}
echo "</table>";

echo "</body></HTML>";


?>