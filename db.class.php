<?php
require_once("config.inc.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of api
 *
 * @author flohw
 */
class DB extends PDO {
	private static $db = null;

	public static function getDb() {
		if(self::$db === null) {
			self::$db = new DB();
		}
		
		return self::$db;
	}

    public function __construct($options = null) {
        parent::__construct('mysql:host='.dbhost.';dbname='.dbname,
                            dbuser,
                            dbpw, $options);
        parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function safeQuery($query) {	    
        $args = func_get_args();
        array_shift($args); //first element is not an argument but the query itself, should get removed

        $reponse = parent::prepare($query);
        $reponse->execute($args);
        return $reponse;
    }
}