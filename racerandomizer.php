<!doctype html>
<html class="no-js" lang="en">
<head>
		<meta charset="UTF-8">
		<title>Stellaris Empire Randomizer</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="css/knacss.css" media="all">
		<link rel="stylesheet" href="style.css" media="all">
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" />
</head>

<body>


	<div id="title">
	<h1 id="intro">Generate a Random Stellaris Empire</h1>
	<form action="racerandomizer.php" method="get">
		<p id="but"><input type ="submit" id="button" name="generate" value="Generate"/></p>	
	</form>
	</div>

<?php
// Connexion à la base de données
try {
	$bdd = new PDO('mysql:host=localhost;dbname=randomizer;charset=utf8', 'root', '');
	array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}


/********************************* TIRAGE DES ETHOS AU HASARD ******************************************/
//3 points d'ethos maximum :
$ethosvalue=0;
$ethosgroup1; //Group de l'ethos en question
$ethosgroup2;
$ethosgroup3;
$ethosnom1; //Nom de l'ethos
$ethosnom2;
$ethosnom3;
$ethosid1; //ID des ethos pour les images
$ethosid2;
$ethosid3;
$ethosmodif1; //Caractéristiques des ethos
$ethosmodif2;
$ethosmodif3;

$test2 = $bdd->query("SELECT * FROM ethos");
$ethostablelength=$test2->rowCount();
while ($ethosvalue < 3 ) {	

	//Si il y a 0 points d'ethos on met le premier
	if ($ethosvalue == 0) {
		//On prend un id de la table au hasard
		$ethosid= rand(1, $ethostablelength);
		//On prend les valeurs correspondantes
		$varethos= $bdd->query("SELECT ethos_id, ethos_group, ethos_type, ethos_value, ethos_name, ethosmodif FROM ethos WHERE id=".$ethosid."");
		$ethos = $varethos->fetch(); 
		$ethosnom1=$ethos['ethos_name']; //Nom de l'ethos
		$ethosgroup1=$ethos['ethos_group']; //Groupe de l'ethos
		$ethosid1=$ethos['ethos_id'];
		$ethosmodif1=$ethos['ethosmodif'];
		$ethosvalue+=$ethos['ethos_value']; //On augmente les points d'ethos
	}

	//Si il y a 1 point d'ethos, on met le second
	if ($ethosvalue == 1) {
		//On prend un id de la table au hasard
		$ethosid= rand(1, $ethostablelength);
		//On prend les valeurs correspondantes
		$varethos= $bdd->query("SELECT ethos_id, ethos_group, ethos_type, ethos_value, ethos_name, ethosmodif FROM ethos WHERE id=".$ethosid."");
		$ethos = $varethos->fetch(); 
			if ($ethos['ethos_group'] != $ethosgroup1){
				$ethosnom2=$ethos['ethos_name']; //Nom de l'ethos
				$ethosgroup2=$ethos['ethos_group']; //Groupe de l'ethos
				$ethosid2=$ethos['ethos_id'];
				$ethosmodif2=$ethos['ethosmodif'];
				$ethosvalue+=$ethos['ethos_value']; //On augmente les points d'ethos
			}
	}


	//Si il y a 2 points d'ethos, on prend un troisième ou un deuxième à valeur 1
	if ($ethosvalue == 2){
		//On prend un id de la table au hasard
		$ethosid= rand(1, $ethostablelength);
		//On prend les valeurs correspondantes
		$varethos= $bdd->query("SELECT ethos_id, ethos_group, ethos_type, ethos_value, ethos_name, ethosmodif FROM ethos WHERE id=".$ethosid."");
		$ethos = $varethos->fetch(); 
		if (isset($ethosgroup2) && $ethos['ethos_group'] != $ethosgroup2 && $ethos['ethos_group'] != $ethosgroup1 && $ethos['ethos_value']==1){ //On ne met l'ethos 3 que si le deuxième est déjà existant
			$ethosnom3=$ethos['ethos_name']; //Nom de l'ethos
			$ethosgroup3=$ethos['ethos_group']; //Groupe de l'ethos
			$ethosid3=$ethos['ethos_id'];
			$ethosmodif3=$ethos['ethosmodif'];
			$ethosvalue+=$ethos['ethos_value']; //On augmente les points d'ethos
		}
		elseif (!isset($ethosgroup2) && $ethos['ethos_group'] != $ethosgroup1 && $ethos['ethos_value']==1){
			$ethosnom2=$ethos['ethos_name']; //Nom de l'ethos
			$ethosgroup2=$ethos['ethos_group']; //Groupe de l'ethos
			$ethosid2=$ethos['ethos_id'];
			$ethosmodif2=$ethos['ethosmodif'];
			$ethosvalue+=$ethos['ethos_value']; //On augmente les points d'ethoss
		}
	}

}

/**************************************** TIRAGE DU TYPE DE GOUVERNEMENT AU HASARD ***************************************************/
$govtest=0; //Tant qu'un gouvernement n'a pas été assigné on laisse la variable à 0
$govname;
$govtype;
$govnature;
$govimage;
$govmodif;

$inutile=0;
if (isset($ethosnom3)){
	$inutile++;
}
else {
	$ethosid3="rien";
}

while ($govtest==0){

	//On choisit un gouvernement au hasard dans la liste :
	$test2 = $bdd->query("SELECT * FROM government");
	$govtablelength=$test2->rowCount();
	$govid= rand(1, $govtablelength);
	$vargov= $bdd->query("SELECT id, gov_id, govtype, govnature, govname, govmodif FROM government WHERE id=".$govid."");
	$govinfos = $vargov->fetch();
	//Pour chaque Gouvernement différent on vérifie si les conditions sont réunies (ouais c'est ptetre pas opti mais j'avais pas d'autre idée)
	//DESPOTIC EMPIRE :
	if ($govinfos['id']==1 AND $ethosid1!="Individualist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Individualist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Individualist" AND $ethosid3 !="Fanatic_Individualist") {
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}




	//DESPOTIC HEGEMONY :
	if ($govinfos['id']==2 AND $ethosid1!="Individualist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Individualist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Individualist" AND $ethosid3!="Fanatic_Individualist" ){
		if ($ethosid1=="Materialist" || $ethosid1=="Fanatic_Materialist" || $ethosid2=="Materialist" || $ethosid2=="Fanatic_Materialist" || $ethosid3=="Materialist" || $ethosid3=="Fanatic_Materialist"){
			$govname=$govinfos['govname'];
			$govtype=$govinfos['govtype'];
			$govnature=$govinfos['govnature'];
			$govimage=$govinfos['gov_id'];
			$govmodif=$govinfos['govmodif'];
			$govtest++;
	}}


	//DIRECT DEMOCRACY
	if ($govinfos['id']==3 AND $ethosid1!="Collectivist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Collectivist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Collectivist" AND $ethosid3!="Fanatic_Collectivist") {
		if ($ethosid1=="Materialist" || $ethosid1=="Fanatic_Materialist" || $ethosid2=="Materialist" || $ethosid2=="Fanatic_Materialist" || $ethosid3=="Materialist" || $ethosid3=="Fanatic_Materialist"){
			$govname=$govinfos['govname'];
			$govtype=$govinfos['govtype'];
			$govnature=$govinfos['govnature'];
			$govimage=$govinfos['gov_id'];
			$govmodif=$govinfos['govmodif'];
			$govtest++;
	}}

	//DIVINE MANDATE
	if ($govinfos['id']==4 AND $ethosid1!="Individualist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Individualist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Individualist" AND $ethosid3!="Fanatic_Individualist") {
		if ($ethosid1=="Spiritualist" || $ethosid1=="Fanatic_Spiritualist" || $ethosid2=="Spiritualist" || $ethosid2=="Fanatic_Spiritualist" || $ethosid3=="Spiritualist" || $ethosid3=="Fanatic_Spiritualist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//ENLIGHTENED MONARCHY
	if ($govinfos['id']==5 AND $ethosid1!="Individualist" AND $ethosid1 !="Fanatic_Individualist" AND $ethosid2!="Individualist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Individualist" AND $ethosid3!="Fanatic_Individualist"){
		if ( $ethosid1=="Pacifist" || $ethosid1=="Fanatic_Pacifist" || $ethosid2=="Pacifist" || $ethosid2=="Fanatic_Pacifist" || $ethosid3=="Pacifist" || $ethosid3=="Fanatic_Pacifist"){
			$govname=$govinfos['govname'];
			$govtype=$govinfos['govtype'];
			$govnature=$govinfos['govnature'];
			$govimage=$govinfos['gov_id'];
			$govmodif=$govinfos['govmodif'];
			$govtest++;
	}}

	//INDIRECT DEMOCRACY
	if ($govinfos['id']==6 AND $ethosid1!="Collectivist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Collectivist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Collectivist" AND $ethosid3 !="Fanatic_Collectivist") {
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}

	//MILITARY DICTATORSHIP
	if ($govinfos['id']==7 AND $ethosid1!="Individualist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Individualist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Individualist" AND $ethosid3!="Fanatic_Individualist"){
		if ($ethosid1=="Militarist" || $ethosid1=="Fanatic_Militarist" || $ethosid2=="Militarist" || $ethosid2=="Fanatic_Militarist" || $ethosid3=="Militarist" || $ethosid3=="Fanatic_Militarist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//MILITARY JUNTA
	if ($govinfos['id']==8 AND $ethosid1!="Fanatic_Collectivist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Fanatic_Collectivist" AND $ethosid3!="Fanatic_Individualist") {
		if ($ethosid1=="Militarist" || $ethosid1=="Fanatic_Militarist" || $ethosid2=="Militarist" || $ethosid2=="Fanatic_Militarist" || $ethosid3=="Militarist" || $ethosid3=="Fanatic_Militarist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//MILITARY REPUBLIC
	if ($govinfos['id']==9 AND $ethosid1!="Collectivist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Collectivist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Collectivist" AND $ethosid3!="Fanatic_Collectivist"){
		if ($ethosid1=="Militarist" || $ethosid1=="Fanatic_Militarist" || $ethosid2=="Militarist" || $ethosid2=="Fanatic_Militarist" || $ethosid3=="Militarist" || $ethosid3=="Fanatic_Militarist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//MORAL DEMOCRACY
	if ($govinfos['id']==10 AND $ethosid1!="Collectivist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Collectivist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Collectivist" AND $ethosid3!="Fanatic_Collectivist") {
		if ($ethosid1=="Pacifist" || $ethosid1=="Fanatic_Pacifist" || $ethosid2=="Pacifist" || $ethosid2=="Fanatic_Pacifist" || $ethosid3=="Pacifist" || $ethosid3=="Fanatic_Pacifist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//PEACEFUL BUREAUCRACY
	if ($govinfos['id']==11 AND $ethosid1!="Fanatic_Individualist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Fanatic_Individualist" AND $ethosid3!="Fanatic_Collectivist"){
		if ($ethosid1=="Pacifist" || $ethosid1=="Fanatic_Pacifist" || $ethosid2=="Pacifist" || $ethosid2=="Fanatic_Pacifist" || $ethosid3=="Pacifist" || $ethosid3=="Fanatic_Pacifist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//PLUTOCRATIC OLIGARCHY
	if ($govinfos['id']==12 AND $ethosid1!="Fanatic_Collectivist" AND $ethosid1!="Fanatic_Individualist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid3 !="Fanatic_Collectivist" AND $ethosid3 !="Fanatic_Individualist") {
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}

	//SCIENCE DIRECTORATE
	if ($govinfos['id']==13 AND $ethosid1!="Fanatic_Individualist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Fanatic_Individualist" AND $ethosid3!="Fanatic_Collectivist"){
		if ($ethosid1=="Materialist" || $ethosid1=="Fanatic_Materialist" || $ethosid2=="Materialist" || $ethosid2=="Fanatic_Materialist" || $ethosid3=="Materialist" || $ethosid3=="Fanatic_Materialist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//THEOCRATIC REPUBLIC
	if ($govinfos['id']==14 AND $ethosid1!="Collectivist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Collectivist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Collectivist" AND $ethosid3!="Fanatic_Collectivist") {
		if ($ethosid1=="Spiritualist" || $ethosid1=="Fanatic_Spiritualist" || $ethosid2=="Spiritualist" || $ethosid2=="Fanatic_Spiritualist" || $ethosid3=="Spiritualist" || $ethosid3=="Fanatic_Spiritualist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

	//THEOCRATIC OLIGARCHY
	if ($govinfos['id']==15 AND $ethosid1!="Fanatic_Individualist" AND $ethosid1!="Fanatic_Collectivist" AND $ethosid2!="Fanatic_Individualist" AND $ethosid2!="Fanatic_Collectivist" AND $ethosid3 !="Fanatic_Individualist" AND $ethosid3!="Fanatic_Collectivist") {
		if ($ethosid1=="Spiritualist" || $ethosid1=="Fanatic_Spiritualist" || $ethosid2=="Spiritualist" || $ethosid2=="Fanatic_Spiritualist" || $ethosid3=="Spiritualist" || $ethosid3=="Fanatic_Spiritualist"){
		$govname=$govinfos['govname'];
		$govtype=$govinfos['govtype'];
		$govnature=$govinfos['govnature'];
		$govimage=$govinfos['gov_id'];
		$govmodif=$govinfos['govmodif'];
		$govtest++;
	}}

}

/******************TIRER UN NOM DE RACE AU HASARD ****************/ 

//Sélectionner un nombre au hasard dans la table des noms de races :
//On prend un nombre au hasard
$test1 = $bdd->query("SELECT * FROM racename");
$racetablelength = $test1->rowCount();

$raceid= rand(1, $racetablelength);

//On prend le nom dans la table et on l'affecte
$varrace= $bdd->query('SELECT name FROM racename WHERE id='.$raceid.'');
$nomrace = $varrace->fetch();
$racename=$nomrace['name']; //Nom de la race


/********************* TIRER LE NOM DU GOUVERNEMENT *******************/

$statecount=0; //On cherche tant que la valeur n'est pas à 1
$statename;
while ($statecount!=1){
	$test1 = $bdd->query("SELECT * FROM factionname");
	$factiontablelength=$test1->rowCount();
	$stateid=rand(1, $factiontablelength);

	//On prend le nom dans la table et on l'affecte si il remplit les critères
	$varrace= $bdd->query('SELECT name, type, nature FROM factionname WHERE id='.$stateid.'');
	$nomstate = $varrace->fetch();
	if ($nomstate['type']==$govtype || $nomstate['type']=="global" AND $nomstate['nature']==$govnature || $nomstate['nature']=="global"){
		$statename=$nomstate['name']; //Nom de la faction
		$statecount++;
	}
}



/**************** TIRAGE DES TRAITS ******************************/
//Déclaration des variables
$trait1;
$trait2;
$trait3;
$trait4;
$modiftrait1;
$modiftrait2;
$modiftrait3;
$modiftrait4;
$traitid1;
$traitid2;
$traitid3;
$traitid4;
$traitcategory1;
$traitcategory2;
$traitcategory3;
$traitcategory4;


$traitnumber=rand(1,26); //Ici on Prend un chiffre au hasard pour déterminer le nombre de traits

/************************************************************************/
if (1<=$traitnumber AND $traitnumber<=4) { //Un seul trait valeur 2
$onetrait=0;
	while ($onetrait<1) {
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($vartrait2['trait_points'] == 0+2 && $vartrait2['virtue']=="positive"){
				$trait1=$vartrait2['traitname'];
				$traitid1=$vartrait2['trait_id'];
				$traitcategory1=$vartrait2['category'];
				$modiftrait1=$vartrait2['modif'];
				$onetrait++;
			}}
}
/************************************************************************/
if (5<=$traitnumber AND $traitnumber<=9) { //Deux traits valeur 1
$twotraits=0;
	while($twotraits<2){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($twotraits==0 && $vartrait2['trait_points'] == 0+1 && $vartrait2['virtue']=="positive" ){
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$twotraits++;
		}
		elseif (isset($trait1) && $vartrait2['trait_points'] == 0+1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="positive"){
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$twotraits++;
		}
	}
}
/************************************************************************/
if (10<=$traitnumber AND $traitnumber<=12) { //Un trait valeur 4 et deux traits valeur -1
$threetraits=0;
	while($threetraits<3){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($threetraits==0 && $vartrait2['trait_points'] == 0+4){ // On met un trait à 4 de valeur
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$threetraits++;
		}
		elseif (isset($trait1) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="negative" && $threetraits==1){ // On met un trait à -1 de valeur
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$threetraits++;
		}

		elseif (isset($trait2) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['virtue']=="negative"){ // Un deuxième
			$trait3=$vartrait2['traitname'];
			$traitid3=$vartrait2['trait_id'];
			$traitcategory3=$vartrait2['category'];
			$modiftrait3=$vartrait2['modif'];
			$threetraits++;
		}


	}
}
/************************************************************************/
if (12<=$traitnumber AND $traitnumber<=14) { //Deux traits valeur 2 et deux traits valeur -1
$fourtraits=0;
	while($fourtraits<4){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($fourtraits==0 && $vartrait2['trait_points'] == 0+2 && $vartrait2['virtue']=="positive"){ // On met un trait à 2 de valeur
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$fourtraits++;
		}

		elseif (isset($trait1) && $vartrait2['trait_points'] == 0+2 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="positive" && $fourtraits==1){ // On met un trait à 2 de valeur
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$fourtraits++;
		}

		elseif (isset($trait2) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['virtue']=="negative" && $fourtraits==2){ // Un premier chiffre à valeur -1
			$trait3=$vartrait2['traitname'];
			$traitid3=$vartrait2['trait_id'];
			$traitcategory3=$vartrait2['category'];
			$modiftrait3=$vartrait2['modif'];
			$fourtraits++;
		}

		elseif (isset($trait3) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['category'] != $traitcategory3 && $vartrait2['virtue']=="negative" && $fourtraits==3){ // Un premier chiffre à valeur -1
			$trait4=$vartrait2['traitname'];
			$traitid4=$vartrait2['trait_id'];
			$traitcategory4=$vartrait2['category'];
			$modiftrait4=$vartrait2['modif'];
			$fourtraits++;
		}

}}

if (14<=$traitnumber AND $traitnumber<=16) { //Un trait valeur 5 et trois traits valeur -1
$fourtraitsbis=0;
	while ($fourtraitsbis<4){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		
		if ($fourtraitsbis==0 && $vartrait2['trait_points'] == 0+5 && $vartrait2['virtue']=="positive"){ // On met un trait à 5 de valeur
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$fourtraitsbis++;
		}

		elseif (isset($trait1) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="negative" && $fourtraitsbis==1){ // On met un trait à -1 de valeur
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$fourtraitsbis++;
		}

		elseif (isset($trait2) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['virtue']=="negative" && $fourtraitsbis==2){ // Un second chiffre à valeur -1
			$trait3=$vartrait2['traitname'];
			$traitid3=$vartrait2['trait_id'];
			$traitcategory3=$vartrait2['category'];
			$modiftrait3=$vartrait2['modif'];
			$fourtraitsbis++;
		}

		elseif (isset($trait3) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['category'] != $traitcategory3 && $vartrait2['virtue']=="negative" && $fourtraitsbis==3){ // Un troisième chiffre à valeur -1
			$trait4=$vartrait2['traitname'];
			$traitid4=$vartrait2['trait_id'];
			$traitcategory4=$vartrait2['category'];
			$modiftrait4=$vartrait2['modif'];
			$fourtraitsbis++;
		}

	}}

if (17<=$traitnumber AND $traitnumber<=21) { //Un traits +2, un trait +1 et un trait -1
$fivetraits=0;
	while ($fivetraits<3){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($fivetraits==0 && $vartrait2['trait_points'] == 0+2 && $vartrait2['virtue']=="positive"){ // On met un trait à 2 de valeur
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$fivetraits++;
		}

		elseif (isset($trait1) && $vartrait2['trait_points'] == 0+1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="positive" && $fivetraits==1){ // On met un trait à +1 de valeur
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$fivetraits++;
		}

		elseif (isset($trait2) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['virtue']=="negative"){ // -1 de valeur
			$trait3=$vartrait2['traitname'];
			$traitid3=$vartrait2['trait_id'];
			$traitcategory3=$vartrait2['category'];
			$modiftrait3=$vartrait2['modif'];
			$fivetraits++;
		}
	}}

if (22<=$traitnumber AND $traitnumber<=26) { //Trois traits +1 et un trait -1
$sixtraits=0;
	while ($sixtraits<4){
		$test1 = $bdd->query("SELECT * FROM xenotraits");
		$traittablelength=$test1->rowCount();
		$traitid=rand(1, $traittablelength);
		$traitbdd=$bdd->query('SELECT trait_id, trait_points, traitname, virtue, category, modif FROM xenotraits WHERE id='.$traitid.'');
		$vartrait2=$traitbdd->fetch();
		if ($sixtraits==0 && $vartrait2['trait_points'] == 0+1 && $vartrait2['virtue']=="positive"){ // On met un trait à +1 de valeur
			$trait1=$vartrait2['traitname'];
			$traitid1=$vartrait2['trait_id'];
			$traitcategory1=$vartrait2['category'];
			$modiftrait1=$vartrait2['modif'];
			$sixtraits++;
		}

		elseif (isset($trait1) && $vartrait2['trait_points'] == 0+1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['virtue']=="positive" && $sixtraits==1){ // On met un trait à +1 de valeur
			$trait2=$vartrait2['traitname'];
			$traitid2=$vartrait2['trait_id'];
			$traitcategory2=$vartrait2['category'];
			$modiftrait2=$vartrait2['modif'];
			$sixtraits++;
		}

		elseif (isset($trait2) && $vartrait2['trait_points'] == 0+1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['virtue']=="positive"  && $sixtraits==2){ // +1 de valeur
			$trait3=$vartrait2['traitname'];
			$traitid3=$vartrait2['trait_id'];
			$traitcategory3=$vartrait2['category'];
			$modiftrait3=$vartrait2['modif'];
			$sixtraits++;
		}

		elseif (isset($trait3) && $vartrait2['trait_points'] == 0-1 && $vartrait2['category'] != $traitcategory1 && $vartrait2['category'] != $traitcategory2 && $vartrait2['category'] != $traitcategory3 && $vartrait2['virtue']=="negative" && $sixtraits==3){ // Un troisième chiffre à valeur -1
			$trait4=$vartrait2['traitname'];
			$traitid4=$vartrait2['trait_id'];
			$traitcategory4=$vartrait2['category'];
			$modiftrait4=$vartrait2['modif'];
			$sixtraits++;
		}
}}


/****************** SELECTION DU PORTRAIT DE RACE AU HASARD ***************************/
$portraitid=rand(1,60); //Une chance sur 6 pour chaque type d'espèce
$specieimage;
$specietype;
//Arthropoid:
if(1 <= $portraitid && $portraitid <=10){
	$specietype="Arthropoid";
	$specieid=rand(1,14);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

//Avian:
if(11 <= $portraitid && $portraitid <=20){
	$specietype="Avian";
	$specieid=rand(1,15);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

//Fungoid:
if(21 <= $portraitid && $portraitid <=30){
	$specietype="Fungoid";
	$specieid=rand(1,14);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

//Mammalian:
if(31 <= $portraitid && $portraitid <=40){
	$specietype="Mammalian";
	$specieid=rand(1,17);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

//Molluscoid:
if(41 <= $portraitid && $portraitid <=50){
	$specietype="Molluscoid";
	$specieid=rand(1,13);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

//Reptilian:
if(51 <= $portraitid && $portraitid <=60){
	$specietype="Reptilian";
	$specieid=rand(1,15);
		if ($specieid<10) {
			$specieimage=$specietype."_0".$specieid;
		}
		else {
			$specieimage=$specietype."_".$specieid;
		}
}

/************CHOIX DU MONDE NATAL*******************/
$test1 = $bdd->query("SELECT * FROM planets");
$planettablelength=$test1->rowCount();
$planetid=rand(1, $planettablelength);
$planetbdd=$bdd->query('SELECT planettype FROM planets WHERE id='.$planetid.'');
$planetchoice=$planetbdd->fetch();

$homeworld=$planetchoice['planettype'];


/*********** CHOIX TECHNOLOGIE FTL ***************/
$test1 = $bdd->query("SELECT * FROM ftltech");
$ftltablelength=$test1->rowCount();
$ftlid=rand(1, $ftltablelength);
$ftlbdd=$bdd->query('SELECT name, pic FROM ftltech WHERE id='.$ftlid.'');
$ftlchoice=$ftlbdd->fetch();

$ftlname=$ftlchoice['name'];
$ftlpix=$ftlchoice['pic'];


/*********** CHOIX TECHNOLOGIE ARMEMENT ***************/
$test1 = $bdd->query("SELECT * FROM weapontech");
$weapontablelength=$test1->rowCount();
$weaponid=rand(1, $weapontablelength);
$weaponbdd=$bdd->query('SELECT techid, techname FROM weapontech WHERE id='.$weaponid.'');
$weaponchoice=$weaponbdd->fetch();

$weaponname=$weaponchoice['techname'];
$weaponpix=$weaponchoice['techid'];

////////////////////////////////////////////////////////////
/*******************VARIABLES A AFFICHER*******************/
$empirename=$racename." ".$statename;
//Nom complet de la faction : echo $empirename


/***** ETHOS *****/
/*
$ethosgroup1; //Group de l'ethos en question
$ethosgroup2;
$ethosgroup3;
$ethosnom1; //Nom de l'ethos
$ethosnom2;
$ethosnom3;
$ethosid1; //ID des ethos pour les images
$ethosid2;
$ethosid3;
$ethosmodif1; //Caractéristiques des ethos
$ethosmodif2;
$ethosmodif3;
*/


/**** GOUVERNEMENT ****/
/*
$govname;
$govtype;
$govnature;
$govimage;
$govmodif;
*/

/***** TRAITS ****/
/*
$trait1;
$trait2;
$trait3;
$trait4;
$modiftrait1;
$modiftrait2;
$modiftrait3;
$modiftrait4;
$traitid1;
$traitid2;
$traitid3;
$traitid4;
$traitcategory1;
$traitcategory2;
$traitcategory3;
$traitcategory4;

echo $trait1;
if (isset($trait2)){echo ", ".$trait2;}
if (isset($trait3)){echo ", ".$trait3;}
if (isset($trait4)){echo ", ".$trait4;}
*/

/***PORTRAIT IMAGE****/

/*
echo '<img src="img-stellaris/species/'.$specieimage.'.png" alt="" />';
$specieimage;
$specietype;
*/

/***** PLANET TYPE
echo $homeworld;
echo '<img src="img-stellaris/planets/'.$homeworld.'.png" alt="" />';
*/


/***** FTL TYPE
echo $ftlname;
echo '<img src="img-stellaris/ftl/'.$ftlpix.'" alt="" />';
*/

/**** WEAPON TYPE
echo $weaponname;
echo '<img src="img-stellaris/weapons/'.$weaponpix.'.png" alt="" />';
*/




?>


<div id="content">
<?php echo '<img id="speciepix" src="img-stellaris/species/'.$specieimage.'.png" alt="" />'; ?>
<h1 class="id" id="empirename"><?php echo $empirename; ?></h1>
<p id='speciegroup'>Specie's group : <?php echo $specietype; ?></p>
<?php echo '<img id="govpix" src="img-stellaris/gov/'.$govimage.'.png" alt="" />'; ?>
<h2 class="id" id="govname"><?php echo $govname; ?></h2>
<p class="id" id="govmodifiers"><?php echo $govmodif; ?></p>

<h3 class="id" id="ethoslist">Ethos :</h3>
	<ul id="listethos">
		<li class="ethos_list"><?php echo '<img class="ethospix" src="img-stellaris/ethos/'.$ethosid1.'.png" alt="" />'." <b>".$ethosnom1."</b> : <br />".$ethosmodif1;  ?></li>
		<li class="ethos_list"><?php echo '<img class="ethospix" src="img-stellaris/ethos/'.$ethosid2.'.png" alt="" />'." <b>".$ethosnom2."</b> : <br />".$ethosmodif2;  ?></li>
		<?php if(isset($ethosnom3)){  echo '<li class="ethos_list"> <img class="ethospix" src="img-stellaris/ethos/'.$ethosid3.'.png" alt="" />'." <b>".$ethosnom3."</b> : <br />".$ethosmodif3."</li>";  } ?>
	</ul>

<h3 class="id" id="traitlist">Traits :</h3>
	<ul id="listetrait">
		<li><?php echo '<img class="ethospix" src="img-stellaris/traits/'.$traitid1.'.png" alt="" />'." <b>".$trait1."</b> : ".$modiftrait1;  ?></li>
		<?php if(isset($trait2)){  echo '<li> <img class="ethospix" src="img-stellaris/traits/'.$traitid2.'.png" alt="" />'." <b>".$trait2."</b> : ".$modiftrait2."</li>";  } ?>
		<?php if(isset($trait3)){  echo '<li> <img class="ethospix" src="img-stellaris/traits/'.$traitid3.'.png" alt="" />'." <b>".$trait3."</b> : ".$modiftrait3."</li>";  } ?>
		<?php if(isset($trait4)){  echo '<li> <img class="ethospix" src="img-stellaris/traits/'.$traitid4.'.png" alt="" />'." <b>".$trait4."</b> : ".$modiftrait4."</li>";  } ?>
	</ul>
</div>

<div id="contentbis">
 <div id="box1">
	<p id="homew"><?php echo "Home world category : ".$homeworld; ?></p>
	<?php echo '<img id="planet" src="img-stellaris/planets/'.$homeworld.'.png" alt="" />' ;?>
 </div>

 <div id="box2">
 	<p id="ftltype"><?php echo "FTL Technology : ".$ftlname; ?></p>
 	<?php echo '<img id="ftlpix" src="img-stellaris/ftl/'.$ftlpix.'" alt="" />'; ?>
 </div>

 <div id="box3">
 <p id="ftltype"><?php echo "Weapon Technology : ".$weaponname; ?></p>
 <?php echo '<img src="img-stellaris/weapons/'.$weaponpix.'.png" alt="" />'; ?>
 </div>
</div>

</body>
</html>