<?php
  require_once('config.class.php');
  $conexion = new SibasDB();
  
  if($_POST['opcion']=='verify_password'){
	  if(getDataChar($_POST['pass'])){
		 echo 0;  
	  }else{
		 echo 1; 
	  }
	  
  }
  
  function getDataChar($password) {
	  $len_pass = strlen($password) - 1;
  
	  for ($i = 0; $i <= $len_pass; $i++) { 
		  if ($i < $len_pass) {
			  if ($password[$i] === $password[$i + 1]) {
				  return false;
			  }
		  }
	  }
  
	  return true;
  }
?>
