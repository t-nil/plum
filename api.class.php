<?php
require_once("db.class.php");

/**
 * The API class.
 * New instances are only aquirable through API::auth().
 * Every method, unless otherwise stated, returns a json string in the scheme of {"error": <error string>, "result": <optional result>}.
 */
class API {
	public static $loginTime = 86400;
	public static $db;
		
	private $user;
    
    /**
     * Initializes the database connection. Do not call this function yourself!
     */
    public static function init() {
        self::$db = DB::getDb();
		define("QUERY_GEN_USERCOUNT", "SELECT locations.*, COUNT(users.id) as usercount FROM locations LEFT JOIN users ON users.checked_in_at = locations.id GROUP BY locations.id");
    }

    /**
     * Executes a login.
     * 
     * @param string $name username
     * @param string $pw password
     * @return string the session id to be used by auth
     */
	public static function login($name, $pw) {
		$result = self::$db->safeQuery("SELECT id, pw FROM users
                                        WHERE name = ? AND is_active = TRUE",
		                               $name);
		
		if ($result->rowCount() == 0) {
			return self::printOutput('uname_not_found', '');
		}
		
		$user = $result->fetch(PDO::FETCH_ASSOC);
		
		if ($user['pw'] != self::_hashPw($pw)) {
			return self::printOutput('wrong_pw', '');
		}
		
		$sid = uniqid('api', true);
		self::$db->safeQuery("UPDATE users SET sid = ? WHERE id = ?",
                             $sid, $user['id']);
		self::_setLastAccessed($user['id']);
		
		return self::printOutput('',
		             array('uid' => $user['id'],
					       'sid' => $sid));
	}
	
    /**
     * Registers a new user at the backend.
     * 
     * @param string $name username (unique)
     * @param string $email email address (also unique)
     * @param string $pw password (not necessarily unique ^_^)
     * @return string 
     */
	public static function register($name, $email, $pw) {
		if (strlen($name) < 3 ||
		    strlen($name) > 20 ||
	       !filter_var($email, FILTER_VALIDATE_EMAIL) ||
		    strlen($email) > 60 ||
		    strlen($pw) < 4 ||
		    strlen($pw) > 32) {
			return self::printOutput('malformed_input', '');
		}
		
		$users = self::$db->safeQuery("SELECT COUNT() AS count FROM users
		                        WHERE name = ? OR email = ?",
		                        $name, $email)->fetch();
		
		if ($users['count'] > 0) {
			return self::printOutput('already_in_use', '');
		}
		
		$pw_hashed = self::_hashPw($pw);
		
		self::$db->safeQuery("INSERT INTO users
			        SET name = ?, email = ?, pw = ?", 
			       $name, $email, $pw_hashed);
				   
		mail($email, "Registration on plum.faui2k13.de",
			     "Thank you for registering your account. Click
				  <a href='http://plum.faui2k13.de/confirm_register.php?name="
				 .urlencode($_POST["name"])."&key=".$pw_hashed."'>here</a>
				 to activate your account.",
				 "From: faui2k13-Team <noreply@faui2k13.de>\nContent-Type:text/html");
		
		return self::printOutput('', '');
	}
	
    /**
     * Authenticates against the backend.
     * 
     * @param string $sid the session id
     * @return null|\API An instance of API on successfull auth, null otherwise
     */
	public static function auth($sid) {
		$userQuery = self::$db->safeQuery("SELECT * FROM users WHERE sid LIKE ?", $sid);
		
		if ($userQuery->rowCount() == 0) {
			return null;
		}
		
		$user = $userQuery->fetch();
		
		if (!self::_checkLoggedIn($user['id'])) {
			return null;
		}
		
		return new API($user);
	}

    /**
     * A helper method, used to generate the json strings.
     * You probably won't need it.
     * 
     * @param var $error
     * @param var $result
     * @return string
     */
	public static function printOutput($error, $result = "") {
		return json_encode(array('error' => $error, 'result' => $result));
	}
	
	private function __construct($user) {
		$this->user = $user;
		self::_setLastAccessed($user['id']);
	}
	
    /**
     * Lists all available locations.
     * 
     * @return array(array(var)) the locations array
     */
	public function listLocations() {
		$locations = self::$db->safeQuery(QUERY_GEN_USERCOUNT);
		return API::printOutput('', $locations->fetchAll(PDO::FETCH_ASSOC));
	}
	
	public function listLocations2($type) {
		$locations = self::$db->safeQuery(QUERY_GEN_USERCOUNT . " HAVING locations.type_id = ?", $type);
		return API::printOutput('', $locations->fetchAll(PDO::FETCH_ASSOC));
	}
	
	public function getLocation($id) {
		$location = self::$db->safeQuery(QUERY_GEN_USERCOUNT . " HAVING locations.id = ?", $id);
		return API::printOutput('', $location->fetch(PDO::FETCH_ASSOC));
	}
	
    /**
     * Lists all available location types.
     * @return array(array(var)) the location types array
     */
	public function listLocationTypes() {
		$location_types = self::$db->safeQuery(
				"SELECT location_types.*, SUM(locations2.usercount) as usercount
				FROM location_types
				LEFT JOIN (" . QUERY_GEN_USERCOUNT . ") as locations2
				ON locations2.type_id = location_types.id
				GROUP BY location_types.id");
		return API::printOutput('',
                $location_types->fetchAll(PDO::FETCH_ASSOC));
	}
	
    /**
     * Lists all users that are currently checked in at a location.
     * 
     * @param int $location_id the location id
     * @return array(array(var)) the user array 
     */
	public function listUsers($location_id) {
		$users = self::$db->safeQuery("SELECT id, name, last_access, checked_in_at_time, pos_x, pos_y, status FROM users
                                       WHERE checked_in_at = ?",
                                      $location_id);
		return API::printOutput('', $users->fetchAll(PDO::FETCH_ASSOC));
	}
	
    /**
     * Fetches information about a specific user. (use getUserSelf()) for infos about yourself)
     * 
     * @param string $userid the user id
     * @return array(var) user data
     */
	public function getUser($userid) {
		$_user = self::$db->safeQuery("SELECT name, last_access, checked_in_at,
                                       checked_in_at_time, pos_x, pos_y, status
		                               FROM users
								       WHERE id = ?", $userid)
                          ->fetch(PDO::FETCH_ASSOC);
		return self::printOutput('', $_user);
	}
	
    /**
     * Fetches information about the user this session belongs to.
     * 
     * @return array(var) user data
     */
	public function getUserSelf() {
		$_user = self::$db->safeQuery("SELECT name, email, checked_in_at,
                                       checked_in_at_time, pos_x, pos_y, status
		                               FROM users
								       WHERE id = ?", $this->user['id'])
                          ->fetch(PDO::FETCH_ASSOC);
		return self::printOutput('', $_user);
	}
	
    /**
     * Updates the user status.
     * <b>This method currently doesn't report any errors.</b>
     * 
     * @param type $new_status the status to set
     */
	public function updateStatus($new_status) {
		self::$db->safeQuery("UPDATE users SET status = ? WHERE id = ?",
                             $new_status, $this->user['id']);
	}
	
    /**
     * Checks in the current user at the told location & coordinates.
     * 
     * @param int $location_id the location
     * @param int $posx the x coordinate
     * @param int $posy the y coordinate
     * @return nothing
     */
	public function checkin($location_id, $posx, $posy) {
		if (self::$db->safeQuery("SELECT id FROM locations where id = ?",
                                $location_id)->rowCount() == 0) {
			return self::printOutput('invalid_location', '');
        }
		
		self::$db->safeQuery("UPDATE users SET checked_in_at = ?,
		                      checked_in_at_time = ?, pos_x = ?, pos_y = ?
						      WHERE id = ?",
                             $location_id, time(), $posx, $posy, $this->user['id']);
		
		return self::printOutput('', '');
	}
	
    /**
     * checkout(x) := checkin^-1(x) (d'oh)
     * 
     * @return nothing
     */
	public function checkout() {
		self::$db->safeQuery("UPDATE users SET checked_in_at = NULL
		                WHERE id = ?", $this->user['id']);
						
		return self::printOutput('', '');
	}
	
    /**
     * Logs the current user out.
     * 
     * @return nothing
     */
	public function halt() {
		self::$db->safeQuery("UPDATE users SET sid = NULL WHERE id = ?",
                             $this->user['id']);
		
		return self::printOutput('', '');
	}
	
	private static function _setLastAccessed($uid) {
		self::$db->safeQuery("UPDATE users SET last_access = ?
                              WHERE id = ?", time(), $uid);
	}
	
	private static function _checkLoggedIn($uid) {
		$user = self::$db->safeQuery("SELECT COUNT(*) as count, last_access
                                      FROM users
		                              WHERE id = ? AND sid IS NOT NULL
									               AND last_access IS NOT NULL",
		                             $uid)->fetch();
									 
		if ($user['count'] == 1
            && (self::$loginTime == -1
                || $user['last_access'] + self::$loginTime > time())) {
			return true;
		}
		
		return false;
	}
	
	private static function _hashPw($pw) {		
		return md5($pw . pepper);
	}
}

API::init();