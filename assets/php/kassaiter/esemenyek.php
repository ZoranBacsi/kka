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

echo "<body class='szel_jobb'>";

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();


$query="select esm_nap, esm_ido, hely_rnev, tip_nev, case when coalesce(tip_tipus,'-')='-' then '' else concat('(',tip_tipus,')') end tip_tipus_mod, coalesce(esm_szemely1,'') esm_szemely1, coalesce(esm_szemely2,'') esm_szemely2, DAYOFWEEK(esm_nap) as nap, "; 
$query=$query."case when DATEDIFF(esm_nap,current_date)<0 then -1 when DATEDIFF(esm_nap,current_date)=0 then 0 when DATEDIFF(esm_nap,current_date)<7 then 1 when DATEDIFF(esm_nap,current_date)<31 then 2 else 3 end as friss, eveg_rnev, esm_perc "; 
$query=$query.", coalesce(esm_szemely1,concat(' ',esm_szemely2)) jellemzo, tip_tipus  from a_esemenyek "; 
$query=$query."   join a_helyszinek on hely_id=esm_hely_id "; 
$query=$query."   join a_tipusok on tip_id=esm_tip_id "; 
$query=$query."   left join a_esemeny_vegzo on eveg_id=esm_eveg_id "; 
$query=$query."where esm_del=0 and DATEDIFF( esm_nap, current_date) BETWEEN 0 AND 60 and substr(esm_megjelenik,1,1)='I' "; 
$query=$query."order by esm_nap ASC, case when tip_tipus='N' then 0 else 1 end ASC, esm_ido ASC"; 
$eredm=mysql_query($query);  //query futtatása
$rekordok = mysql_num_rows($eredm);
if ($rekordok==0)
{echo "<img style='display: block; margin-left: auto; margin-right: auto;' alt='esketés' src='..//images/gomb_cimke/nincs_adat.png' height=35 />";}
else
{
$voltnap=''; //date ( 'Y-m-d' , strtotime ( '-10 day' , strtotime ( date('Y-m-d') ) ) );

echo "<table class='mise'>"; 
while($sor=mysql_fetch_array($eredm)) 
	{
	if ($voltnap!=$sor["esm_nap"]) {$ujnap='I'; $voltnap=$sor["esm_nap"]; $tipN='I';}

    switch ($sor["friss"])	{
	   case 0 : $szin="bgcolor='#ffff00'><font color='#000000'>";		break;  //ffff00
	   case 1 : $szin="bgcolor='#ffcc66'><font color='#000000'>";		break;  //cc9900
	   case 2 : $szin="bgcolor='#996633'><font color='#ffff00'>";		break;  //996633
	   case 3 : $szin="bgcolor='#663300'><font color='#ffff00'>";		break;  //663300
	   default:	$szin="bgcolor='#c0c0c0'><font color='#000000'>";		}

	
	if ($ujnap=='I') {echo "<tr><th colspan='2'>".$voltnap." (".napok($sor['nap']).")"; $ujnap='N';}
		
	if ($sor["tip_tipus"]=='N') 
		{
			if ($sor["tip_nev"]=='perselygyûjtés') { echo "<div class='lista2'>".$sor["tip_nev"]." célja: ".$sor["jellemzo"]."</div>";}
			else { echo "<div class='lista2'>".$sor["esm_ido"]."-".$sor["jellemzo"]." ".$sor["tip_nev"]."</div>";}
		}
		else  //normál módú kiiratás
		{
			if ($tipN=='I') {echo "</th></tr>"; $tipN='N';}
			
			if (($sor["esm_szemely2"]!='') and ($sor["tip_nev"]!='Mise') and (strlen($sor["esm_szemely2"])>4) ) {$leiras='<br>'.$sor['esm_szemely2'];} else {$leiras='';}
			if ($sor["esm_szemely1"]!='') {$leiras=$leiras.'<br>'.$sor['esm_szemely1'];} 

			echo "<tr><td width='30%' ".$szin.$sor["esm_ido"]."</br>".$sor["hely_rnev"]."</font></td>";
			echo "<td width='60%' ".$szin.$sor["tip_nev"].$sor["tip_tipus_mod"].$leiras."</font></td></tr>";
		}
	}
echo "</table>";
}



echo "</body></HTML>";
?>

