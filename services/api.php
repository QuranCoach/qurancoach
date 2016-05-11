<?php
 	require_once("Rest.inc.php");
 	require_once("config.php");
 	require_once('models/users.php');
 	
	class API extends REST {
	
		public $data = "";
		
		private $entity;
		private $methode;
		
		private $db = NULL;
		
		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/*
		 * Dynmically call the method based on the query string
		 */
		public function processApi(){
			$service = explode("/", $_GET['x']);
			$this->entity = ucfirst($service[0]);
			$this->methode = $service[1];
			$func = $this->methode.$this->entity;
			$this->addLog('--'.$this->methode.$this->entity);
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class "Page not found".
				
		}
				
		/*private function login(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$email = $this->_request['email'];		
			$password = $this->_request['pwd'];
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$query="SELECT id, lname, fname FROM users WHERE email = '$email' AND password = '".$password."' LIMIT 1";
					$r = $this->pdo->query($query) or die($this->pdo->errorInfo().__LINE__);

					if($r->rowCount() > 0) {
						$result = $r->fetch(PDO::FETCH_ASSOC);	
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}
					$this->response('', 204);	// If no records "No Content" status
				}
			}
			
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}*/
		
		private function viewUsers(){	//users
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$users = new Users();
			$result = $users->view($id);
			if(count($result) > 0){
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		private function insertUsers(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$user = json_decode(file_get_contents("php://input"),true);
			if(!empty($user)){
				$u = new Users();
				$u->insert($user);
				$success = array('status' => "Success", "msg" => "User Created Successfully.", "data" => $user);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	//"No Content" status
		}
		
		private function updateUsers(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$user = json_decode(file_get_contents("php://input"),true);
			if(!empty($user)){
				$u = new Users();
				$u->update($user);
				$success = array('status' => "Success", "msg" => "User ".$id." Updated Successfully.", "data" => $user);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// "No Content" status
		}
		
		private function deleteUsers(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				$u = new Users();
				$u->delete($id);
				$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

	}
	
	// Initiiate Library
	$api = new API;
	$api->processApi();
?>