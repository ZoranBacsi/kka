<?php

echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /><style> td {border-top: solid black;} p {font-size: 12pt;} </style> </HEAD>";
echo "<body onload=meret()><script>";
echo "function meret()";
echo "{window.parent.document.getElementById('onlineradio').style.height = document.getElementById('dtable').offsetHeight;}";
echo "</script>";

require("a_kapcs.inc.cS7.php");
dbkapcs();


echo "<p style='text-align:center;'><img src='/images/stories/Cikkekhez/hirdetes/ad-keres.png' alt='' align='middle'/></p>";
echo "<p>Ez az oldal arra szolg�l, hogy k�z�ss�g�nk tagjai<br>";
echo "- a sz�mukra sz�ks�gtelen, de hasznos eszk�z�ket a t�bbiek sz�m�ra felaj�nlhass�k<br>";
echo "- a sz�mukra sz�ks�ges seg�ts�geket felrakhass�k, melyben a t�bbiek seg�ts�g�re lehetnek.</p>";
//echo "<p><b>A hirdet�sek felad�s�hoz, felaj�nl�sok ig�nybev�tel�hez sz�ks�g van az atya, vagy a v�lasztott egyh�ztest�leti tag j�v�hagy�s�hoz (a regisztr�ci�hoz sz�ks�ges adatokat nekik kell megadni (N�v, email c�m)). A j�v�hagy�s/regisztr�ci�val biztos�tjuk, hogy k�z�ss�g�nk tagja vehesse csak ig�nybe a felaj�nl�sokat.</b></p>";
//a regisztr�lt e-mail c�mr�l a 
echo "<p><b>A felaj�nl�sok ig�nybev�tel�hez egy levelet kell �rni <a href='mailto:csernyilaszlo.kassaiter@gmail.com?Subject=ad-keres'>csernyilaszlo.kassaiter@gmail.com</a> c�mre a megfelel� hirdet�s hivatkoz�si sz�m�val.</b></p>";
//echo "<h3>A felaj�nl� az ingyenes t�rgy �tad�sakor k�rheti az adott �vi egyh�zi hozz�j�rul�s igazol�s�t!</h3>";



echo "<p><h2><strong>Hirdet�s bek�ld�se:</strong></h2></p>";
echo "<p>A felaj�nl�s/sz�ks�glet megjelen�t�s�hez a k�vetkez� adatok sz�ks�gesek (a * jel�lt k�telez�):<br>";
echo "- Megnevez�s (*) : (mit aj�nl fel, avagy mire van sz�ks�ge)<br>";
echo "- F�nyk�p : (lehet�leg jpg form�tumban)<br>";
echo "- Hat�rid� : (A hirdet�s eddig lesz fent. Amennyiben nem adja meg, akkor addig, m�g nem jelzi �jra, hogy m�r nem aktu�lis a hirdet�s.)<br>";
echo "- H�rdet� neve (*) : (nem ker�l megjelen�t�sre!)<br>";
echo "- H�rdet� e-mail c�me (*): (Nem ker�l megjelen�t�sre! Amennyiben jelentkezik valaki a hirdet�sre, erre a c�mre fogjuk a megkeres�st tov�bb�tani!)<br>";
echo "- T�pus : (Amennyiben sz�ks�gletr�l van sz�, akkor ingyenesen/megegyez�s alapj�n van r� sz�ks�ge.)</p>";

echo "<h2><strong>Hirdet�sek:</strong></h2>";

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
echo "<tr><th>Sz�vegr�szlet</th><th>Felaj�nl�s</th>";
echo "<tr><td><input type='text' name='mitszoveg' value='".$mitszoveg."'></td>";
//echo "<td></td>";
echo "<td><SELECT name='tipus'>";
if ($tipus=='')  { echo "<OPTION value=''  selected></OPTION>";}  else { echo "<OPTION value='' ></OPTION>";}
if ($tipus=='I') { echo "<OPTION value='I' selected>I</OPTION>";} else { echo "<OPTION value='I'>I</OPTION>";}
if ($tipus=='N') { echo "<OPTION value='N' selected>N</OPTION>";} else { echo "<OPTION value='N'>N</OPTION>";}
echo "</SELECT></td>";
echo "</tr></table>";
echo "</th><th width='10%' >";
echo "<input type='submit' name='szures' value='Sz�r�s'>";
echo "</th></tr></table>";
echo "</form>";


$query="SELECT adk_id, adk_mit, coalesce(adk_kep,'nincs.png') as adk_kep, case when adk_ar is null then 'megegyez�s szerint' when adk_ar=0 then 'ingyen' else cast(adk_ar as char) end as adk_ar FROM a_adkeres where adk_del=0 and coalesce(adk_meddig,current_date())>=current_date() and ".$where." ORDER BY adk_meddig desc, adk_feldate desc"; 
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

	echo "<tr><td style='width: 50%;' align='left'><p>".$eredm["adk_mit"]."<br><br>D�jaz�s : ".$eredm["adk_ar"]."<br><br>Hivatkoz�si sz�m: *".$eredm["adk_id"]."*<p></td>";
	echo "<td style='width: 50%;'><img alt='".$eredm["adk_kep"]."' src='".$eredm["adk_kep"]."' width='".$szeles."' height='".$magas."' /></td></tr>";
}
echo "</table>";

echo "</body></HTML>";


?>