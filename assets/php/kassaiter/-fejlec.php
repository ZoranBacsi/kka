<?php
echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /> </HEAD>";
echo "<body class='frame_fent'>";

require("a_kapcs.inc.cS7.php");
dbkapcs();

echo "<script>function nyit_napievangelium() {window.open('http://evangelium.katolikus.hu/','','width=900, height=800, scrollbars=yes, resizable=no');}</script>";

echo "<table class='fejlec'><tr>";
echo "<td width='30%'></td>";
echo "<td width='30%'><a href='/index.html' title='Ugrás a kezdõlapra!' target='_parent'><img  alt='istenhozta' style='align=middle;' src='../images/hatterek/istenhozta.png' height='70%' /></a></td>";
echo "<td width='30%'></td>";
echo "<td width='10%' onclick='nyit_napievangelium()' style='cursor:pointer'><img style='display: block; margin-left: auto; margin-right: auto;' alt='' src='../images/gomb_cimke/biblia4low.png' width=120 /><img src='../images/gomb_cimke/napievangelium.png' width=120 /></td>";
echo "</table>";

echo "<table class='lent'><tr>";
//programok 
$query="SELECT coalesce(sum(case when prg_nap>=current_date() then 1 else 0 end),0) as db, coalesce(sum(case when DATEDIFF(current_date(),prg_lastupd) between 0 and 8  then 1 else 0 end),0) as uj, coalesce(sum(case when DATEDIFF(prg_nap,current_date()) between 0 and 8  then 1 else 0 end),0) as most FROM `a_programok` where prg_del=0 ";
$eredm=mysql_query($query);
while ($sor=mysql_fetch_array($eredm))
{		echo "<td><a href='programok.php' target='frame_kozep'><img alt='programajnl' src='..//images/gomb_cimke/programok_link.png' /></a>".$sor["db"]." db (".$sor["uj"]." új, ".$sor["most"]." közeli)</td>"; 	
}

//kirandulas
$query="SELECT coalesce(sum(case when coalesce(kir_jelhatdate,kir_taldate)>=current_timestamp() then 1 else 0 end),0) as db, coalesce(sum(case when DATEDIFF(current_date(),kir_lastupd) between 0 and 8  then 1 else 0 end),0) as uj, coalesce(sum(case when DATEDIFF(coalesce(kir_jelhatdate,kir_taldate),current_date()) between 0 and 8 then 1 else 0 end),0) as most  FROM a_kirandulas where kir_del=0";
$eredm=mysql_query($query);
while ($sor=mysql_fetch_array($eredm))
{		echo "<td><a href='kirandulas.php' target='frame_kozep'><img alt='kirandulasok' src='..//images/gomb_cimke/kirandulas_link.png' /></a>".$sor["db"]." db (".$sor["uj"]." új, ".$sor["most"]." közeli)</td>"; 
}

//ad-keres
$query="SELECT coalesce(sum(case when coalesce(adk_meddig,current_date())>=current_date() then 1 else 0 end),0) as db, coalesce(sum(case when DATEDIFF(current_date(),adk_feldate) between 0 and 8  then 1 else 0 end),0) as uj  FROM `a_adkeres` WHERE adk_del=0";
$eredm=mysql_query($query);
while ($sor=mysql_fetch_array($eredm))
{		echo "<td><a href='adkeres.php' target='frame_kozep'><img alt='adkeres' src='..//images/gomb_cimke/adkeres_link.png' /></a>".$sor["db"]." db (".$sor["uj"]." új)</td>"; 	
}

//könyvtár
$query="SELECT count( * ) AS db FROM `a_konyvtar` WHERE konyv_del =0";
$eredm=mysql_query($query);
while ($sor=mysql_fetch_array($eredm))
{	if ($sor["db"]>0) 
	{	echo "<td><a href='konyvtar.php' target='frame_kozep'><img alt='konyvtar' src='..//images/gomb_cimke/konyvtar_link.png' /></a>".$sor["db"]." db</td>"; 	}					  
}
echo "</tr></table>";


echo "</body></HTML>";
?>



