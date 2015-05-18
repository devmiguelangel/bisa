<?php

require_once('lib/nusoap.php'); 

$soapAction = "http://aqua.bisa.com/servicios/swissre/ws/"; 
$operation = "cuentasporCliente"; 

$wsdl="http://10.200.3.82:8810/AquaWar/soap/definition-sudprueba.wsdl";
$namespace = "http://schemas.xmlsoap.org/soap/envelope/"; 

$client = new nusoap_client($wsdl, false); 
	$client->soap_defencoding = 'UTF-8'; 
	$client->setCredentials("sudprueba","HZ+hRGJnkiCK5bRsnnQcpw==");

$err = $client->getError();
if ($err) { 
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>'; 
}

$message = "<soapenv:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ws='http://aqua.bisa.com/servicios/swissre/ws'> ";
$message .= "   <soapenv:Header/> ";
$message .= "   <soapenv:Body> ";
$message .= "      <ws:cuentasporClienteRequest> ";
$message .= "         <ws:codigoCliente>0000001209</ws:codigoCliente> ";
$message .= "      </ws:cuentasporClienteRequest>";
$message .= "   </soapenv:Body> ";
$message .= "</soapenv:Envelope> ";

//$response = $client->call($operation, $message, $namespace, $soapAction, '', 'document', 'literal');
//$param = array('codigoCliente' => '0000001209');

$response = $client->send( $message, $soapAction, '', '');
//$response = $client->call($operation, $param);

if ($client->fault) { 
    echo '<h2>Fault</h2><pre>'; 
    print_r($response); 
    echo '</pre>'; 
} else { 
    $err = $client->getError(); 
    if ($err) { 
        echo '<h2>Error1</h2><pre>' . $err . '</pre>'; 
    } else { 
        echo '<h2>Result</h2><pre>';
		print_r($response);
    echo '</pre>'; 
    } 
} 

echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>'; 
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>'; 
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>'; 



?>