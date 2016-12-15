<?php

include "utils.php";

//Klasse erbt von utils (Logik)
class views extends utils{
	
	//Print den Head und den Anfang des Bodys (Menubar) der HTML
	function views_head(){

	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">

		<head>

			<title>App2night Adminpanel</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
			<style>
				.alert {
					padding: 20px;
					background-color: #f44336;
					color: white;
					opacity: 1;
					transition: opacity 0.6s;
					margin-bottom: 15px;
				}

				.alert.success {background-color: #4CAF50;}
				.alert.info {background-color: #2196F3;}
				.alert.warning {background-color: #ff9800;}

				.closebtn {
					margin-left: 15px;
					color: white;
					font-weight: bold;
					float: right;
					font-size: 22px;
					line-height: 20px;
					cursor: pointer;
					transition: 0.3s;
				}

				.closebtn:hover {
					color: black;
				}
</style>
		</head>
		<body>
		
		<?php	
		//Menübar wird bei Login (default) nicht geprintet
		if($_SESSION['type']) { ?>
				
		<nav class="navbar navbar-default">
		<div class="container-fluid">
		    <div class="navbar-header">
				<a class="navbar-brand" href="#">
					<img alt="Admin Panel" src="/img/a2n_app_color_20.png">
				</a>
			</div>
			<form class="navbar-form navbar-left" action="index.php" method="POST">
				<input type="submit" class='btn btn-info' name='partysview' value="Party Liste">
				<input type="submit" class='btn btn-info' name='partysallview' value="Party Liste (Alle)">
				<input type="submit" class='btn btn-info' name="partycreateview" value="Party anlegen">
				<input type="submit" class='btn btn-info' name="usersview" value="User Liste">
				<input type="submit" class='btn btn-info' name="usercreateview" value="User anlegen">
			</form>
		</div>
		</nav>
		
		<?php
		}
	}

	//Print Footer der HTML
	function views_footer(){

	?>
	
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		</body>
	</html>

	<?php
	}

	//Print die Loginseite
	function views_login(){

	?>
	<br/>
	<br/>
	<br/>
	<div clas="container">
		<img alt="" src="/img/a2n_app_color_300.png" class="img-responsive center-block">
	<div class="col-md-12">
		<div class="modal-dialog" style="margin-bottom:0">
        <div class="modal-content">
        <div class="panel-heading">
			<h3 class="panel-title">Login</h3>
        </div>
        <div class="panel-body">
			<form role="form" name="login" action="index.php" method="POST">
				<fieldset>
					<div  class="form-group">
						<input class="form-control" placeholder="Username" name="username" type="text" autofocus="">
					</div>
					<div class="form-group">
						<input class="form-control" placeholder="Password" name="password" type="password">
					</div>
					<input class="btn btn-sm btn-info" type="submit" value="&nbsp;&nbsp;Login&nbsp;&nbsp;" name="logbutton"/>
				</fieldset>
			</form>
		</div>
		</div>
		</div>
		</div>
		</div>

	<?php
	}

	//Print Party Tabelle
	function views_partys_view(){
	
	//Success Alert, wenn Party erfolgreich bearbeitet
	if($_SESSION['alert'] == "suc_edit"){
		
	?>
	<div class="alert success">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	Party erfolgreich bearbeitet.
	</div>
	
	<?php }
	
	//Success Alert wenn Party erfolgreich gelöscht
	if($_SESSION['alert'] == "suc_delete"){
	
	?>
	<div class="alert success">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	Party erfolgreich gelöscht.
	</div>
	
	<?php }
	
	//Success Alert wenn Party erfolgreich erstellt
	if($_SESSION['alert'] == "suc_create"){
		
	?>
	<div class="alert success">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	Party erfolgreich erstellt.
	</div>
	
	<?php } ?>
	
	<!-- Erstelle Headline mit festen Werten -->
	<div class="container-fluid">
	<form action="index.php" method="POST">
	<table class="table table-bordered">
		<thead class="thead-default">
			<tr>
				<th>Party ID</th>
				<th>Name</th>
				<th>Datum</th>
				<th>Beschreibung</th>
				<th>Preis</th>
				<th>Up-Votings</th>
				<th>Down-Votings</th>
				<th>Musikgenre</th>
				<th>Partytyp</th>
				<th>Location</th>
				<th>Host</th>
			</tr>
		</thead>
		<tbody>
	
	<?php
	
	
	$today = date("Y-m-d");
	
	//iteriere über Array mit Parties
	foreach($_SESSION['pw'] as $keys => $partyobject)
	{
		$pdate = substr($partyobject['PartyDate'],0,10);
		
		$pdate_date = date("Y-m-d", strtotime($pdate));
		
		//erstelle Zeile für jeden key
		//färbe Datensatz nach Datum
		if($pdate_date < $today)
		{
			echo "<tr class=\"info\">";
		} else if($pdate_date == $today){
			echo "<tr class=\"success\">";
		}
		else {
			echo "<tr>";
		}
		
		//iteriere über Partyarray
		foreach($partyobject as $inner_key => $wert)
		{
			//erstelle eine Spalte mit den Werten der Party für jeden inner_key
			echo "<td>$wert</td>";
		if($inner_key == "PartyId") $id = $wert;
		}
		
		//erstelle "Bearbeiten" und "Löschen" Button in jeder Zeile
		echo "<td><button type='submit' class='btn btn-info' name='partyeditview' value='$id'>Bearbeiten</button></td>";
		echo "<td><button type='submit' class='btn btn-danger' name='partydelete' value='$id'>Löschen</button></td>";
		echo "</tr>";
	}
	
	echo "</tbody>";
	echo "</table></form>";
	echo "</div>";
}

//Print Bearbeiten Formular
function views_party_edit(){
	
	//erstelle Formular mit den Werten der aufgewählten Party 
	//Session Variable "pe" wird von Funktion partyeditview() geliefert
	?>
	<div class="container-fluid">
	<div class="row">
	<div class="col-md-6">
	<h1>Party "<?php echo $_SESSION['pe']['PartyName']; ?>" ändern</h1>
	<br/>
	<form name="partyedit" action="index.php" method="POST">
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Party Name</label>
			<div class="col-xs-10">
				<input class="form-control" name="pname" type="text" value="<?php echo $_SESSION['pe']['PartyName']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Party Datum</label>
			<div class="col-xs-10">
				<input class="form-control" name="pdate" type="date" value="<?php echo $_SESSION['pe']['PartyDate']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Land</label>
			<div class="col-xs-10">
				<input class="form-control" name="pcountry" type="text" value="<?php echo $_SESSION['pe']['Location']['CountryName']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Stadt</label>
			<div class="col-xs-10">
				<input class="form-control" name="pcity" type="text" value="<?php echo $_SESSION['pe']['Location']['CityName']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Postleitzahl</label>
			<div class="col-xs-10">
				<input class="form-control" name="pzip" type="text" value="<?php echo $_SESSION['pe']['Location']['Zipcode']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Straße</label>
			<div class="col-xs-10">
				<input class="form-control" name="pstreet" type="text" value="<?php echo $_SESSION['pe']['Location']['StreetName']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Hausnummer</label>
			<div class="col-xs-10">
				<input class="form-control" name="phnumber" type="text" value="<?php echo $_SESSION['pe']['Location']['HouseNumber']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Musik Genre</label>
			<div class="col-xs-10">
				<select class="form-control" name="pgenre">
					<option value="0" <?php if($_SESSION['pe']['MusicGenre'] == 0) echo "selected"; ?>>Alle</option>
					<option value="1" <?php if($_SESSION['pe']['MusicGenre'] == 1) echo "selected"; ?>>Rock</option>
					<option value="2" <?php if($_SESSION['pe']['MusicGenre'] == 2) echo "selected"; ?>>Pop</option>
					<option value="3" <?php if($_SESSION['pe']['MusicGenre'] == 3) echo "selected"; ?>>HipHop</option>
					<option value="4" <?php if($_SESSION['pe']['MusicGenre'] == 4) echo "selected"; ?>>Rap</option>
					<option value="5" <?php if($_SESSION['pe']['MusicGenre'] == 5) echo "selected"; ?>>Elektro</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Party Typ</label>
			<div class="col-xs-10">
				<select class="form-control" name="ptype">
					<option value="0" <?php if($_SESSION['pe']['PartyType'] == 0) echo "selected"; ?>>Bar</option>
					<option value="1" <?php if($_SESSION['pe']['PartyType'] == 1) echo "selected"; ?>>Disco</option>
					<option value="2" <?php if($_SESSION['pe']['PartyType'] == 2) echo "selected"; ?>>Wald</option>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Preis</label>
			<div class="col-xs-10">
				<input class="form-control" name="pprice" type="text" value="<?php echo $_SESSION['pe']['Price']; ?>">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-xs-2 col-form-label">Description</label>
			<div class="col-xs-10">
				<input class="form-control" name="pdesc" type="text" value="<?php echo $_SESSION['pe']['Description']; ?>">
			</div>
		</div>
	<button type='submit' class="btn btn-success" name='partyedit' value='<?php echo $_SESSION['pe']['PartyId']; ?>'>Speichern</button> <button type='submit' class="btn btn-info" name='editcancel' value='$id'>Abbrechen</button>

	</form>
	</div>
	</div>
	</div>
	<?php
	}

//erstelle Formular zur Erstellung einer Party
function views_party_create(){
	
	//Error Alert wenn die Party nicht erstellt werden konnte
	if($_SESSION['alert'] == "fail"){
	?>
	<div class="alert">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	Etwas ist schlief gelaufen, bitte überprüfen Sie Ihre Eingabe.
	</div>
	<?php } ?>
	
	<div class="container-fluid">
	<div class="row">
	<div class="col-md-6">
	<h1>Party anlegen</h1>
	<br/>
	<form action="index.php" method="POST">
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Party Name</label>
		<div class="col-xs-10">
			<input class="form-control" name="pname" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Party Datum</label>
		<div class="col-xs-10">
			<input class="form-control" name="pdate" type="date" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Land</label>
		<div class="col-xs-10">
			<input class="form-control" name="pcountry" type="text" value="Deutschland">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Stadt</label>
		<div class="col-xs-10">
			<input class="form-control" name="pcity" type="text" value="">
		</div>
	</div>	
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Postleitzahl</label>
		<div class="col-xs-10">
			<input class="form-control" name="pzip" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Straße</label>
		<div class="col-xs-10">
			<input class="form-control" name="pstreet" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Hausnummer</label>
		<div class="col-xs-10">
			<input class="form-control" name="phnumber" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Musik Genre</label>
		<div class="col-xs-10">
			<select class="form-control" name="pgenre">
					<option value="0">Alle</option>
					<option value="1">Rock</option>
					<option value="2">Pop</option>
					<option value="3">HipHop</option>
					<option value="4">Rap</option>
					<option value="5">Elektro</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Party Typ</label>
		<div class="col-xs-10">
			<select class="form-control" name="ptype">
				<option value="0">Bar</option>
				<option value="1">Disco</option>
				<option value="2">Wald</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Preis</label>
		<div class="col-xs-10">
			<input class="form-control" name="pprice" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Description</label>
		<div class="col-xs-10">
			<input class="form-control" name="pdesc" type="text" value="">
		</div>
	</div>
	<input type='submit' class="btn btn-success" name='partycreate' value="Speichern"> <input type='submit' class="btn btn-info" name='editcancel' value="Abbrechen">
	</form>
	</div>
	</div>
	</div>

	<?php
	}

//erstelle Formular zur Erstellung eines Users
function views_user_create(){
	?>

	<div class="container-fluid">
	<div class="row">
	<div class="col-md-6">
	<h1>User anlegen</h1>
	<br/>
	<form action="index.php" method="POST">
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Userame</label>
		<div class="col-xs-10">
			<input class="form-control" name="uname" type="text" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Passwort</label>
		<div class="col-xs-10">
			<input class="form-control" name="upassword" type="password" value="">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-xs-2 col-form-label">Email</label>
		<div class="col-xs-10">
			<input class="form-control" name="uemail" type="date" value="">
		</div>
	</div>

	<input type='submit' class="btn btn-success" name='usercreate' value="Speichern"> <input type='submit' class="btn btn-info" name='usercreatecancel' value="Abbrechen">
	</form>
	</div>
	</div>
	</div>

	<?php

	}

//Erstelle User Tabelle
function views_user_view(){
	
	//Success Alert wenn User Erstellen erfolgreich
	//Warning Alert wenn User Erstellen erfolgreich
	if($_SESSION['alert'] == "suc_usercreate"){
	?>
	<div class="alert success">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	User erfolgreich erstellt.
	</div>
	<div class="alert warning">
	<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>  
	Der User wird erst nach seiner ersten Aktivität in der User Liste angezeigt.
	</div>
	
	<?php } ?>
	
	<div class="container-fluid">
	<form action="index.php" method="POST">
	
	<table class="table table-bordered">
		<thead class="thead-default">
			<tr>
				<th>UserID</th>
				<th>UserName</th>
				<th>E-Mail</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	
	<?php
	
	//iteriere über den äußeren Array mit Usern
	foreach($_SESSION['uw'] as $keys => $userobject)
	{		
		//iteriere über den inneren Userarray
		foreach($userobject as $inner_key => $wert)
		{
			//Erstelle Spalte falls inner_key != location, da location immer null
			if($inner_key != "location")
			{
				echo "<td>$wert</td>";
			}
			if($inner_key == "userId") $id = $wert;
		}
		//erstelle in jeder Zeile einen "Parties" Button
		echo "<td><button type='submit' class='btn btn-info' name='userpartyview' value='$id'>Parties</button></td>";
		echo "</tr>";
	}
	
	echo "</tbody>";
	echo "</table></form>";
	echo "</div>";

	}

//Erstelle Tabelle mit den Parties vom User	
function views_userparty_view(){
	?>
	
	<div class="container-fluid">
	<form action="index.php" method="POST">
	
	<table class="table table-bordered">
		<thead class="thead-default">
			<tr>
				<th>UserID</th>
				<th>User</th>
				<th>PartyID</th>
				<th>Party Name</th>
				<th>Party Datum</th>
				<th>Location</th>
				<th>Host</th>
				<th>Commitment State</th>
				<th>General Rating</th>
				<th>Price Rating</th>
				<th>Location Rating</th>
				<th>Mood Rating</th>
			</tr>
		</thead>
		<tbody>
	
	<?php
	
	//iteriere über den äußeren Array mit den UserParties
	foreach($_SESSION['up'] as $keys => $userobject)
	{		
		//iteriere über den inneren UserPartyArray
		foreach($userobject as $inner_key => $wert)
		{
			//Erstelle für jeden Wert eine Spalte
			echo "<td>$wert</td>";	
		}
		echo "</tr>";
	}
	
	echo "</tbody>";
	echo "</table>";
	echo "<td><button type='submit' class='btn btn-info' name='userpartycancel' value='back'>Zurück</button></td>";
	echo "</form>";
	echo "</div>";

	}
}
?>