<?php
/*

*	File:	curl.php
*	By: Louis Nel
*	Date: 2013-08-9
*	Script to connect HTML Landing Page to Inbound Leads Service


**************************************
*/

function post_xml($url, $xml) {

  $ch = curl_init($url);
			//curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlRequest=$xml");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);
  $info = curl_getinfo($ch);
  curl_close($ch);
  return $result;
}

$FirstNames = $_REQUEST['FirstNames'];
$Number = $_REQUEST['Number'];

$xml = '<messageBody><EventDataRow><MessageData><DataProviderID>TheUnlimited</DataProviderID><MessageType>Web Lead</MessageType><Channel>43178</Channel><MessageText>GLV500</MessageText><ReceivedOn>2011-07-19 10:24:58</ReceivedOn><FirstNames>' . $FirstNames . '</FirstNames><LastName></LastName><ClientNumber>' . $Number . '</ClientNumber></MessageData></EventDataRow></messageBody>';

$url = "https://inboundleads.theunlimited.co.za/WebServices/WebLeadInboundService.asmx/CaptureInput";
$result = post_xml($url, $xml);
echo "<pre>"; 
echo htmlspecialchars($result); 
echo "</pre>";

$info = curl_getinfo($ch);
echo($info['request_header']);


?>