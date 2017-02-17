<?php
echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body  class='frame_bal'>";

require("a_kapcs.inc.cS7.php");
dbkapcs();


echo "<div id='navigation'>";
echo "<ul>";
   $query="SELECT m_id, m_nev, case when m_url is null then concat(m_nev) else concat('<a href=''',m_url,''' target=''',m_target,'''>',m_nev,'</a>') end as menu FROM a_menuk WHERE m_hely='MENU' and m_del=0 and m_szulo_m_id is null order by m_sorrend";
   $query_eredm=mysql_query($query);
   while($eredm=mysql_fetch_array($query_eredm)){
    echo "<li>";
	  echo $eredm['menu']; 

	  $query2="SELECT m_id,  m_nev, CASE WHEN m_url IS NULL THEN concat( '<a href=''cimlap_menu.php?id=',m_id,'&akt=0'' target=''',m_target,'''>', m_nev, '</a>' ) ELSE concat( '<a href=''', m_url, ''' target=''',m_target,'''>', m_nev, '</a>' ) END AS menu FROM a_menuk WHERE m_szulo_m_id =".$eredm['m_id']." AND m_del=0 ORDER BY m_sorrend";
      $query2_eredm=mysql_query($query2);

      echo "<ul>";
      while($eredm2=mysql_fetch_array($query2_eredm)){ 	  
			echo "<li>"; 		
			echo $eredm2['menu']; 

//				$query3="SELECT m_id,  m_nev, CASE WHEN m_url IS NULL THEN concat( m_nev ) ELSE concat( '<a href=''', m_url, ''' target=''',m_target,'''>', m_nev, '</a>' ) END AS menu FROM a_menuk WHERE m_szulo_m_id =".$eredm2['m_id']." AND m_del=0 ORDER BY m_sorrend";
//				$query3_eredm=mysql_query($query3);
//				echo "<ul>";
//				while($eredm3=mysql_fetch_array($query3_eredm)){ 	  
//					echo "<li>"; 		
//					echo $eredm3['menu']; 
//					echo "</li>"; 		
//				}
//				echo "</ul>";
			echo "</li>"; 		
			}
      echo "</ul>";
    echo "</li>";  
   }
echo "</ul>";
echo "</div>";



echo "<table class='lent'><tr><td>"; // 

$query="select * from a_menuk where m_del=0 and m_hely='BSZ_LENT1' order by m_sorrend"; 
$eredm=mysql_query($query);  //query futtatása
$nap='';
while($sor=mysql_fetch_array($eredm)) 
{   $cur="";
	$click="";
	if ($sor["m_url"]!=Null) {	
			echo "<script>function megnyit".$sor["m_id"]."() {window.open('".$sor["m_url"]."','','');}</script>"; 
			$cur="style='cursor:pointer;'"; 
			$click="onclick='megnyit".$sor["m_id"]."()'";}
	if ($sor["m_id"]==7) {
			echo "<script>function adminlogin() {    window.open('./admin_login.php','','');}</script>";
			$click="ondblclick='adminlogin()'";			}

	echo "<img ".$cur." class='".$sor["m_stilus"]."' style=' margin-left: auto; margin-right: auto;' ".$click."  src='".$sor["m_kep_link"]."' width=140 />";
}  /*style='display: block; margin-left: auto; margin-right: auto;'*/
echo "</td><td>";
$query="select * from a_menuk where m_del=0 and m_hely='BSZ_LENT2' order by m_sorrend"; 
$eredm=mysql_query($query);  //query futtatása
$nap='';
while($sor=mysql_fetch_array($eredm)) 
{   $cur="";
	if ($sor["m_url"]!=Null) {	
			echo "<script>function megnyit".$sor["m_id"]."() {window.open('".$sor["m_url"]."','','');}</script>"; 
			$cur="style='cursor:pointer;'"; }
	echo "<img ".$cur." class='".$sor["m_stilus"]."' style=' margin-left: auto; margin-right: auto;' onclick='megnyit".$sor["m_id"]."()' src='".$sor["m_kep_link"]."' width=140 />";
}
echo "</td></tr></table>";


echo "</body></HTML>";
?>

