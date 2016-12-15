<?php

class utils{
	
	//entscheide, welche Funktion ausgeführt wird
	function init() {
		
	//Führe aus, wenn Button "logbutton" gedrückt wurde, User will sich einloggen
	if($_POST['logbutton']) $this->login();
	//Führe aus, wenn Button "partyeditview"/"Bearbeiten" gedrückt wurde, User will eine Party bearbeiten 
	if($_POST['partyeditview']) $this->partyeditview();
	//Führe aus, wenn Button "partyedit"/"Speichern" gedrückt wurde, User will Bearbeitung bestätigen
	if($_POST['partyedit']) $this->partyedit();
	//Führe aus, wenn Button "partydelete"/"Löschen" gedrückt wurde, User will Party löschen
	if($_POST['partydelete']) $this->partydelete();
	//Führe aus, wenn Button "editcanel"/"Abbrechen" gedrückt wurde, User will eine Bearbeitung abbrechen
	if($_POST['editcancel']) $this->editcancel();
	//Führe aus, wenn Button "partycreateview"/"Party erstellen" gedrückt wurde, User will neue Party anlegen
	if($_POST['partycreateview']) $this->partycreate();
	//Führe aus, wenn Button "partysview"/"Party Liste" gedrückt wurde, User will Partyliste anzeigen (über Menü)
	if($_POST['partysview']) $this->partylist();
	//Führe aus, wenn Button "userpartyview"/"Parties" gedrückt wurde, User will die Parties eines Users anzeigen
	if($_POST['userpartyview']) $this->userpartyview();
	//Führe aus, wenn Button "usercreateview"/"User anlegen" gedrückt wurde, User will einen neuen User anlegen
	if($_POST['usercreateview']) $this->usercreateview();
	//Führe aus, wenn Button "usercreate"/"Speichern" gedrückt wurde, User will die Erstellung eines Users bestätigen
	if($_POST['usercreate']) $this->usercreate();
	//Führe aus, wenn Button "userview"/"User Liste" gedrückt wurde, User will User Liste anzeigen
	if($_POST['usersview']) $this->usersview();
	//Führe aus, wenn Button "partycreate"/"Speichern" gedrückt wurde, User will die Erstellung einer Party bestätigen
	if($_POST['partycreate']) $this->partypost();
	//Führe aus, wenn Button "userpartycancel"/"Zurück" gedrückt wurde, User will auf die User Liste zurückkehren
	if($_POST['userpartycancel']) $this->userpartcancel();
	//Führe aus, wenn Button "usercreatecancel"/"Abbrechen" gedrückt wurde, User will Erstellung abbrechen
	if($_POST['usercreatecancel']) $this->usercreatecancel();
	//Führe aus, wenn Button "partysallview"/"Party Liste (Alle)" gedrückt wurde, User will alle Parties anzeigen
	if($_POST['partysallview']) $this->partysview();
	//Führe direkt nach Login aus, Party Liste wird als Startseite angezeigt
	if($_SESSION['type'] == "partys_view") $this->partysview();
}


	function login(){
		//definiere URL
		$api_url = 'http://app2nightuser.azurewebsites.net/connect/token';
		
		//definiere head
		$data = array(	'client_id' => 'nativeApp',
						'client_secret' => 'secret',
						'grant_type' => 'password',
						'username' => $_POST['username'],
						'password' => $_POST['password'],
						'scope' => 'App2NightAPI offline_access openid email');
						
		$curl = curl_init();

		$curlConfig = array(
			CURLOPT_URL => $api_url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_POSTFIELDS => $data
		);

		curl_setopt_array($curl, $curlConfig);

		//starte request
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($code == 200) {
			$response = json_decode($response, true);
			//setze den Token auf Session Variable
			$_SESSION['token'] = $response['access_token'];
			$_SESSION['type'] = "partys_view";
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}

	function partysview(){
	
		$headers = array(
			'Accept : application/json',
			'Content-Type: application/json',
					'Authorization: Bearer ' . $_SESSION['token']);
		
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/admin/getPartys';
		
		if(isset($_POST['partysallview']) && !empty($_POST['partysallview']))
		{
			//füge param der URL hinzu
			$api_url .= '?loadAll=true';
			$_SESSION['alert'] = '';
		}
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		 
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 200) {
			$response = json_decode($response, true);
			//setze Antwort Array auf Session Variable "pw"
			$_SESSION['pw'] = $response;
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}

	function partyeditview(){
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/party';
	
		$headers = array('Accept : application/json' ,
							'Content-Type: application/json');
		
		$id = $_POST['partyeditview'];
		$curl = curl_init();
		
		//füge ID der URL hinzu
		$api_url .= '/id=' . $id;

		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 200) {
			$response = json_decode($response, true);
			//lade Bearbeitungsformular
			$_SESSION['type'] = "party_edit";
			//setze den Antwort Array auf Session Variable "pe"
			$_SESSION['pe'] = $response;
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}

	function partyedit(){
		
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/admin/modifyParty';
	
		$headers = array('Content-Type: application/json',
			'Authorization: Bearer ' . $_SESSION['token']);
		
		$id = $_POST['partyedit'];
		
		$data = array(
		  "partyName" => $_POST['pname'],
		  "partyDate" => $_POST['pdate'],
		  "musicGenre" => $_POST['pgenre'],
		  "countryName" => $_POST['pcountry'],
		  "cityName" => $_POST['pcity'],
		  "streetName" => $_POST['pstreet'],
		  "houseNumber" => $_POST['phnumber'],
		  "zipcode" => $_POST['pzip'],
		  "partyType" => $_POST['ptype'],
		  "description" => $_POST['pdesc'],
		  "price" => $_POST['pprice']
		);
		
		$data_string = json_encode($data);
		
		$curl = curl_init();
		
		//Füge ID der URL hinzu
		$api_url .= '?id=' . $id;
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);                                                                           
																															 
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 200) {
		//kehre zurück zur Party Liste mit Success Alert
		$_SESSION['type'] = "partys_view";
		$_SESSION['alert'] = "suc_edit";
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
	function partydelete() {
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/admin/deleteParty';
	
		$headers = array('Content-Type: application/json',
			'Authorization: Bearer ' . $_SESSION['token']);
		
		$id = $_POST['partydelete'];
		
		$curl = curl_init();
		
		//Füge ID der URL hinzu
		$api_url .= '?id=' . $id;
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);                                                                           
																															 
		$result = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 200) {
			//kehre zurück zur Party Liste mit Success Alert
			$_SESSION['type'] = "partys_view";
			$_SESSION['alert'] = "suc_delete"; 
		}
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
	function partypost(){
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/party';
		
		$headers = array('Content-Type: application/json',
				'Authorization: Bearer ' . $_SESSION['token']);
			
		$pDate = $_POST['pdate'];
		
		$convDate = date("Y-m-d H:i:s", strtotime($pDate));
			
		$data = array(
		  "partyName" => $_POST['pname'],
		  "partyDate" => $convDate,
		  "musicGenre" => $_POST['pgenre'],
		  "countryName" =>  $_POST['pcountry'],
		  "cityName" =>  $_POST['pcity'],
		  "streetName" =>  $_POST['pstreet'],
		  "houseNumber" =>  $_POST['phnumber'],
		  "zipcode" =>  $_POST['pzip'],
		  "partyType" =>  $_POST['ptype'],
		  "description" => $_POST['pdesc'],
		  "price" => $_POST['pprice']
		);
			
		$data_string = json_encode($data);
		
		$curl = curl_init();
			
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_POST, true);                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);                                                                           
																																 
		$result = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 201) {
			//kehre zurück zur Party Liste mit Success Alert
			$_SESSION['type'] = "partys_view";
			$_SESSION['alert'] = "suc_create";
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
	function editcancel(){
		//navigiere zur Party Liste, leere Alert
		$_SESSION['type'] = "partys_view";
		$_SESSION['alert'] = "";
	}
	
	function partycreate(){
		//navigiere zum Erstellungsformular, leere Alert
		$_SESSION['type'] = "party_create";
		$_SESSION['alert'] = "";
	}
	
	function partylist(){
		//navigiere zur Party Liste, leere Alert
		$_SESSION['type'] = "partys_view";
		$_SESSION['alert'] = "";
	}
	
	function userpartcancel(){
		//navigiere zur User Liste, leere Alert
		$_SESSION['type'] = "user_view";
		$_SESSION['alert'] = "";
	}
	
	function usercreateview(){
		//navigiere zum Erstellungsformular, leere Alert
		$_SESSION['type'] = "user_create";
		$_SESSION['alert'] = "";
	}

	function usercreate(){
		//definiere URL
		$api_url = 'http://app2nightuser.azurewebsites.net/api/user';
		
		$headers = array('Content-Type: application/json');

		$data = array(
			"username" => $_POST['uname'],
			"password" => $_POST['upassword'],
			"email" => $_POST['uemail']
		);
		
		$data_string = json_encode($data);
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_POST, true);                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);   
		
		$result = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		if ($code == 201) {
			//kehre zurück zur User Liste mit Success Alert
			$_SESSION['type'] = "user_view";
			$_SESSION['alert'] = "suc_usercreate";
		}
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
	function usercreatecancel(){
		$_SESSION['type'] = "user_view";
	}
	
	function usersview(){
		
		$headers = array(
			'Accept : application/json',
			'Content-Type: application/json',
			'Authorization: Bearer ' . $_SESSION['token']);
			
		//definiere URL	
		$api_url = 'http://app2nightapi.azurewebsites.net/api/admin/GetUser';
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		 
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		 
		if ($code == 200) {
			$response = json_decode($response, true);
			//lade User Liste, setze Antwort Array auf Session Variable "uw"
			$_SESSION['type'] = "user_view";
			$_SESSION['uw'] = $response;
		} 
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
	function userpartyview(){
		
		$headers = array(
			'Accept : application/json',
			'Content-Type: application/json',
			'Authorization: Bearer ' . $_SESSION['token']);
		
		//definiere URL
		$api_url = 'http://app2nightapi.azurewebsites.net/api/admin/GetUserParties';
		
		$curl = curl_init();
		
		$id = $_POST['userpartyview'];
		//Füge ID der URL hinzu
		$api_url .= '?id=' . $id;
		
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		 
		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		if ($code == 200) {
			$response = json_decode($response, true);
			//navigiere zu UserParty Liste, setze Antwort Array auf Session Variable "up"
			$_SESSION['type'] = "userparty_view";
			$_SESSION['up'] = $response;
		}
		if ($code == 400 || $code == 404) {
			$_SESSION['alert'] = "fail";
		}
		if ($code == 401) {
			$_SESSION['type'] = "";
			$_SESSION['alert'] = "";
		}
	}
	
}
?>