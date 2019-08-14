<?php
require 'app/Mage.php'; 
umask(0);
Mage::app('admin'); 

$sandbox = Mage::getStoreConfig('carriers/freightcenter/sandbox');
$freightuser       = Mage::getStoreConfig('carriers/freightcenter/freightuser');
$freightpwd       = Mage::getStoreConfig('carriers/freightcenter/freightpwd');
/* $apishipmenturl = Mage::getStoreConfig('carriers/freightcenter/apishipmenturl');
$apicarriersurl = Mage::getStoreConfig('carriers/freightcenter/apicarriersurl');
$apirateurl = Mage::getStoreConfig('carriers/freightcenter/apirateurl'); */
if($sandbox == '1'){
$host = "sandbox.freightcenter.com";
	$apishipmenturl = "http://sandbox.freightcenter.com/v04/shipments.asmx";
	$apirateurl = "http://sandbox.freightcenter.com/v04/rates.asmx";
	$apicarriersurl = "http://sandbox.freightcenter.com/v04/carriers.asmx";
}
else{
$host = "api.freightcenter.com";
	$apishipmenturl = "http://api.freightcenter.com/v04/shipments.asmx";
	$apirateurl = "http://api.freightcenter.com/v04/rates.asmx";
	$apicarriersurl = "http://api.freightcenter.com/v04/carriers.asmx";
}
if(isset($_GET['bookingno'])){
$bookingno = $_GET['bookingno'];
}else{
$bookingno = '11195603';
}
if(isset($_GET['quote_id'])){
$quote_id = $_GET['quote_id'];
}else{
$quote_id = '11195603';
}if(isset($_GET['product_id'])){
$product_id = $_GET['product_id'];
}else{
$product_id = '11195603';
}if(isset($_GET['agent'])){
$agent = $_GET['agent'];
}else{
$agent = 'admin';
}



 ?>



<?php $xml='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <CancelShipment xmlns="http://freightcenter.com/API/V04/">
      <request>
        <LicenseKey>3bda740c-ff39-4bdc-ab49-b0a3c08e23b4</LicenseKey>
      <Username>'.$freightuser.'</Username>
      <Password>'.$freightpwd.'</Password>
      <ShipmentId>'.$bookingno.'</ShipmentId>
      <Reason>Magento Cancelation Request</Reason>
	  <Agent><Name>'.$agent.'</Name></Agent>
      </request>
    </CancelShipment>
  </soap12:Body>
</soap12:Envelope>';
            $apiurl = $apishipmenturl;
            
            $headers = array(
                "POST /V04/shipments.asmx HTTP/1.1",
                "Host: ".$host."",
                "Content-Type: text/xml; charset=utf-8",
                "Content-length: ".strlen($xml)
            );
           
            /* send curl request to get active carriers */
            $soap_do = curl_init();
            curl_setopt($soap_do, CURLOPT_URL,            $apiurl );
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do, CURLOPT_POST,           true );
			curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT ,0); 
			curl_setopt($soap_do, CURLOPT_TIMEOUT, 400);
            curl_setopt($soap_do, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $xml);

            $result = curl_exec($soap_do);

                            curl_close($soap_do);
                                                        
                            $p = xml_parser_create();
                            xml_parse_into_struct($p, $result, $vals, $index);
                            xml_parser_free($p);
							//echo '<pre>';print_r($vals);
						//echo $cancel_ship = count($vals);
						foreach($vals as $ship){
						if($ship['tag'] == 'TYPE' ){
							 $message = $ship['value'];
						}
						
						
						
						
						}
						
	if($message == 'SUCCESS'){
	echo $message ; 
	$resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write'); 
        $tableNamep = $resource->getTableName('brst_freight_shipping');
		$query = "UPDATE `$tableNamep` SET final_dest ='' where quote_id = $quote_id AND product_id = $product_id";
        $writeConnection->query($query);
	}	else if($message == ''){
	echo "Cancelation of Booking is unsuccessfull";
	}	
else if($message == 'WARNING'){
	echo "WARNING";
	$resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write'); 
        $tableNamep = $resource->getTableName('brst_freight_shipping');
		$query = "UPDATE `$tableNamep` SET final_dest ='' where quote_id = $quote_id AND product_id = $product_id";
        $writeConnection->query($query);
	}
	else {
	echo "Cancelation of Booking is unsuccessfull";
	}
						
		
			?>