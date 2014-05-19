<?php
require_once('api.class.php');

if (substr($_SERVER['QUERY_STRING'], 0, 1) == '/') {
	$query = explode('/', $_SERVER['QUERY_STRING']);
	$api;
	if ($query[1] != 'auth' && $query[1] != 'register' && $query[1] != 'login') {
		if (!isset($_POST['sid'])) die(API::printOutput('auth_failed', ''));
		$api = API::auth($_POST['sid']);
		if ($api == NULL) die(API::printOutput('auth_failed', ''));
    }
		
	
	switch ($query[1]) {
		case 'login':
			if (!isset($_POST['name']) || !isset($_POST['pw'])) sendBadRequest();
			$name = $_POST['name'];
			$pw = $_POST['pw'];
			
			echo API::login($_POST['name'], $_POST['pw']);
			break;
			
		case 'register':
			if (!isset($_POST['name']) || !isset($_POST['pw']) || !isset($_POST['email'])) sendBadRequest();
			$name = $_POST['name'];
			$email = $_POST['email'];
			$pw = $_POST['pw'];
		
			echo API::register($name, $email, $pw);
			break;
			
		case 'get':		
			switch ($query[2]) {
				case 'locations':
					if(isset($_POST['type_id']))
						echo $api->listLocations2(intval($_POST['type_id']));
					else
						echo $api->listLocations();
					break;
					
				case 'location':
					if (!isset($_POST['id'])) sendBadRequest();
					echo $api->getLocation(intval($_POST['id']));
					break;
					
				case 'location_types':
					echo $api->listLocationTypes();
					break;
					
				case 'users':
					if (!isset($_POST['location_id'])) sendBadRequest();
					echo $api->listUsers($_POST['location_id']);
					break;
				
				case 'user':
					if (!isset($_POST['id'])) sendBadRequest();
					echo $api->getUser($user_id);
					break;
					
				case 'me':
					echo $api->getUserSelf();
					break;
					
				default:
					sendBadRequest();
			}
			
			break;
			
		case 'set':
			switch ($query[2]) {
				case 'status':
					if (!isset($_POST['status'])) sendBadRequest();
					echo $api->updateStatus($_POST['status']);
					break;
					
				default:
					sendBadRequest();
			}
			
			break;
			
		case 'checkin':
			if (!isset($_POST['posx']) || !isset($_POST['posy']) || !isset($_POST['location_id'])) sendBadRequest();
			$location_id = $_POST['location_id'];
			$posx = $_POST['posx'];
			$posy = $_POST['posy'];
			echo $api->checkin($location_id, $posx, $posy);
			break;
			
		case 'checkout':
			echo $api->checkout();
			break;
			
		case 'halt':
			echo $api->halt();
			break;
			
		default:
			sendBadRequest();
	}
}

function sendBadRequest() {
	die(API::printOutput("malformed_request", ""));
}

