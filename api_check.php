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
	
if(isset($_GET['date_from']) && $_GET['date_from'] != NULL) {
    $date_from = $_GET['date_from'];
} else {
    $date_from = '2014-07-25';
}
if(isset($_GET['date_to']) && $_GET['date_to'] != NULL) {
    $date_to = $_GET['date_to'];
} else {
    $date_to = '2014-06-24';
}
if(isset($_GET['ware_fname']) && $_GET['ware_fname'] != NULL) {
    $ware_fname = $_GET['ware_fname'];
} else {
    $ware_fname = 'Jason';
}
if(isset($_GET['ware_lname']) && $_GET['ware_lname'] != NULL) {
    $ware_lname = $_GET['ware_lname'];
} else {
    $ware_lname = 'Maldonado';
}
if(isset($_GET['warephone']) && $_GET['warephone'] != NULL) {
    $warephone = $_GET['warephone'];
} else {
    $warephone = '8134479678';
}
if(isset($_GET['warepostcode']) && $_GET['warepostcode'] != NULL) {
    $warepostcode = $_GET['warepostcode'];
} else {
    $warepostcode = '33545';
}
if(isset($_GET['warestreet']) && $_GET['warestreet'] != NULL) {
    $warestreet = $_GET['warestreet'];
} else {
    $warestreet = '123';
}
if(isset($_GET['warecity']) && $_GET['warecity'] != NULL) {
    $warecity = $_GET['warecity'];
} else {
    $warecity = 'Wesley Chapel';
}
if(isset($_GET['warestate']) && $_GET['warestate'] != NULL) {
    $warestate = $_GET['warestate'];
} else {
    $warestate = 'FL';
}
if(isset($_GET['warecountry']) && $_GET['warecountry'] != NULL) {
    $warecountry = $_GET['warecountry'];
} else {
    $warecountry = 'US';
}
if(isset($_GET['fname']) && $_GET['fname'] != NULL) {
    $fname = $_GET['fname'];
} else {
    $fname = 'Jason';
}
if(isset($_GET['lname']) && $_GET['lname'] != NULL) {
    $lname = $_GET['lname'];
} else {
    $lname = 'Maldonado';
}
if(isset($_GET['telephone']) && $_GET['telephone'] != NULL) {
    $telephone = $_GET['telephone'];
} else {
    $telephone = '8134479678';
}
if(isset($_GET['pincode']) && $_GET['pincode'] != NULL) {
    $pincode = $_GET['pincode'];
} else {
    $pincode = '33545';
}
if(isset($_GET['street']) && $_GET['street'] != NULL) {
    $street = $_GET['street'];
} else {
    $street = '123';
}
if(isset($_GET['city']) && $_GET['city'] != NULL) {
    $city = $_GET['city'];
} else {
    $city = 'Wesley Chapel';
}
if(isset($_GET['region']) && $_GET['region'] != NULL) {
    $region = $_GET['region'];
} else {
    $region = 'FL';
}
if(isset($_GET['country']) && $_GET['country'] != NULL) {
    $country = $_GET['country'];
} else {
    $country = 'US';
}
if(isset($_GET['quote_id']) && $_GET['quote_id'] != NULL) {
    $quote_id = $_GET['quote_id'];
} else {
    $quote_id = '';
}
if(isset($_GET['rateid']) && $_GET['rateid'] != NULL) {
    $rateid = $_GET['rateid'];
} else {
    $rateid = '';
}
if(isset($_GET['product_id']) && $_GET['product_id'] != NULL) {
    $product_id = $_GET['product_id'];
} else {
    $product_id = '';
}
if(isset($_GET['agent']) && $_GET['agent'] != NULL) {
    $agent = $_GET['agent'];
} else {
    $agent = 'Admin';
}
if(isset($_GET['hour']) && $_GET['hour'] != NULL) {
    $hour = $_GET['hour'];
} else {
    $hour = '8pm';
}
if(isset($_GET['ware_email']) && $_GET['ware_email'] != NULL) {
    $ware_email = $_GET['ware_email'];
} else {
    $ware_email = 'admin@admin.com';
}
if(isset($_GET['ware_comp_name']) && $_GET['ware_comp_name'] != NULL) {
    $ware_comp_name = $_GET['ware_comp_name'];
} else {
    $ware_comp_name = 'brst';
}
if(isset($_GET['company']) && $_GET['company'] != NULL) {
    $company = $_GET['company'];
} else {
    $company = 'company';
}
if(isset($_GET['email']) && $_GET['email'] != NULL) {
    $email = $_GET['email'];
} else {
    $email = 'email@eamil.com';
}

  
  
  
  $xml='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <BookRate xmlns="http://freightcenter.com/API/V04/">
      <request>
        <LicenseKey>3bda740c-ff39-4bdc-ab49-b0a3c08e23b4</LicenseKey>
        <Username>'.$freightuser.'</Username>
         <Password>'.$freightpwd.'</Password>
        <RateId>'.$rateid.'</RateId>
        <PickupDateFrom>'.$date_from.'T09:00:00.000-04:00</PickupDateFrom>
        <PickupDateTo>'.$date_from.'T17:00:00.000-04:00</PickupDateTo>
        <Origin>
          <FirstName>'.$ware_fname.'</FirstName>
          <LastName>'.$ware_lname.'</LastName>
          <PhoneNumber>'.$warephone.'</PhoneNumber>
          <StreetAddress>'.$warestreet.'</StreetAddress>
          <City>'.$warecity.'</City>
          <State>'.$warestate.'</State>
          <PostalCode>'.$warepostcode.'</PostalCode>
          <Country>'.$warecountry.'</Country>
		  <EmailAddress>'.$ware_email.'</EmailAddress>
		  <Company>'.$ware_comp_name.'</Company>
		  <HoursOfOperation>'.$hour.'</HoursOfOperation>
        </Origin>
        <Destination>
          <FirstName>'.$fname.'</FirstName>
          <LastName>'.$lname.'</LastName>
          <PhoneNumber>'.$telephone.'</PhoneNumber>
          <StreetAddress>'.$street.'</StreetAddress>
          <City>'.$city.'</City>
          <State>'.$region.'</State>
          <PostalCode>'.$pincode.'</PostalCode>
          <Country>'.$country.'</Country>
		  <EmailAddress>'.$email.'</EmailAddress>
		  <Company>'.$company.'</Company>
		  <HoursOfOperation></HoursOfOperation>
        </Destination>
        <Agent><Name>'.$agent.'</Name></Agent>
      </request>
    </BookRate>
  </soap12:Body>
</soap12:Envelope>';
            $apiurl = $apirateurl;
            
            $headers = array(
                "POST /V04/rates.asmx HTTP/1.1",
                "Host: ".$host."",
                "Content-Type: application/soap+xml; charset=utf-8",
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
							//echo '<pre>'; print_r($vals); echo '</pre>';
					if(count($vals) != 0){
                            foreach($vals as $charges){
                                if($charges['tag'] == 'MESSAGE'){
                                    $total_charge = $charges['value'];
                                }
								if($charges['tag'] == 'SHIPMENTID'){
                                    $PREPRONUMBER = $charges['value'];
                                }
								
                            }
							
		$resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write'); 
        $tableNamep = $resource->getTableName('brst_freight_shipping');
		$query = "UPDATE `$tableNamep` SET bookingno='$PREPRONUMBER', status='$total_charge', date_from = '$date_from', date_to = '$date_from', warehouse_name='$ware_fname' where quote_id = $quote_id AND product_id = $product_id";
        $writeConnection->query($query);
?>
			
<?php  if($PREPRONUMBER){
echo $PREPRONUMBER;
}else if($total_charge){
echo $total_charge;
} else {
echo "Shipment not successfully booked";
}?>
    

<?php } else{ echo "Try Again";} ?>