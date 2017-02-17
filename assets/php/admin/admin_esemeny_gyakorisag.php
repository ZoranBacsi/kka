<?php
session_start();
if (!isset( $_SESSION["userid"])) die("Nincs bejelentkezve!");

require("../a_kapcs.inc.cS7.php");
require("../a_ellenorzes.inc.php");
dbkapcs();


if(isset($_GET['delid']))
{	$id=$_GET['delid'];
	$query1=mysql_query("update a_esemeny_gyakorisag set egy_del=1-egy_del where egy_id=".$id);
	if($query1){header('location:admin_esemeny_gyakorisag.php');}
	unset($_GET['felid']);	unset($_POST['beallit']);	unset($_POST['felvesz']);	unset($_POST['modosit']);
}


if ((isset($_GET['felid'])) or (isset($_GET['delfelid'])))
{
	$query="SELECT * from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='egy'"; 
	$eredm=mysql_query($query);  //query futtatása
	$sor=mysql_fetch_array($eredm);
	$kezd=$sor['tmp_tol'];	
	$veg=$sor['tmp_ig'];	

	//misék és több gyakorisággal, idõben átfedéssel rendelkezõ események esetén
	//if (($_GET['esm_tip_id']==4) or ($_GET['esm_tip_id']==5)) { 
		if (isset($_GET['felid'])) {$where=" and coalesce(esm_szemely2,'0')='".$_GET['felid']."'";} else {$where=" and coalesce(esm_szemely2,'0')='".$_GET['delfelid']."'";}	
	//} else {$where='';}  
	
	$query="delete from a_esemenyek where esm_tip_id=".$_GET['esm_tip_id'].$where." and esm_nap between '".$kezd."' and '".$veg."'";
	mysql_query($query) or die ("A törlés sikertelen!". $query); 
//	echo $query;
}

if ((isset($_GET['felid'])))
{  	$gyaktip=$_GET['gyaktip'];		 //hetente, havonta ...
	$gyakorisag=$_GET['gyakszam'];   //hány hetente, havonta, ...
	$hany=$_GET['hany'];			 //hányadikán ha gyaknap_tip_id is null, illetve hanyadik adott napon

    if ($_GET['angol_hetnapja']=='') {$milyen_napon=-1;} else {$milyen_napon=$_GET['angol_hetnapja'];}  //ha gyaknap_tip_id is not null (nem hanyadikán), akkor milyen napon

	$query="SELECT * from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='egy'"; 
	$eredm=mysql_query($query);  //query futtatása
	$sor=mysql_fetch_array($eredm);
	$kezd=$sor['tmp_tol'];			  //kezdõdátum
	$veg=$sor['tmp_ig'];			  //végdátum
	$elseje_nap=date('N', strtotime($kezd)); //milyen nap a kezõdátum

	$mostho =(int)date('m', strtotime($kezd));	// hanyadik hónap van
	$mosthet=(int)date('W', strtotime($kezd));	// hanyadik hét van

	$ho=0; $het=0; $ps=0; $pt=0;
	if ($gyaktip=='havonta') {$ho=1;}
	if ($gyaktip=='hetente') {
			switch ($hany) {
				case 1:		   $het=1;       break;
			    case 2:        $ps=1;        break;
			    case 3:        $pt=1;        break;			}							}

	$mostgyakorisag=1;
	$mosthanyadik=1;
	if (($het==1) or ($ho==1)) {	$mostgyakorisag=$gyakorisag; }					// a páros/páratlan hét kivételével az aktuális hónap/hét az elsõ olyan, ahol fel kell venni az eseményt és utána az adott gyakorisággal

	//echo " gyakorisag=".$gyakorisag.", ho=".$ho.", het=".$het.", pt=".$pt.", ps=".$ps.", milyen_napon=".$milyen_napon." -  ";

	while (strtotime($kezd)<=strtotime($veg)) {
		//echo "nap=".$kezd." mostho=".$mostho." mosthet=".$mosthet." mostgyakorisag=".$mostgyakorisag." hany=".$hany." ho=".$ho." date(m,$kezd)=".(int)date("m", strtotime($kezd))."  ".$kezd."   "."<br>";
		$ok=0;

		//gyakoriság szerinti hónap valahanyadikán
		if (($ho==1) and ($milyen_napon==-1) and ($mostgyakorisag==$gyakorisag)								and (date('j', strtotime($kezd))==$hany))			{$ok=1;}
		
		//gyakorisag szerinti hónap valahanyadik adott napján
		if (($ho==1) and ($milyen_napon!=-1) and ($mostgyakorisag==$gyakorisag) and ($mosthanyadik==$hany)	and (date('N', strtotime($kezd))==$milyen_napon))	{$ok=1;}

		//gyakorisag szerinti hét adott napján
		if (($het==1) and					     ($mostgyakorisag==$gyakorisag)								and (date('N', strtotime($kezd))==$milyen_napon))	{$ok=1;}

		//páros/páratlan hét adott napján
		if (((($ps==1) and ((int)date('W', strtotime($kezd)) % 2==0)) or 
			(($pt==1) and ((int)date('W', strtotime($kezd)) % 2==1))		)								and (date('N', strtotime($kezd))==$milyen_napon))	{$ok=1;}


		if ($ok==1)	{  
			$query="insert into a_esemenyek (esm_nap, esm_ido, esm_tip_id, esm_hely_id, esm_eveg_id, esm_perc, esm_rogzitette, esm_szemely1, esm_szemely2, esm_del) ";
			$query=$query."values ('".$kezd."','".$_GET[esm_ido]."',".$_GET[esm_tip_id].",".$_GET[esm_hely_id].",";		$query=$query.nulloz($_GET[esm_eveg_id]).",".$_GET[esm_perc].",'".$_SESSION["userid"]."','".$_GET['spec']."','".$_GET['felid']."',0)";
			mysql_query($query) or die ("A felvétel sikertelen!". $query); 
			$mostgyakorisag=0;
			$mosthanyadik=1;
			}

		$kezd = strtotime ( '+1 day' , strtotime ( $kezd ) ) ;
		$kezd = date ( 'Y-m-d' , $kezd );

		// hétváltás (hónapon belüli!!)
		if (date('N', strtotime($kezd))==$elseje_nap)					{										$mosthanyadik=$mosthanyadik+1;											   }   
		
		// hónapváltás  (havi rendszeresség)
		if (($ho ==1) and ($mostho!= (int)date('m', strtotime($kezd)))) {$mostgyakorisag=$mostgyakorisag+1;		$mosthanyadik=1;				$elseje_nap=date('N', strtotime($kezd));	$mostho  =(int)date('m', strtotime($kezd));}   
		
		// hétváltás	(heti rendszeresség)
		if (($het==1) and ($mosthet!=(int)date('W', strtotime($kezd)))) {$mostgyakorisag=$mostgyakorisag+1;																					$mosthet =(int)date('W', strtotime($kezd));} 
		
	}
	unset($_GET['delid']);	unset($_POST['beallit']);	unset($_POST['felvesz']);	unset($_POST['modosit']);
}
// $_GET['felid'] VÉGE

if(isset($_POST['beallit']))
{	$query="delete from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='egy'";
	mysql_query($query)  or die ("A törlés sikertelen!". $query);
	$query="insert into a_temp (tmp_user, tmp_menu, tmp_tol, tmp_ig) values (".$_SESSION["userid"].",'egy','".$_POST['dtol']."','".$_POST['dig']."')";
	mysql_query($query)  or die ("Az insert sikertelen!". $query);
	unset($_GET['delid']);	unset($_GET['felid']);	unset($_POST['felvesz']);	unset($_POST['modosit']);
}


if(isset($_POST['felvesz']))
{	if ( (ures($_POST["gyakmertek"]))  )	{		echo "A mezõk kitöltése kötelezõ!";	}
	else
 	{ 	$i=nulloz($_POST[hanyadik]);
		if ($i=='NULL') {$i=1;}
		$query="insert into a_esemeny_gyakorisag (egy_tipus_tip_id, egy_mertek, egy_gyak_tip_id, egy_idopont, egy_tartam, egy_gyaknap_tip_id, 
			egy_gyaknap_hanyadik, egy_eveg_id, egy_hely_id, egy_del) values (".$_POST[tipus].",'".$_POST[gyakmertek]."',".$_POST[gyak].",'".$_POST[ido]."',".nulloz($_POST[tartam]).",".nulloz($_POST[gyaknap]).",".
				$i.",".nulloz($_POST[vegzo]).",".$_POST[helyszin].",0)";
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
	}
	unset($_GET['delid']);	unset($_GET['felid']);	unset($_POST['beallit']);	unset($_POST['modosit']);
}

if(isset($_POST['modosit']))
{	if ( (ures($_POST["gyakmertek"]))  )	{		echo "A mezõk kitöltése kötelezõ!";	}
	else
 	{ 	$i=nulloz($_POST[hanyadik]);
		if ($i=='NULL') {$i=1;}
		$query="update a_esemeny_gyakorisag set egy_mertek='".$_POST[gyakmertek]."', egy_gyak_tip_id=".$_POST[gyak].", egy_idopont='".$_POST[ido]."', egy_tartam=".nulloz($_POST[tartam]).", egy_gyaknap_tip_id=".nulloz($_POST[gyaknap]).",";
		$query=$query."egy_gyaknap_hanyadik=".$i.", egy_eveg_id=".nulloz($_POST[vegzo]).", egy_hely_id=".$_POST[helyszin]." where egy_id=".$mid;
		mysql_query($query) or die ("A felvétel sikertelen!". $query);
	}
	unset($_GET['delid']);	unset($_GET['modid']);	unset($_GET['felid']);	unset($_POST['beallit']);	unset($_POST['felvesz']);
}


echo "<HTML><HEAD><meta http-equiv='Content-Type' content='text/html;charset=ISO-8859-1'/><link rel='stylesheet' type='text/css' href='cserlac.css' /></HEAD>";
echo "<body>";

$query="SELECT current_date() as datum"; 
$eredm=mysql_query($query);  
$sor=mysql_fetch_array($eredm);
$ev=substr($sor["datum"],0,4);

if(isset($_GET['modid'])) {
	$mozgatid=$_GET['modid'];		$gnev='modosit';			$felirat='Módosít';				$tilt=' disabled';			$szin='#ffcc66';
	$mtipid=$_GET['esm_tip_id'];	$mgyszam=$_GET['gyakszam'];	$mgyaktip=$_GET['gyaktip'];		$mido=$_GET['esm_ido'];		$mperc=$_GET['esm_perc'];	$mhany=$_GET['hany'];	$mnap=$_GET['gynap'];	$mhelyid=$_GET['esm_hely_id'];	$mevegid=$_GET['esm_eveg_id'];	
	unset($_POST['modosit']);					
		 } 
	else {	$mozgatid=0;			$gnev='felvesz';			$felirat='Felvesz';				$tilt='';					$szin='#669999';
			$mtipid=0;				$mgyszam=1;					$mgyaktip='havonta';			$mido='00:00';				$mperc=60;					$mhany=1;				$mnap=0;				$mhelyid=0;						$mevegid=0;	

		 }


echo "<p>Hetente ismétlõdéskor a hanyadik értékei: 1-Minden, 2-páros, 3-páratlan hét adott napjai!</p>";
echo "<form action='' name='".$gnev."' method='post'>";
echo "<table border='0' bgcolor='".$szin."'  align='center'><tr><th width='90%'>";
echo "<table border='3'>";
echo "<tr><th>Szervezõ</th><th></th><th>Gyakoriság</th><th>Idõpont</th><th>Tartam [perc]</th></tr>";
echo "<tr><td><input type='hidden' id='mid' name='mid' size='60' maxlength='80' value='".$mozgatid."'>";
	$query = "SELECT tip_id, tip_nev, tip_tipus FROM a_tipusok where tip_fajta='esemény' and tip_del=0 and tip_egy='I' order by tip_nev";
	$eredm = mysql_query($query);
	echo "<SELECT name='tipus' ".$tilt.">";  
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["tip_id"]==$mtipid) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["tip_id"]."' ".$sel.">";
		if (($rek["tip_tipus"]!='-') and ($rek["tip_tipus"]!='N')) {echo $rek["tip_nev"]."(".$rek["tip_tipus"].")</OPTION>";}
		else {echo $rek["tip_nev"]."</OPTION>";}
	}
	echo "</SELECT></td>";
echo "<td><input type='text' name='gyakmertek' size='2' maxlength='2' value='".$mgyszam."'></td>";
echo "<td>";
	$query = "SELECT tip_id, tip_nev, tip_tipus FROM a_tipusok where tip_fajta='ESMgyak' and tip_del=0 order by tip_nev";
	$eredm = mysql_query($query);
	echo "<SELECT name='gyak' >";
	while ($rek = mysql_fetch_array($eredm))
	{	if ($rek["tip_nev"]==$mgyaktip) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["tip_id"]."' ".$sel.">".$rek["tip_nev"]."</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td><input type='text' name='ido' size='5' maxlength='5' value='".$mido."'></td>";
echo "<td><input type='text' name='tartam' size='5' maxlength='5' value='".$mperc."'></td></tr>";


echo "<tr><th></th><th>Hanyadik</th><th>Milyen nap?</th><th>Helyszín</th><th>Jelenlevõ</th></tr>";
echo "<tr><td></td><td><input type='text' name='hanyadik' size='2' maxlength='2' value='".$mhany."'></td>";
echo "<td>";
	$query = "SELECT tip_id, tip_nev, tip_tipus FROM a_tipusok where tip_fajta='ESMgyaknap' and tip_del=0 order by tip_tipus";
	$eredm = mysql_query($query);
	echo "<SELECT name='gyaknap' >";
	if ('0'==$mnap) {$sel='selected';} else {$sel='';}
	echo "<OPTION value='0' ".$sel.">-</OPTION>";
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["tip_id"]==$mnap) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["tip_id"]."' ".$sel.">".$rek["tip_nev"]."</OPTION>";
	}
	echo "<OPTION value='Null'>-</OPTION>";
	echo "</SELECT></td>";
echo "<td>";
	$query = "SELECT hely_id, hely_megnev, hely_rnev, hely_cim FROM a_helyszinek where hely_del=0 and hely_naploba='I' order by hely_tip_id, hely_megnev";
	$eredm = mysql_query($query);
	echo "<SELECT name='helyszin'>";
	while ($rek = mysql_fetch_array($eredm))
	{   if ($rek["hely_id"]==$mhelyid) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["hely_id"]."' ".$sel.">".$rek["hely_rnev"]." (".$rek["hely_cim"].")</OPTION>";
	}
	echo "</SELECT></td>";
echo "<td>";
	$query = "SELECT eveg_id, eveg_tnev FROM a_esemeny_vegzo where eveg_del=0";
	$eredm = mysql_query($query);
	echo "<SELECT name='vegzo'>";
	if ('0'==$mevegid) {$sel='selected';} else {$sel='';}
	echo "<OPTION value='0' ".$sel.">-</OPTION>";
	while ($rek = mysql_fetch_array($eredm))
	{	if ($rek["eveg_id"]==$mevegid) {$sel='selected';} else {$sel='';}
		echo "<OPTION value='".$rek["eveg_id"]."' ".$sel.">".$rek["eveg_tnev"]."</OPTION>";
	}
	echo "</SELECT></td>";

echo "</tr></table>";

echo "</th><th width='10%' >";
echo "<input type='submit' name='".$gnev."' value='".$felirat."'>";
echo "</th></tr></table>";

echo "</form>";


echo "<p>'Tölt'-re kattintáskor a beállított dátumok között a (most felvételre kerülõ esemény) beállításai törlõdnek!</p>";
//echo "<p>Amennyiben nincs megadva, hogy hanyadik adott nap, akkor a gyakoriságnak megfelelõen minden elsõ olyan napon beállításra kerül.</p>";




$query="select * from a_temp where tmp_user=".$_SESSION["userid"]." and tmp_menu='egy'";
$eredm=mysql_query($query);  //query futtatása
$rekordok = mysql_num_rows($eredm);
if ($rekordok==0)
	{	$tol = date('Y-m-d');
		while (date('d', strtotime($tol))!=1) {
			$tol = strtotime ( '-1 day' , strtotime ( $tol ) ) ;
			$tol = date ( 'Y-m-d' , $tol );
		}
		$ig= strtotime ( '+1 year' , strtotime ( $tol ) ) ;
		$ig = date ( 'Y-m-d' , $ig );
		$query="insert into a_temp (tmp_user, tmp_menu, tmp_tol, tmp_ig) values (".$_SESSION["userid"].",'egy','".$tol."','".$ig."')";
		mysql_query($query)  or die ("Az insert sikertelen!". $query);

	}
	else
	{	$sor=mysql_fetch_array($eredm);
		$tol = $sor['tmp_tol'];
		$ig = $sor['tmp_ig'] ;
	}



echo "<form action='' name='beallit' method='post'><table border='0' bgcolor='gray'  align='center'>";
echo "<tr><th>Mettõl?</th><th>Meddig?</th><td rowspan='2'><input type='submit' name='beallit' value='Beállít'></td></tr>";
echo "<tr><td><input type='text' name='dtol' size='10' maxlength='10' value='".$tol."' ></td><td><input type='text' name='dig' size='10' maxlength='10' value='".$ig."'></td></tr>";
echo "</table></form>";

echo "<p>A beállított dátumok a táblázat jobb felsõ sarkában látható!</p>";



$query="SELECT egy_id, T1.tip_nev ki, egy_mertek gyak, case when T2.tip_nev='hetente' and egy_gyaknap_hanyadik=3 then 'páratlan hét' when T2.tip_nev='hetente' and egy_gyaknap_hanyadik=2 then 'páros hét' else egy_gyaknap_hanyadik end as hanyadik, "; 
$query=$query."T2.tip_nev idonkent, egy_idopont idopont, T3.tip_nev napon, T3.tip_tipus as angol_hetnapja, hely_rnev hol, coalesce(eveg_rnev,'') kivel, "; 
$query=$query."egy_tipus_tip_id, egy_hely_id, egy_tartam, egy_eveg_id, egy_del, egy_gyaknap_hanyadik, egy_gyaknap_tip_id "; 
$query=$query."FROM a_esemeny_gyakorisag GY "; 
$query=$query."    join a_tipusok T1 on T1.tip_id=egy_tipus_tip_id"; 
$query=$query."    join a_tipusok T2 on T2.tip_id=egy_gyak_tip_id"; 
$query=$query."    left join a_tipusok T3 on T3.tip_id=egy_gyaknap_tip_id"; 
$query=$query."    left join a_esemeny_vegzo on egy_eveg_id=eveg_id"; 
$query=$query."    join a_helyszinek H on H.hely_id=egy_hely_id "; 
$query=$query."    order by egy_del, egy_id "; 
$eredm=mysql_query($query);  //query futtatása

$v='</font>';

echo "<table class='naptar'><tr><th></th><th>Szervezõ</th><th>Gyakoriság</th><th>Idõpont</th><th>Mely napokon</th><th>Helyszín</th><th>Jelenlevõ</th><th></th><th><input type='text' id='datumtol' size='10' maxlength='10' value='".$tol."' disabled><input type='text' id='datumig' size='10' maxlength='10' value='".$ig."' disabled></th></tr>";
while($sor=mysql_fetch_array($eredm)) 
	  { if ($sor['egy_del']==0) {$kep='torol'; $szin="<font color='black'>";} else {$kep='beir'; $szin="<font color='gray'>";}
		switch ($sor['egy_id']) {
			case 40 :	$spec='Szent Pió atya tiszteletére'; break;  //hónap 23-a Jáki kápolna
//			case 41 :	$spec='Jézus Szíve tiszteletére'; break;  //Elsõ péntek
			default:	$spec=''; break;
		}
		  echo "<tr><td><a href='admin_esemeny_gyakorisag.php?delid=".$sor['egy_id']."'>";
		  echo "<img src='".$kep.".png' height='20'></a></td><td>".$szin.$sor["ki"]." ".$spec.$v."</td><td>".$szin.$sor["gyak"].' '.$sor["idonkent"].$v."</td><td>".$szin.$sor["idopont"]."- (kb. ".$sor["egy_tartam"]." perc)".$v."</td>";
		  echo "<td>".$szin.$sor["hanyadik"].'. '.$sor["napon"].$v."</td><td>".$szin.$sor["hol"].$v."</td><td>".$szin.$sor["kivel"].$v."</td>";
		  echo "<td><a href='admin_esemeny_gyakorisag.php?modid=".$sor['egy_id']."&esm_ido=".$sor['idopont']."&esm_tip_id=".$sor['egy_tipus_tip_id']."&esm_hely_id=".$sor['egy_hely_id'].
				"&esm_eveg_id=".$sor['egy_eveg_id']."&esm_perc=".$sor['egy_tartam']."&gyakszam=".$sor['gyak']."&gyaktip=".$sor['idonkent']."&hany=".$sor['egy_gyaknap_hanyadik']."&gynap=".$sor['egy_gyaknap_tip_id']."'><img src='mozgat.png' height='20'></a></td>";
		  if ($kep=='torol') {
		  echo "<td><table align='center'><tr><td><a href='admin_esemeny_gyakorisag.php?felid=".$sor['egy_id']."&esm_ido=".$sor['idopont']."&esm_tip_id=".$sor['egy_tipus_tip_id']."&esm_hely_id=".$sor['egy_hely_id'].
				"&esm_eveg_id=".$sor['egy_eveg_id']."&esm_perc=".$sor['egy_tartam']."&gyakszam=".$sor['gyak']."&gyaktip=".$sor['idonkent']."&hany=".$sor['egy_gyaknap_hanyadik']."&angol_hetnapja=".$sor['angol_hetnapja']."&spec=".$spec."'><img src='tolt.png' height='20'></a></td>";  
		  echo "<td><a href='admin_esemeny_gyakorisag.php?delfelid=".$sor['egy_id']."&esm_tip_id=".$sor['egy_tipus_tip_id']."&angol_hetnapja=".$sor['angol_hetnapja']."'><img src='delez.png' height='20'></a></td></tr></table></td></tr>";  }
		  else {		  echo "<td></td></tr>";  }
		  }
	  
echo "</table>";





echo "</body>";
echo "</HTML>";
?>