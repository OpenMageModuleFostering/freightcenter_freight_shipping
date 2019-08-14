<?php
define('MAGENTO_ROOT', getcwd());
    $mageFilename = MAGENTO_ROOT . '/app/Mage.php';
    require_once $mageFilename;
    Mage::init();


if(isset($_GET['bookingno'])){
$bookingno = $_GET['bookingno'];
}else{
$bookingno = '11195603';
}

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



 ?>

<?php $xml='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
<soap12:Body>
<IsBolReadyForDownload xmlns="http://freightcenter.com/API/V04/">
<ShipmentId>'.$bookingno.'</ShipmentId>
</IsBolReadyForDownload>
</soap12:Body>
</soap12:Envelope>';
            $apiurl = $apishipmenturl;
            
            $headers = array(
                "POST /V04/shipments.asmx HTTP/1.1",
                "Host: sandbox.freightcenter.com",
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
							//echo '<pre>'; print_r($vals);
						foreach($vals as $charges){
                                if($charges['tag'] == 'ISBOLREADYFORDOWNLOADRESULT'){
                                 echo    $total_charge = $charges['value'];
                                }
								}
						
			?>