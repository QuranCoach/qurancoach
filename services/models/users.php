<?php


class Users {
	private $fname;
	private $lname;
	private $email;
	private $password;
	
	private $pdo = NULL;
	
	public function __construct(){
		$this->pdo = new PDO(
				Config::get('database/driver').":dbname=".Config::get('database/db').";port=".Config::get('database/port').";host=".Config::get('database/host').";charset=utf8",
				Config::get('database/user'),
				Config::get('database/password'));
	}
	
	public function view($id){
		if($id > 0){
			$query="SELECT distinct u.id, u.fname, u.lname, u.email, u.password FROM users u where u.id=$id";
		}else{
			$query="SELECT distinct u.id, u.fname, u.lname, u.email FROM users u order by u.id desc";
		}
		$r = $this->pdo->query($query) or die($this->pdo->errorInfo().__LINE__);
		if($r->rowCount() > 0){
			if($id > 0){
				$result = $r->fetch(PDO::FETCH_ASSOC);
			}else{
				$result = array();
				while($row = $r->fetch(PDO::FETCH_ASSOC)){
					$result[] = $row;
				}
			}
		}
		return  $result;
	}
	
	public function insert($user){
		$column_names = array('fname', 'lname', 'email', 'password');
		$keys = array_keys($user);
		$columns = '';
		$values = '';
		foreach($column_names as $desired_key){ // Check the user received. If blank insert blank into the array.
			if(!in_array($desired_key, $keys)) {
				$$desired_key = '';
			}else{
				$$desired_key = $user[$desired_key];
			}
			$columns = $columns.$desired_key.',';
			$values = $values."'".$$desired_key."',";
		}
		$query = "INSERT INTO users(".trim($columns,',').") VALUES(".trim($values,',').")";
		$r = $this->pdo->query($query) or die($this->pdo->eerrorInfo().__LINE__);
	}
	
	public function update($user){
		$id = (int)$user['id'];
		$column_names = array('fname', 'lname', 'email', 'password');
		$keys = array_keys($user['user']);
		$columns = '';
		$values = '';
		foreach($column_names as $desired_key){ // Check the user received. If key does not exist, insert blank into the array.
			if(!in_array($desired_key, $keys)) {
				$$desired_key = '';
			}else{
				$$desired_key = $user['user'][$desired_key];
			}
			$columns = $columns.$desired_key."='".$$desired_key."',";
		}
		$query = "UPDATE users SET ".trim($columns,',')." WHERE id=$id";
		$r = $this->pdo->query($query) or die($this->pdo->errorInfo().__LINE__);
	}
	
	public function delete($id){
		$query="DELETE FROM users WHERE id = $id";
		$r = $this->pdo->query($query) or die($this->pdo->errorInfo().__LINE__);
	}
	
	function addLog($txt)
	{
		file_put_contents("log.txt", date("[j/m/y H:i:s]")." - $txt \r\n", FILE_APPEND);
	}
}
?>