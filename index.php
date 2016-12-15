<?php
	//STARTFILE
	header("Context-Type: text/html; charset=utf-8");

	session_start();

		Error_Reporting(E_ALL & ~E_NOTICE);
		include "views.php";
	
		//Initialisierung des "views" Objekts
		$app2night = new views();

		//Ausführung der Logik
		$app2night->init();

		//Printe head (der bei allen Seiten gleich ist)
		$app2night->views_head();

		//Entscheide über Session-Variable welche Seite geprintet wird.
		//Default: Login
		switch ($_SESSION['type']){
			case "partys_view":
				$app2night->views_partys_view();
				break;
			case "party_create":
				$app2night->views_party_create();
				break;
			case "party_edit":
				$app2night->views_party_edit();
				break;
			case "user_create":
				$app2night->views_user_create();
				break;
			case "user_view":
				$app2night->views_user_view();
				break;
			case "userparty_view":
				$app2night->views_userparty_view();
				break;
			default:
				$app2night->views_login();
		}

		//Printe footer (der bei allen Seiten gleich ist)
		$app2night->views_footer();

	session_write_close();

?>