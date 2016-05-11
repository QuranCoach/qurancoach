<?php
/*$DB_SERVER = "127.0.0.1";
$DB_USER = "root";
$DB_PASSWORD = "root";
$DB_PORT = "3306";
$DB = "alquaree";*/

$GLOBALS['config'] = array(
		"database" 	=> array(
				"driver" 	=> "mysql",
				"host" 		=> "localhost",
				"port"		=> "3306",
				"user" 		=> "root",
				"password" 	=> "root",
				"db" 		=> "alquaree",
				"charset" 	=> "utf8"
		),
		'remember' 	=> array(
				'cookie_name' 	=> 'hash',
				'cookie_expiry'	=> 	604800 //per second that means 7 days
		), //how long do we want to remember the user if he checked remember me
		'session' 	=> array(
				'session_name'	=> 'user',
				'token_name'	=> 'token'
		),
);

/**
 * Config class v1.0
 */
class Config
{
	public static function get($path = null) {
		if($path){
			$config = $GLOBALS['config'];
			$path = explode('/', $path);

			foreach ($path as $bit) {
				if(isset($config[$bit]));
				$config = $config[$bit];
			}

			return $config;
		}

		return false;
	}
}
?>