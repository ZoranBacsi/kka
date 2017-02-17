<?php
echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='programok' src='/images/stories/Cikkekhez/programok.png' height=50 /></p>";

echo "<table class='normal'>";
echo "<tr bgcolor='#D8D8D8'><th width='10%'>Dátum</br>Idõpont</th><th  width='25%'>Helyszín</th><th width='45%'>Leírás</th>";
echo "</tr>";
require("a_kapcs.inc.cS7.php");
dbkapcs();
$query="select prg_nap, prg_ido, hely_rnev, hely_megnev, hely_cim, coalesce(hely_link,'-') as hely_link, 
coalesce(F1.szem_nev,'-') AS sz1, coalesce(F1.szem_hiv,'-') as szh1, coalesce(F2.szem_nev, '-' ) as sz2, coalesce(F2.szem_hiv,'-') as szh2, prg_leiras, coalesce(prg_leirlink,'-') as link, case when DATEDIFF(prg_nap,current_date)<0 then -1 when DATEDIFF(prg_nap,current_date)=0 then 0 when DATEDIFF(prg_nap,current_date)<7 then 1 when DATEDIFF(prg_nap,current_date)<31 then 2 else 3 end as friss,  case DAYOFWEEK(prg_nap) when 1 then 'Vasárnap' when 2 then 'Hétfo' when 3 then 'Kedd' when 4 then 'Szerda' when 5 then 'Csütörtök' when 6 then 'Péntek' else 'Szombat' end as nap, prg_arkat, coalesce(prg_reszletes,'') as prg_reszletes from a_programok P join a_helyszinek on hely_id=prg_hely_id join a_tipusok on prg_tip_id=tip_id LEFT JOIN a_szemelyek F1 ON P.prg_szem_id_1 = F1.szem_id LEFT JOIN a_szemelyek F2 ON P.prg_szem_id_2 = F2.szem_id where prg_del=0 and prg_nap>=current_date and tip_fajta='program' order by prg_nap ASC, prg_ido ASC"; 

$eredm=mysql_query($query);  //query futtatása
// $sorokszama=mysql_num_rows($eredm);
while($sor=mysql_fetch_array($eredm)) //kiveszi az éppen aktuális sornak a sorszámát
		//igaz amíg ki tud veni adaot, ha már nem, akkor null ami hamis értéknek felel meg
		//mysql_fetch_row($eredm) esetében nem mezõnévre, ahenm mezõ sorszámára hivatkozhatnánk
{

   switch ($sor["friss"])
	{
	   case 0 : 
				echo "<tr bgcolor='#FFD700'>";
				break;
	   case 1 : 
				echo "<tr bgcolor='#ADFF2F'>";
				break;
	   case 2 : 
				echo "<tr bgcolor='#FFFACD'>";
				break;
	   case 3 : 
				echo "<tr >";
				break;
	   default:
				echo "<tr bgcolor='#D8D8D8'>";	
	} 
	echo "<td>".$sor["prg_nap"]."</br>".$sor["prg_ido"]."</br>".$sor["nap"]."</td>";
	if ($sor["hely_link"]!='-')
	{echo "<td><a target='_blank' href='".$sor["hely_link"]."'>".$sor["hely_megnev"]."</a></br>".$sor["hely_cim"];}
		else 
	{echo "<td>".$sor["hely_megnev"]."</br>".$sor["hely_cim"];}
	 if ($sor["prg_arkat"]!='ingyenes')
		{echo "</br>(".$sor["prg_arkat"]." HUF)</td>";}
		else
		{echo "</br>(".$sor["prg_arkat"].")</td>";}

	echo "<td>";
	if ($sor["szh1"]=='-')
		{if ($sor["sz1"]!='-')
			{echo "<b>".$sor["sz1"]."</b>";}
		}
	else
		{echo "<a target='_blank' href='".$sor["szh1"]."'><b>".$sor["sz1"]."</b></a>";}

	if ($sor["sz2"]!='-')
		{if ($sor["szh2"]=='-')
			{echo " - <b>".$sor["sz2"]."</b>";}
		else
			{echo " - <a target='_blank' href='".$sor["szh2"]."'><b>".$sor["sz2"]."</b></a>";}
		}
	else
		{echo "";}

	if ($sor["sz1"]!='-') 	{    echo ":</br>";}

	if ($sor["link"]!='-')
		{echo "<a target='_blank' href='".$sor["link"]."'>".$sor["prg_leiras"]."</a>";}
	else
		{echo $sor["prg_leiras"];}

	if ($sor["prg_reszletes"]!='')
		{echo "</br>".$sor["prg_reszletes"];}

	echo "</td>";
	echo "</tr>";
}
echo "</table>";


echo "<p><img style='display: block; margin-left: auto; margin-right: auto;' alt='programok' src='/images/stories/Cikkekhez/korabbi.png' height=50 /></p>";

echo "<table class='normal'>";
echo "<tr bgcolor='#D8D8D8'><th width='10%'>Dátum</br>Idõpont</th><th  width='25%'>Helyszín</th><th width='45%'>Leírás</th>";
echo "</tr>";
require("a_kapcs.inc.cS7.php");
dbkapcs();
$query="select prg_nap, prg_ido, hely_rnev, hely_megnev, hely_cim, coalesce(hely_link,'-') as hely_link, 
coalesce(F1.szem_nev,'-') AS sz1, coalesce(F1.szem_hiv,'-') as szh1, coalesce(F2.szem_nev, '-' ) as sz2, coalesce(F2.szem_hiv,'-') as szh2, prg_leiras, coalesce(prg_leirlink,'-') as link, case when DATEDIFF(prg_nap,current_date)<0 then -1 when DATEDIFF(prg_nap,current_date)=0 then 0 when DATEDIFF(prg_nap,current_date)<7 then 1 when DATEDIFF(prg_nap,current_date)<31 then 2 else 3 end as friss,  case DAYOFWEEK(prg_nap) when 1 then 'Vasárnap' when 2 then 'Hétfo' when 3 then 'Kedd' when 4 then 'Szerda' when 5 then 'Csütörtök' when 6 then 'Péntek' else 'Szombat' end as nap, prg_arkat, coalesce(prg_reszletes,'') as prg_reszletes from a_programok P join a_helyszinek on hely_id=prg_hely_id join a_tipusok on prg_tip_id=tip_id LEFT JOIN a_szemelyek F1 ON P.prg_szem_id_1 = F1.szem_id LEFT JOIN a_szemelyek F2 ON P.prg_szem_id_2 = F2.szem_id where prg_del=0 and prg_nap<current_date and tip_fajta='program' order by prg_nap DESC, prg_ido DESC"; 

$eredm=mysql_query($query);  //query futtatása
// $sorokszama=mysql_num_rows($eredm);
while($sor=mysql_fetch_array($eredm)) //kiveszi az éppen aktuális sornak a sorszámát
		//igaz amíg ki tud veni adaot, ha már nem, akkor null ami hamis értéknek felel meg
		//mysql_fetch_row($eredm) esetében nem mezõnévre, ahenm mezõ sorszámára hivatkozhatnánk
{

   switch ($sor["friss"])
	{
	   case 0 : 
				echo "<tr bgcolor='#FFD700'>";
				break;
	   case 1 : 
				echo "<tr bgcolor='#ADFF2F'>";
				break;
	   case 2 : 
				echo "<tr bgcolor='#FFFACD'>";
				break;
	   case 3 : 
				echo "<tr >";
				break;
	   default:
				echo "<tr bgcolor='#D8D8D8'>";	
	} 
	echo "<td>".$sor["prg_nap"]."</br>".$sor["prg_ido"]."</br>".$sor["nap"]."</td>";
	if ($sor["hely_link"]!='-')
	{echo "<td><a target='_blank' href='".$sor["hely_link"]."'>".$sor["hely_megnev"]."</a></br>".$sor["hely_cim"];}
		else 
	{echo "<td>".$sor["hely_megnev"]."</br>".$sor["hely_cim"];}
	 if ($sor["prg_arkat"]!='ingyenes')
		{echo "</br>(".$sor["prg_arkat"]." HUF)</td>";}
		else
		{echo "</br>(".$sor["prg_arkat"].")</td>";}

	echo "<td>";
	if ($sor["szh1"]=='-')
		{if ($sor["sz1"]!='-')
			{echo "<b>".$sor["sz1"]."</b>";}
		}
	else
		{echo "<a target='_blank' href='".$sor["szh1"]."'><b>".$sor["sz1"]."</b></a>";}

	if ($sor["sz2"]!='-')
		{if ($sor["szh2"]=='-')
			{echo " - <b>".$sor["sz2"]."</b>";}
		else
			{echo " - <a target='_blank' href='".$sor["szh2"]."'><b>".$sor["sz2"]."</b></a>";}
		}
	else
		{echo "";}

	if ($sor["sz1"]!='-') 	{    echo ":</br>";}

	if ($sor["link"]!='-')
		{echo "<a target='_blank' href='".$sor["link"]."'>".$sor["prg_leiras"]."</a>";}
	else
		{echo $sor["prg_leiras"];}

	if ($sor["prg_reszletes"]!='')
		{echo "</br>".$sor["prg_reszletes"];}

	echo "</td>";
	echo "</tr>";
}
echo "</table>";



echo "</HTML>";
?>

