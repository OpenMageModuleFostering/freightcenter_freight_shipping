<?php 
define('MAGENTO_ROOT', getcwd());
    $mageFilename = MAGENTO_ROOT . '/app/Mage.php';
    require_once $mageFilename;
    Mage::init();
	 $coreSession = Mage::getSingleton('core/session', array('name' => 'frontend'));
	  $cart = Mage::getModel('checkout/cart')->getQuote();
	  $subtotal =  Mage::helper('checkout/cart')->getQuote()->getSubtotal() ; 
/* get attribute options */
$attribute_f = Mage::getModel('eav/config')->getAttribute('catalog_product', 'freight_class');
foreach ( $attribute_f->getSource()->getAllOptions(true, true) as $opt_menuf)
{
    $f_attribute[$opt_menuf['value']] = $opt_menuf['label'];
}

$attribute1 = Mage::getModel('eav/config')->getAttribute('catalog_product', 'origin_warehouse');
foreach ( $attribute1->getSource()->getAllOptions(true, true) as $opt_menu)
{
    $m1_attribute[$opt_menu['value']] = $opt_menu['label'];
}


//echo $subtotal =  Mage::helper('checkout/cart')->getQuote()->getSubtotal() ; 

	  
	  $discount = Mage::getStoreConfig('carriers/freightcenter/discount');
	  if($discount == '1'){
		$discount_amount = Mage::getStoreConfig('carriers/freightcenter/discount_amount');
	  $discount_price = Mage::getStoreConfig('carriers/freightcenter/discount_price');
	 
	  }
	  else{
	  $discount_amount = $subtotal + 100;
	  $discount_price = '0';
	  }
$freightuser       = Mage::getStoreConfig('carriers/freightcenter/freightuser');
$freightpwd       = Mage::getStoreConfig('carriers/freightcenter/freightpwd');
 $markup = Mage::getStoreConfig('carriers/freightcenter/markup');
	  $markup_type = Mage::getStoreConfig('carriers/freightcenter/markup_type');
	  $markup_price = Mage::getStoreConfig('carriers/freightcenter/markup_price');




$sandbox = Mage::getStoreConfig('carriers/freightcenter/sandbox');
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
$myresult = array();
$ids = $_POST['proid'];
$address = $_POST['address'];
$product_location_type = $_POST['loctype'];
$quote_id = $_POST['quote'];
$loader = $_POST['img'];
$warehouse_postcode = $_POST['warehouse_postcode'];
if(isset($_POST['carrier_radio_select'])) {
    $carrier_radio_select = $_POST['carrier_radio_select'];
} else {
    $carrier_radio_select = 'AAACOOPER';
}
$getids = explode(';',$ids);
$codes = '';
if(isset($_POST['accessorials'])) {
    $accessorials = $_POST['accessorials'];
    foreach ($accessorials as $access) {
        $codes .= '<string>'.$access.'</string>';
    }
}
 $CarrierCode = Mage::getStoreConfig('carriers/freightcenter/positions');
$Carrierdisplay = Mage::getStoreConfig('carriers/freightcenter/carrier_display');
$CarrierCode = explode("," , $CarrierCode);
	  //echo count($CarrierCode);
	 /*  if( $Carrierdisplay > count($CarrierCode))
	  {
		$Carrierdisplay = count($CarrierCode);
	  }
	   */
	    $carriercount = count($CarrierCode);
	   for($modz=0;$modz<$carriercount; $modz++){
	   
	  $carrier_multi_rate .= " <Carrier>
              <RatesRequest_Carrier>
                <CarrierCode>".$CarrierCode[$modz]."</CarrierCode>
               
              </RatesRequest_Carrier>
            </Carrier>" ;
        }
/* make group of products when in same warehouse */
$ware_array = array();
$cart_products = array();
foreach($getids as $prodid) {
    if($prodid != NULL) {
        $explode = explode(' ',$prodid);
        $productid = $explode[0];
        $qtyordered = $explode[1];
        $cart_products[$productid] = $qtyordered;
        $proarray = Mage::getModel('catalog/product')->load($productid);
        $warehouseid = $m1_attribute[$proarray['origin_warehouse']];
		
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $ware_query = "SELECT * FROM `brst_ship_warehouses` WHERE short_name='$warehouseid'";
        $ware_results = $connection->fetchAll($ware_query);
		//echo "<pre>";print_r($ware_results);exit;
        if($ware_results != NULL) {
            $warehousename = $ware_results[0]['short_name'];
        }

        if (array_key_exists($warehousename, $ware_array)) {
            array_push($ware_array[$warehousename],$proarray);
        } else {
            $ware_array[$warehousename] = array($proarray);
        }
    }
}


//echo "<pre>";print_r($ware_array);exit;
$ware_count = count($ware_array);
 $discount_price = $discount_price/$ware_count; 
 $markup_price = $markup_price/$ware_count;
foreach($ware_array as $key=>$value) {
    $pro_item = '';
    foreach($value as $mainarray) {
        $pro_model = $mainarray;
        $proid = $pro_model['entity_id'];
        $des = str_replace(' ','',$pro_model['description']);
        $flen = $pro_model['freight_length'];
        $fwidth = $pro_model['freight_width'];
        $fheight = $pro_model['freight_height'];
        $nmfc = $pro_model['nmfc'];
        $quantity = $cart_products[$proid];
        $weight = ($pro_model['weight'] * $quantity);
        
        $fclass = $f_attribute[$pro_model['freight_class']];
        $ware_id = $m1_attribute[$pro_model['origin_warehouse']];
        
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read'); 
        $tableNamep = $resource->getTableName('brst_ship_warehouses');
        $queryp = "SELECT * FROM $tableNamep WHERE short_name ='$ware_id'" ;
        $results = $readConnection->fetchAll($queryp); 
        //echo "<pre>";print_r($results);exit;
        foreach($results as $ware_result)
        {
            $warehouse_location_type = str_replace(' ','',$ware_result['location_type']);
			$warehouse_postcode = $ware_result['cmpny_postcode'];
			$accessorials = $ware_result['accessorials'];
							$accessorials = explode(',', $accessorials);
							//print_r($accessorials);
							$accessorialsdisplay  = count($accessorials);
							 $print_accessorials = '';
							 for($modz=0;$modz<$accessorialsdisplay; $modz++){
							 
							//$print_accessorials ="<p>".$accessorials[$modz]."</p>" 
							 if($accessorials[$modz] == '1'){
							 $print_accessorials .="<string>ORIGIN_LIFT_GATE</string>" ;
							 }
							 if($accessorials[$modz] == '2'){
							 $print_accessorials .="<string>ORIGIN_LIMITED_PU</string>" ;
							 }
							 if($accessorials[$modz] == '3'){
							 $print_accessorials .="<string>ORIGIN_INSIDE_PU</string>" ;
							 }
							 if($accessorials[$modz] == '4'){
							 $print_accessorials .="<string>ORIGIN_CALL_FOR_APPT</string>" ;
							 }
						}
						//ECHO $codes$print_accessorials;
        }
        
        $pro_item .= '<RatesRequest_Item>
                        <Description>'.$des.'</Description>
                        <PackagingCode>Palletized</PackagingCode>
                        <Quantity>'.$quantity.'</Quantity>
                        <LinearFeet>0</LinearFeet>
                        <Dimensions>
                          <Length>'.$flen.'</Length>
                          <Width>'.$fwidth.'</Width>
                          <Height>'.$fheight.'</Height>
                          <UnitOfMeasure>IN</UnitOfMeasure>
                        </Dimensions>
                        <FreightClass>'.$fclass.'</FreightClass>
                        <Weight>
                          <WeightAmt>'.$weight.'</WeightAmt>
                          <UnitOfMeasure>LBS</UnitOfMeasure>
                        </Weight>
                        <Nmfc>'.$nmfc.'</Nmfc>
                      </RatesRequest_Item>';
        
    }
        
        /* send api request */
        $xml='<?xml version="1.0" encoding="utf-8"?>
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
          <soap12:Body>
            <GetRates xmlns="http://freightcenter.com/API/V04/">
              <request>
                <LicenseKey>3BDA740C-FF39-4BDC-AB49-B0A3C08E23B4</LicenseKey>
                <Username>'.$freightuser.'</Username>
                <Password>'.$freightpwd.'</Password>
                <OriginPostalCode>'.$warehouse_postcode.'</OriginPostalCode>
                <OriginLocationType>'.$warehouse_location_type.'</OriginLocationType>
                <DestinationPostalCode>'.$address.'</DestinationPostalCode>
                <DestinationLocationType>'.$product_location_type.'</DestinationLocationType>
                <Items>'.$pro_item.'</Items>
                <Accessorials>'.$codes.''.$print_accessorials.'</Accessorials>
                <Filters>
                                      <Mode>LTL</Mode>
                                      <IncludeWhiteGlove>false</IncludeWhiteGlove>
                                      <IncludeMotorcycleRates>false</IncludeMotorcycleRates>
                                      <CarrierFilter>
                                        <CarrierFilterType>INCLUDE</CarrierFilterType>
                                        '.$carrier_multi_rate.'
                                      </CarrierFilter>
                                    </Filters>
              </request>
            </GetRates>
          </soap12:Body>
        </soap12:Envelope>';

        $apiurl = $apirateurl;
        $headers = array(
            "POST  /V04/rates.asmx HTTP/1.1",
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
     //  echo "<pre>";print_r($vals);
	  $yes = "'yes'";
	 if($vals['5']['value'] == 'ERROR'){
					$response =	'<div><p>
							There was a problem with the freight.  Do you want to check-out with only non-freight products?
							</p>
							<button  onclick="removefreight('.$yes.')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button></div>';

 }
 else if($vals['5']['value'] == 'WARNING'){
 $response =	'<div><p>
							There was a problem with the freight.  Do you want to check-out with only non-freight products?
							</p>
							<button  onclick="removefreight('.$yes.')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button></div>';
 }
  else if(empty($vals)){
  $response =	'<div><p>
							There was a problem with the freight.  Do you want to check-out with only non-freight products?
							</p>
							<button  onclick="removefreight('.$yes.')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button></div>';
 
 
 }
 else{
			
         $xmlResponseArray = array();
							foreach($vals as $charges){
							
								if (in_array("TOTALCHARGE", $charges)) {
									$xmlResponseArray['TOTALCHARGE'][] = 	$charges['value'];
								}
								if (in_array("SERVICENAME", $charges)) {
									$xmlResponseArray['SERVICENAME'][] = 	$charges['value'];
								}
								if (in_array("SERVICEDAYS", $charges)) {
											$xmlResponseArray['SERVICEDAYS'][] = 	$charges['value'];
								}
								if (in_array("RATEID", $charges)) {
									$xmlResponseArray['RATEID'][] = 	$charges['value'];
								}
							}	
						$xmlResponse = array();
							$countedCharges =  count($xmlResponseArray['TOTALCHARGE']);
							if($subtotal > $discount_amount){
						$Carrierd = 1;
						}else{
						$Carrierd = $Carrierdisplay;
						}
						
							for($j=0;$j<$Carrierd;$j++){
								$xmlResponse[$j][] = $xmlResponseArray['TOTALCHARGE'][$j];
								$xmlResponse[$j][] = $xmlResponseArray['SERVICENAME'][$j];
								$xmlResponse[$j][] = $xmlResponseArray['SERVICEDAYS'][$j];
								$xmlResponse[$j][] = $xmlResponseArray['RATEID'][$j];
							}
							$response = '';
                           foreach($xmlResponse as $xmlResponse2){
								
                                $total_charge = $xmlResponse2[0];
                                $carriers_value = $xmlResponse2[1];
                                $business_day = $xmlResponse2[2];
								 $RATEID = $xmlResponse2[3];
								 if($total_charge == ''){}else { 
		if($carriers_value == NULL){
		  $response = "No Result Found, Please try another Carrier";
		}else{
		/* if($subtotal > $discount_amount){
		if($total_charge < $discount_price){
						} else{
						$total_charge = $discount_price;
						}
		
					} */	
					/* if($markup == 'none'){
						//$gettotal = $myPrice;
							 $total_charge;
										}
					
						if($markup == 'markup'){
										if($markup_type == 'percent'){
										   $gettotal_pr = ($total_charge *  $markup_price)/100;
										   $total_charge = $total_charge + $gettotal_pr;
										   
											}
											else{
											$total_charge = $total_charge + $markup_price ; 
											
											}
			}
			
			if($markup == 'discount'){
										 if($markup_type == 'percent'){
										   $gettotal_pr = ($total_charge *  $markup_price)/100;
										   $total_charge = $total_charge - $gettotal_pr;
										    }
											else{
											$total_charge = $total_charge - $markup_price ; 
											}
											} */
											if($markup == 'none'){
						$gettotal = $total_charge;
						 if($subtotal > $discount_amount){
						//echo $total_charge;
						if($gettotal < $discount_price){
						} 
						else{
						$gettotal = $discount_price;
						}
						}
						
										}
					
						if($markup == 'markup'){
						$gettotal = $total_charge;
										if($markup_type == 'percent'){
										$markup_price =	Mage::getStoreConfig('carriers/freightcenter/markup_price');
										   $gettotal_pr = ($gettotal *  $markup_price)/100;
										  $gettotal = $gettotal + $gettotal_pr;
										   }
											else{
											//$markup_price =	Mage::getStoreConfig('carriers/freightcenter/markup_price');
											$gettotal = $gettotal + $markup_price ; 
											}
											
						if($subtotal > $discount_amount){
						//echo $total_charge;
						if($gettotal < $discount_price){
						} 
						else{
						$gettotal = $discount_price;
						}
						}			
			}
			
			if($markup == 'discount'){
			$gettotal = $total_charge;
											if($markup_type == 'percent'){
											$markup_price =	Mage::getStoreConfig('carriers/freightcenter/markup_price');
										   $gettotal_pr = ($gettotal *  $markup_price)/100;
										  $gettotal = $gettotal - $gettotal_pr;
										    }
											else{
											//$markup_price =	Mage::getStoreConfig('carriers/freightcenter/markup_price');
											$gettotal = $gettotal - $markup_price ; 
											}
						if($subtotal > $discount_amount){
						//echo $total_charge;
						if($gettotal < $discount_price){
						} 
						else{
						$gettotal = $discount_price;
						}
						}			
									
									
											
											}
											
											 if($gettotal < $total_charge){ 
						 $discount_get = $total_charge - $gettotal; 
						 $discount_get = number_format($discount_get, 2, '.', ','); 
						 $discount_ge =  '</br>Discount:- <span>$'.$discount_get.'</span>'; 
						 $total_cha =  number_format($total_charge, 2, '.', ','); 
						 $total =  ' </br>Cost:  <span>$'.number_format($total_charge, 2, '.', ',').'</span>'; 
						 }else if ($gettotal < $total_charge){
						 $gettotal = $total_charge;
						 //$gettotal = number_format($gettotal, 2, '.', ',');
						 }
		
        $response .= '<div style="display: block; margin-bottom: 20px; width: 100%;" class="ajax-result" id="ajax_result'.$proid.'">
            
            <input type="radio" class="ajax" value="ajax_response" name="shipping'.$proid.'" onclick="ajaxcallnew('.$proid.','.$gettotal.','.$quote_id.','."'$carriers_value'".','."'$RATEID'".','."'$business_day'".');" id="ajax'.$proid.'" />
            <label for="ajax'.$proid.'"><span style="font-weight:bold;">'.$carriers_value.'</span>  <span style="display:none;" id="apply_rate'.$proid.'">
            <img class="v-middle" title="Applying Carrier Rate..." alt="Applying Carrier Rate..." src="'.$loader.'" />
            </span><br/>Estimated Transit Time: '.$business_day.' Business days</label>
          '.$total.'
		 '.$discount_ge.'
		  </br>You Pay: <span>$'.number_format($gettotal, 2, '.', ',').'</span>
		  </div>';
        
}
}

}
}

$myresult[$proid] = $response;
}
$response = '';
//echo "<pre>";print_r($myresult);exit;
$gotresult['rates'] = $myresult;
echo json_encode($gotresult);
exit;