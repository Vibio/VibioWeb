<?php
define("EBAY_COMM_MSG_SUCCESS", "success");

//$xml is a simple xml element
function _ebay_comm_send($xml, $url_var, $header_version_var)
{
	if (!($url = variable_get($url_var, false)) || !($header_version = variable_get($header_version_var, false)))
	{
		return false;
	}
	
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, _ebay_comm_get_headers(str_replace("Request", "", $xml->getName()), (int) $header_version));
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $xml->asXML());
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl);
	curl_close($curl);
	
	try
	{
		$xml = new SimpleXMLElement($response);
	}
	catch (Exception $e)
	{
		return false;
	}
	
	if (strtolower($xml->Ack) != EBAY_COMM_MSG_SUCCESS)
	{
		return false;
	}
	
	return $xml;
}

function _ebay_comm_get_headers($func_name, $version)
{
	$headers = array (
		"X-EBAY-API-COMPATIBILITY-LEVEL:{$version}",
		"X-EBAY-API-VERSION:{$version}",
		"X-EBAY-API-REQUEST-ENCODING:XML",
		"X-EBAY-API-DEV-NAME:".variable_get("ebayapi_devid", ""),
		"X-EBAY-API-APP-NAME:".variable_get("ebayapi_appid", false),
		"X-EBAY-API-CERT-NAME:".variable_get("ebayapi_certid", false),
		"X-EBAY-API-CALL-NAME:{$func_name}",
		"X-EBAY-API-SITEID: 0",
		'Content-Type: text/xml;charset=utf-8',
	);

	return $headers;
}
?>