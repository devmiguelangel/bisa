<?php

date_default_timezone_set('America/La_Paz');

class Log
{
	private $m;
	private $db;
	private $db_name = 'BisaLog';
	private	$log;
	private $cx;

	public function __construct($cx)
	{
		$this->cx 	= $cx;
		$this->m 	= new MongoClient();
		$this->db 	= new MongoDB($this->m, $this->db_name);

		$this->log = $this->db->createCollection(
			'logs',
			array(
				'capped' => true
			)
		);
	}

	public function postLog($user_id, $msg)
	{
		$data 		= $this->getDataUser($user_id);
		$user_id 	= '';
		$user_name 	= '';

		if (count($data) > 0) {
			$user_id 	= $data['id_usuario'];
			$user_name 	= $data['usuario'];
		}

		$this->log->insert([
			'user_id'	=> $user_id,
			'user_name'	=> $user_name,
			'ip'		=> $this->getUserIP(),
			'msg'		=> $msg,
			'created_at'	=> date('Y-m-d H:i:s'),
			'ts'		=> new MongoDate()
		]);
	}

	public function getLog()
	{
		$data = $this->log->find();

		return $data;
	}

	public function getDataUser($id)
	{
		$data = array();

		$sql = 'select
			su.id_usuario, 
			su.usuario,
			su.nombre
		from 
			s_usuario as su
		where
			su.id_usuario = "' . base64_decode($id) . '"
		limit 0, 1
		;';

		if (($rs = $this->cx->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$data = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
			}
		}

		return $data;
	}

	public function getUserIP()
	{
	    if (isset($_SERVER)) {
	    	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
	            return $_SERVER["HTTP_X_FORWARDED_FOR"];
	    	}
	        
	        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
	            return $_SERVER["HTTP_CLIENT_IP"];
	        }

	        return $_SERVER["REMOTE_ADDR"];
	    }

	    if (getenv('HTTP_X_FORWARDED_FOR')) {
	        return getenv('HTTP_X_FORWARDED_FOR');
	    }

	    if (getenv('HTTP_CLIENT_IP')) {
	        return getenv('HTTP_CLIENT_IP');
	    }

	    return getenv('REMOTE_ADDR');
	}

}




?>