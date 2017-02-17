<?php

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

echo "<body onload=meret()><script>";
echo "function meret()";
echo "{window.parent.document.getElementById('onlineradio').style.height = document.getElementById('dtable').offsetHeight;}";
echo "</script>";

require("../a_kapcs.inc.cS7.php");
dbkapcs();


echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/iranyatermeszet.png' alt='' align='middle'/></p>";
echo "<p>Ez az oldal arra szolg�l, hogy k�z�ss�g�nk tagjai feltehess�k a tervezett kir�ndul�sokat �s a t�bbiek �gy csatlakozhassanak hozz�juk.<br>";
echo "<b>A kir�ndul�sok (szabadid�s programok) megjelen�t�se k�rhet� egy lev�l meg�r�s�v�l: <a href='mailto:csernyilaszlo.kassaiter@gmail.com?Subject=irany a term�szet&body=Kedves C�mzett!%0DK�rlek a honlapra kirakni az al�bbit:%0D%0D- Tal�lkoz� id�pontja:%0D- Tal�lkoz� helysz�ne:%0D- A program r�vid le�r�sa:%0D- A program id�tartama:%0D- A program le�r�sa:%0D- Jelentkez�si hat�rid�:%0D- Kapcsolattart� adatai: (E-mail, n�v)%0D-Kedvcsin�l�k�nt egy url (nem k�telez�):%0D%0DK�sz�nettel:'>Kir�ndul�s bek�ld�se</a></b></p>";

echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/aktualis_ajanlat.png' alt='' align='middle'/></p>";

$query="SELECT kir_id, case when kir_taldate>='9999-01-01 00:00:00' then 'Tervez�s alatt' else kir_taldate end kir_taldate, kir_talhely, kir_nev, kir_leiras, kir_idotartam, case when kir_jelhatdate<current_timestamp() then concat(kir_jelhatdate,' volt, az esetleges m�dosul�sokr�l az id�ben jelentkezettek �rtes�tve lettek/lesznek, a fel�leten nem biztos, hogy ez megjelenik!!!') else kir_jelhatdate end kir_jelhatdate, kir_kapcsolat, coalesce(kir_izelito,'-') as izelito "; 
$query=$query."FROM a_kirandulas where kir_del=0 and substr(kir_megjelenik,1,1)='I' and kir_taldate>=current_timestamp() ORDER BY coalesce(kir_jelhatdate,kir_taldate) ";
$query_eredm=mysql_query($query);  
$rekordok = mysql_num_rows($query_eredm);
if ($rekordok==0)
{echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Aktu�lis programok' src='/assets/img/gomb_cimkek/nincs_adat.png' height=35 /></p>";}
else
{
echo "<tbody><table style='width:100%'>";
while($eredm=mysql_fetch_array($query_eredm)) 
{
	echo "<tr><th colspan='2' style='width: 100%; align=left'><p>Program megnevez�se: ".$eredm["kir_nev"]."</p></th></tr>";
	echo "<tr>";
	echo "<td style='width: 40%; align=left'><p>Tal�lkoz�:<br>".$eredm["kir_taldate"]."<br>".$eredm["kir_talhely"]."<br><br>Id�tartam:<br>".$eredm["kir_idotartam"]."<br><br>Jelentkez�si hat�rid�<br>".$eredm["kir_jelhatdate"]."<br><br>Kontakt (szervez�)<br>".$eredm["kir_kapcsolat"]."</p></td>";
	echo "<td style='width: 60%;'><p>".$eredm["kir_leiras"]."</p>";
	if ($eredm["izelito"]!='-') 
	{	if (strpos(substr($eredm["izelito"],-4),'.')===false) {echo "<a href='".$eredm["izelito"]."' target='_blank'>Kedvcsin�l�</a>";}
		else {echo "<img src='".$eredm["izelito"]."' height='250'>";}
	}
	echo "</td>";
	echo "</tr>";
}
echo "</table>";
}

echo "<p style='text-align:center;'><img src='/assets/img/gomb_cimkek/korabbi.png' alt='' align='middle'/></p>";

$query="SELECT kir_id, kir_taldate, kir_talhely, kir_nev, kir_leiras, kir_idotartam, kir_jelhatdate, kir_kapcsolat, coalesce(kir_izelito,'-') as izelito "; 
$query=$query."FROM a_kirandulas where kir_del=0 and substr(kir_megjelenik,1,1)='I' and coalesce(kir_jelhatdate,kir_taldate)<current_timestamp() ORDER BY kir_taldate desc";

$query_eredm=mysql_query($query);  

$rekordok = mysql_num_rows($query_eredm);
if ($rekordok==0)
{echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='Aktu�lis programok' src='/assets/img/gomb_cimkek/nincs_adat.png' height=35 /></p>";}
else
{
echo "<table style='width:100%'>";
while($eredm=mysql_fetch_array($query_eredm)) 
{
	echo "<tr><th colspan='2' style='width: 100%; align=left'><p>Program megnevez�se: ".$eredm["kir_nev"]."</p></th></tr>";
	echo "<tr>";
	echo "<td style='width: 40%; align=left'><p>Tal�lkoz�:<br>".$eredm["kir_taldate"]."<br>".$eredm["kir_talhely"]."<br><br>Id�tartam:<br>".$eredm["kir_idotartam"]."<br><br>Jelentkez�si hat�rid�<br>".$eredm["kir_jelhatdate"]."<br><br>Kontakt (szervez�)<br>".$eredm["kir_kapcsolat"]."</p></td>";
	echo "<td style='width: 60%;'><p>".$eredm["kir_leiras"]."</p>";
	if ($eredm["izelito"]!='-') 
	{	if (strpos(substr($eredm["izelito"],-4),'.')===false) {echo "<a href='".$eredm["izelito"]."' target='_blank'>Kedvcsin�l�</a>";}
		else {echo "<img src='".$eredm["izelito"]."' height='250'>";}
	}
	echo "</tr>";
}
echo "</table>";
}

echo "</body></HTML>";


?>