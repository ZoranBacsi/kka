<?php
function ures($mezo)
{
 	$ures=false;
	if($mezo=="")
	{
		$ures=true;
	}
	return $ures;
}

function pipa($mezo)
{
 	$ertek='N';
	if($mezo=="I")
	{
		$ertek='I';
	}
	return $ertek;
}


function nulloz($mezo)
{
	if (($mezo=="")  || ($mezo=="0000-00-00"))   //   || ($mezo=="0") || ($mezo=="-") 
	{	$ures='NULL';			}
	else 
	{ 	$ures="'".$mezo."'";	}
	return $ures;
}


function ekezetcsere($szov)
{
	$szov = str_replace("õ", "o", $szov);
	$szov = str_replace("Õ", "O", $szov);
	$szov = str_replace("û", "u", $szov);
	$szov = str_replace("Û", "U", $szov);
	return $szov;
}

function nosqlinjekt($szov)
{
//	$szov = str_replace("""", "'", $szov, $count);
	$szov = str_replace("`", "'", $szov, $count);
	while (stripos($szov,"''") !== false) {	$szov = str_replace("''", "'", $szov, $count);}
	$szov = str_replace("'", "''", $szov, $count);
	$szov = "'".$szov."'";
	return $szov;
}


function honapok($i)
{
    switch($i) {
		case 1: $szov='január'; break;
		case 2: $szov='február'; break;
		case 3: $szov='március'; break;
		case 4: $szov='április'; break;
		case 5: $szov='május'; break;
		case 6: $szov='június'; break;
		case 7: $szov='július'; break;
		case 8: $szov='augusztus'; break;
		case 9: $szov='szeptember'; break;
		case 10: $szov='október'; break;
		case 11: $szov='november'; break;
		case 12: $szov='december'; break;
	}
	return $szov;
}


function napok($i)
{
    switch($i) {
		case 1: $szov='vasárnap'; break;
		case 2: $szov='hétfõ'; break;
		case 3: $szov='kedd'; break;
		case 4: $szov='szerda'; break;
		case 5: $szov='csütörtök'; break;
		case 6: $szov='péntek'; break;
		case 7: $szov='szombat'; break;
	}
	return $szov;
}


function removeaccent($str){
    $search  = array("á", "é", "í", "ó", "ö", "õ", "ú", "ü", "û", " ");
    $replace = array("a", "e", "i", "o", "o", "o", "u", "u", "u", "_");
    return str_replace($search, $replace, $str);}


?>