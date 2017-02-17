<?php
echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body class='frame_kozep'>";

require("a_kapcs.inc.cS7.php");
//require("include_HTML.js");
dbkapcs();


echo "<table name='menusor' class='menufent'><tr>";
$query="SELECT m_id,  m_nev, m_url, m_stilus, case when SUBSTRING(m_url,1,4)<>'..//' then concat('<a href=''',m_url,''' target=''',m_target,'''>', m_nev,'</a>') else concat('<a href=''cimlap_menu.php?id=',m_szulo_m_id,'&akt=',m_id,''' target=''',m_target,'''>', m_nev,'</a>') end AS hivatk FROM a_menuk WHERE m_szulo_m_id =".$_GET['id']." AND m_del=0 ORDER BY m_sorrend";
$eredm=mysql_query($query);
$link='';
$db=0;
$mlen=0;
$line=2;
$max=4;
$szeles=100/$max;

while ($sor=mysql_fetch_array($eredm))
{	
	if (($sor["m_id"]==$_GET['akt']) or (($_GET['akt']==0) and ($link=='')))
		{ $link=$sor["m_url"];}   //.;
	echo "<td width='".$szeles."%'>".$sor["hivatk"]."</td>";
	$db++;
	if (strlen($sor["m_nev"])>$mlen) {$mlen=strlen($sor["m_nev"]);}
	if ($db==$max) 
		{	$db=0; 
			$line=$line+$mlen/25;
			$mlen=0;
			echo "</tr><tr>"; }
}
echo "</tr></table>";

$db=0;
while ($db<$line)
{	
	echo "<br>";
	$db++;
}


echo "<div include_html='".$link."'></div>";
echo "<script src='include_HTML.js'></script>";

echo "</body></HTML>";

?>



