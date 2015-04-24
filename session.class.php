<?php
class Session{
	private $user_agent, $skey, $ip_address, $last_activity;
	public $idUser, $idEF;
	
	function Session(){
		session_start();
		
		if(isset($_SESSION['sKey'])) $this->skey = $_SESSION['sKey'];
		else $this->skey = '';
		
		if(isset($_SESSION['idUser'])) $this->idUser = $_SESSION['idUser'];
		else $this->idUser = '';
		
		if(isset($_SESSION['idEF'])) $this->idEF = $_SESSION['idEF'];
		else $this->idEF = '';
		
		if(isset($_SESSION['ipAddress'])){
			$this->ip_address = $_SESSION['ipAddress'];
		}else{
			$this->ip_address = $this->get_ip_address();
		}
		
		if(isset($_SESSION['userAgent'])) $this->user_agent = $_SESSION['userAgent'];
		else $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if(isset($_SESSION['lastActivity'])) $this->last_activity = $_SESSION['lastActivity'];
		else $this->last_activity = '';
	}
	
	public function start_session($idUser, $idEF){
		$_SESSION['idUser'] = base64_encode($idUser);
		$_SESSION['idEF'] = base64_encode($idEF);
		$_SESSION['sKey'] = md5(uniqid(mt_rand(), true));
		$_SESSION['ipAddress'] = $this->ip_address;
		$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['lastActivity'] = $_SERVER['REQUEST_TIME'];
	}
	
	public function check_session(){
		if(isset($_SESSION['sKey']) 
			&& isset($_SESSION['ipAddress']) 
			&& isset($_SESSION['idUser']) 
			&& isset($_SESSION['idEF'])){
				
			if($_SESSION['ipAddress'] == $this->get_ip_address() && $_SESSION['userAgent'] == $this->user_agent){
				return true;
			}
		}else{
			return false;
		}
	}
	
	public function remove_session(){
		session_unset();
		session_destroy();
		session_regenerate_id(true);
	}
	
	private function get_ip_address(){
		$ip = '';
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
}
?>