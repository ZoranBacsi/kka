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
	$szov = str_replace("�", "o", $szov);
	$szov = str_replace("�", "O", $szov);
	$szov = str_replace("�", "u", $szov);
	$szov = str_replace("�", "U", $szov);
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
		case 1: $szov='janu�r'; break;
		case 2: $szov='febru�r'; break;
		case 3: $szov='m�rcius'; break;
		case 4: $szov='�prilis'; break;
		case 5: $szov='m�jus'; break;
		case 6: $szov='j�nius'; break;
		case 7: $szov='j�lius'; break;
		case 8: $szov='augusztus'; break;
		case 9: $szov='szeptember'; break;
		case 10: $szov='okt�ber'; break;
		case 11: $szov='november'; break;
		case 12: $szov='december'; break;
	}
	return $szov;
}


function napok($i)
{
    switch($i) {
		case 1: $szov='vas�rnap'; break;
		case 2: $szov='h�tf�'; break;
		case 3: $szov='kedd'; break;
		case 4: $szov='szerda'; break;
		case 5: $szov='cs�t�rt�k'; break;
		case 6: $szov='p�ntek'; break;
		case 7: $szov='szombat'; break;
	}
	return $szov;
}


function removeaccent($str){
    $search  = array("�", "�", "�", "�", "�", "�", "�", "�", "�", " ");
    $replace = array("a", "e", "i", "o", "o", "o", "u", "u", "u", "_");
    return str_replace($search, $replace, $str);}


?>