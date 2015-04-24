<?php
require('session.class.php');
$session = new Session();
$session->remove_session();
echo '<meta http-equiv="refresh" content="0;url=index.php" >';
?>