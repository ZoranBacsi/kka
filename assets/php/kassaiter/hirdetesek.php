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

echo "<body class='frame_kozep'>";
require("../a_kapcs.inc.cS7.php");
dbkapcs();

echo "<table class='cimlap'><tr>";
echo "<td>";
	$query="select hir_nap, hir_sorrend, hir_leiras, DATEDIFF(hir_nap,current_date) as d from a_hirdetesek where hir_del=0 and substr(hir_megjelenik,1,1)='I'"; 
	$query=$query." and DATEDIFF(hir_nap,current_date)>-7 and DATEDIFF(hir_nap,current_date)<2 or (DATEDIFF(hir_nap,current_date)<0 and hir_nap in (select max(hir_nap) from a_hirdetesek)) order by hir_nap DESC, hir_sorrend ASC";
	$eredm=mysql_query($query);  //query futtatása
	$nap='';
	while($sor=mysql_fetch_array($eredm)) 
	{
		if ($nap!=$sor["hir_nap"]) 
		{echo "<h2>".$sor["hir_nap"]."</h2>";
			if ($nap!='') {echo "</ol>";}
			$nap=$sor["hir_nap"];
			echo "<ol>";	
		}
		echo "<li>".$sor["hir_leiras"]."</li>";
	}
	echo "</ol>";
echo "</td>";
echo "<td>";
	$query="select hkep_id, hkep_link from a_hirdetes_kepek where DATEDIFF(current_date,hkep_ervenyes)<=0 and coalesce(hkep_kezd,current_date)>=current_date and hkep_del=0 and substr(hkep_megjelenik,1,1)='I'";
	$query=$query." and hkep_tip_id in (1045,1046) order by hkep_ervenyes DESC"; 
	$elso='Nagyításhoz, kérem kattintson a képre!';
	$eredm=mysql_query($query);  //query futtatása
	while($sor=mysql_fetch_array($eredm)) 
	{   if ($elso!='') {echo $elso; $elso='';}
		echo "<img onclick='megnyit_".$sor["hkep_id"]."()' style='cursor:pointer; display: block; margin-left: auto; margin-right: auto;' alt='' src='".$sor["hkep_link"]."' width=160 /><br>";
	 	echo "<script>function megnyit_".$sor["hkep_id"]."() {window.open('".$sor["hkep_link"]."','','width=800, height=500');}</script>"; 
	}
echo "</td>";
echo "</tr></table>";

echo "</body></HTML>";
?>

