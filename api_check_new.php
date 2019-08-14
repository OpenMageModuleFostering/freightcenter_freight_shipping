<?php
define('MAGENTO_ROOT', getcwd());
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require_once $mageFilename;
Mage::init();
$coreSession = Mage::getSingleton('core/session', array(
    'name' => 'frontend'
));
$cart        = Mage::getModel('checkout/cart')->getQuote();

$freightuser       = Mage::getStoreConfig('carriers/freightcenter/freightuser');
$freightpwd       = Mage::getStoreConfig('carriers/freightcenter/freightpwd');
$subtotal     = Mage::helper('checkout/cart')->getQuote()->getSubtotal();
$markup       = Mage::getStoreConfig('carriers/freightcenter/markup');
$markup_type  = Mage::getStoreConfig('carriers/freightcenter/markup_type');
$markup_price = Mage::getStoreConfig('carriers/freightcenter/markup_price');
$discount     = Mage::getStoreConfig('carriers/freightcenter/discount');
if ($discount == '1') {
    $discount_amount = Mage::getStoreConfig('carriers/freightcenter/discount_amount');
    $discount_price  = Mage::getStoreConfig('carriers/freightcenter/discount_price');
    
} else {
    $discount_amount = $subtotal + 100;
    $discount_price  = '0';
}


$sandbox        = Mage::getStoreConfig('carriers/freightcenter/sandbox');
$apishipmenturl = Mage::getStoreConfig('carriers/freightcenter/apishipmenturl');
$apicarriersurl = Mage::getStoreConfig('carriers/freightcenter/apicarriersurl');
$apirateurl     = Mage::getStoreConfig('carriers/freightcenter/apirateurl');
if ($sandbox == '1') {
    $host           = "sandbox.freightcenter.com";
    $apishipmenturl = "http://sandbox.freightcenter.com/v04/shipments.asmx";
    $apirateurl     = "http://sandbox.freightcenter.com/v04/rates.asmx";
    $apicarriersurl = "http://sandbox.freightcenter.com/v04/carriers.asmx";
} else {
    $host           = "api.freightcenter.com";
    $apishipmenturl = "http://api.freightcenter.com/v04/shipments.asmx";
    $apirateurl     = "http://api.freightcenter.com/v04/rates.asmx";
    $apicarriersurl = "http://api.freightcenter.com/v04/carriers.asmx";
}
$myarray     = array();
$non_freight = array();
foreach ($cart->getAllItems() as $item) {
    $productID    = $item->getProduct()->getId();
    $productPrice = $item->getProduct()->getPrice();
    $qtyordered   = $item->getQty();
    
    $product          = Mage::getModel('catalog/product')->load($productID);
    $ship_via_freight = $product->getData('ship_via_freight');
    if ($ship_via_freight != 1) {
        $non_freight[] = $product;
?>
<!--<style>.other_method { display:none; } </style>-->
<?php
    } else {
        $myarray[] = $product;
    }
    
}

$quote_id = $_GET['quote_id'];
$weights  = $_GET['weight'];
if (isset($_GET['loc_val'])) {
    $loc_val = $_GET['loc_val'];
} else {
    $loc_val = 'Residential';
}
if (isset($_GET['address'])) {
    $address = $_GET['address'];
} else {
    $address = '70816';
}
$codes = '';
if (isset($_GET['accessorials'])) {
    $accessorials        = $_GET['accessorials'];
    $accessorials        = explode(",", $accessorials);
    $accessorialsdisplay = count($accessorials);
    for ($modz = 0; $modz < $accessorialsdisplay; $modz++) {
        $codes .= '<string>' . $accessorials[$modz] . '</string>';
    }
    
} else {
    $codes = '';
}

$CarrierCode    = Mage::getStoreConfig('carriers/freightcenter/positions');
$Carrierdisplay = Mage::getStoreConfig('carriers/freightcenter/carrier_display');
$CarrierCode    = explode(",", $CarrierCode);
//echo count($CarrierCode);
/* if( $Carrierdisplay > count($CarrierCode))
{
$Carrierdisplay = count($CarrierCode);
} */
$carriercount   = count($CarrierCode);
?>
<!--<div id="carrier_radio_select_div"></div>-->
<!--<p style="font-size: 15px; font-weight: 600;">Select Carrier</p>-->
<?php
for ($modz = 0; $modz < $carriercount; $modz++) {
    
    $carrier_multi_rate .= " <Carrier>
              <RatesRequest_Carrier>
                <CarrierCode>" . $CarrierCode[$modz] . "</CarrierCode>
                
              </RatesRequest_Carrier>
            </Carrier>";
}
//echo '<pre>';  print_r($myarray); echo '</pre>';
$attribute_f = Mage::getModel('eav/config')->getAttribute('catalog_product', 'freight_class');
foreach ($attribute_f->getSource()->getAllOptions(true, true) as $opt_menuf) {
    $f_attribute[$opt_menuf['value']] = $opt_menuf['label'];
}

$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'packaging_type');
foreach ($attribute->getSource()->getAllOptions(true, true) as $opt_menu1) {
    $m_attribute[$opt_menu1['value']] = $opt_menu1['label'];
}

$attribute1 = Mage::getModel('eav/config')->getAttribute('catalog_product', 'origin_warehouse');
foreach ($attribute1->getSource()->getAllOptions(true, true) as $opt_menu) {
    $m1_attribute[$opt_menu['value']] = $opt_menu['label'];
}
$ware_array = array();
foreach ($myarray as $proarray) {
    $warehouseid = $m1_attribute[$proarray['origin_warehouse']];
    
    $connection   = Mage::getSingleton('core/resource')->getConnection('core_read');
    $ware_query   = "SELECT * FROM `brst_ship_warehouses` WHERE short_name = '$warehouseid'";
    $ware_results = $connection->fetchAll($ware_query);
    if ($ware_results != NULL) {
        $warehousename = $ware_results[0]['short_name'];
    }
    
    if (array_key_exists($warehousename, $ware_array)) {
        array_push($ware_array[$warehousename], $proarray);
    } else {
        $ware_array[$warehousename] = array(
            $proarray
        );
    }
}
$myPrice            = '';
$ware_count         = count($ware_array);
$discount_price_new = $discount_price / $ware_count;
$markup_price_new   = $markup_price / $ware_count;
foreach ($ware_array as $key => $value) {
    //$start = 1;
    $pro_item  = '';
    $pro_names = '';
    foreach ($value as $mainarray) {
        $pro_model           = $mainarray;
        $weight              = ($pro_model['weight'] * $qtyordered);
        $product_id          = $mainarray['entity_id'];
        $product_description = str_replace(' ', '', $mainarray['description']);
        $product_name        = $mainarray['name'];
        $freight_length      = $mainarray['freight_length'];
        $freight_width       = $mainarray['freight_width'];
        $freight_height      = $mainarray['freight_height'];
        $nmfc                = $mainarray['nmfc'];
        // $quantity = $cart_products[$product_id];
        // $weight = ($mainarray['weight'] * $quantity);
        
        $freight_class = $f_attribute[$mainarray['freight_class']];
        //$packaging_type = $m_attribute[$mainarray['packaging_type']];
        $ware_id       = $m1_attribute[$mainarray['origin_warehouse']];
        
        $resource       = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $tableNamep     = $resource->getTableName('brst_ship_warehouses');
        $queryp         = "SELECT * FROM  $tableNamep WHERE short_name = '$ware_id' LIMIT 10";
        
        $results = $readConnection->fetchAll($queryp);
        //echo "<pre>";print_r($results);exit; 
        foreach ($results as $ware_result) {
            $warehouse_name_i        = $ware_result['short_name'];
            $warehouse_location_type = str_replace(' ', '', $ware_result['location_type']);
            $accessorials            = $ware_result['accessorials'];
            $accessorials            = explode(',', $accessorials);
            //print_r($accessorials);
            $accessorialsdisplay     = count($accessorials);
            $print_accessorials      = '';
            for ($modz = 0; $modz < $accessorialsdisplay; $modz++) {
                
                //$print_accessorials ="<p>".$accessorials[$modz]."</p>" 
                if ($accessorials[$modz] == '1') {
                    $print_accessorials .= "<string>ORIGIN_LIFT_GATE</string>";
                }
                if ($accessorials[$modz] == '2') {
                    $print_accessorials .= "<string>ORIGIN_LIMITED_PU</string>";
                }
                if ($accessorials[$modz] == '3') {
                    $print_accessorials .= "<string>ORIGIN_INSIDE_PU</string>";
                }
                if ($accessorials[$modz] == '4') {
                    $print_accessorials .= "<string>ORIGIN_CALL_FOR_APPT</string>";
                }
            }
            //ECHO $print_accessorials;
?>

<input type="hidden" value="<?php
            echo $warehouse_location_type;
?>" id="hidden_warehouse_location" />
<?php
            $warehouse_postcode = $ware_result['cmpny_postcode'];
            $city               = $ware_result['city'];
            $state              = $ware_result['state'];
            //$location_type = $ware_result['location_type'];
?>
<input type="hidden" value="<?php
            echo $warehouse_postcode;
?>" id="warehouse_postcode" />
<?php
        }
        // echo $apirateurl;
        $pro_names .= '<h3>' . $product_name . '</h3>';
        
        $pro_item .= '<RatesRequest_Item>
                                        <Description>' . $product_description . '</Description>
                                        <PackagingCode>Palletized</PackagingCode>
                                        <Quantity>' . $qtyordered . '</Quantity>
                                        <LinearFeet>0</LinearFeet>
                                        <Dimensions>
                                          <Length>' . $freight_length . '</Length>
                                          <Width>' . $freight_width . '</Width>
                                          <Height>' . $freight_height . '</Height>
                                          <UnitOfMeasure>IN</UnitOfMeasure>
                                        </Dimensions>
                                        <FreightClass>' . $freight_class . '</FreightClass>
                                        <Weight>
                                          <WeightAmt>' . $weight . '</WeightAmt>
                                          <UnitOfMeasure>LBS</UnitOfMeasure>
                                        </Weight>
                                        <Nmfc>' . $nmfc . '</Nmfc>
                                      </RatesRequest_Item>';
    }
    
    
    $xml     = '<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <GetRates xmlns="http://freightcenter.com/API/V04/">
      <request>
        <LicenseKey>3BDA740C-FF39-4BDC-AB49-B0A3C08E23B4</LicenseKey>
        <Username>'.$freightuser.'</Username>
      <Password>'.$freightpwd.'</Password>
        <OriginPostalCode>' . $warehouse_postcode . '</OriginPostalCode>
        <OriginLocationType>' . $warehouse_location_type . '</OriginLocationType>
        <DestinationPostalCode>' . $address . '</DestinationPostalCode>
        <DestinationLocationType>' . $loc_val . '</DestinationLocationType>
       <Items>' . $pro_item . '</Items>
         <Accessorials>' . $codes . '' . $print_accessorials . '</Accessorials>
        <Filters>
                                      <Mode>LTL</Mode>
                                      <IncludeWhiteGlove>false</IncludeWhiteGlove>
                                      <IncludeMotorcycleRates>false</IncludeMotorcycleRates>
                                      <CarrierFilter>
                                        <CarrierFilterType>INCLUDE</CarrierFilterType>
                                        ' . $carrier_multi_rate . '
                                      </CarrierFilter>
                                    </Filters>
      </request>
    </GetRates>
  </soap12:Body>
</soap12:Envelope>';
    $apiurl  = $apirateurl;
    // print_r($xml); 
    $headers = array(
        "POST  /V04/rates.asmx HTTP/1.1",
        "Host: " . $host . "",
        "Content-Type: text/xml; charset=utf-8",
        "Content-length: " . strlen($xml)
    );
    //print_R($xml); 
    /* send curl request to get active carriers */
    $soap_do = curl_init();
    curl_setopt($soap_do, CURLOPT_URL, $apiurl);
    curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($soap_do, CURLOPT_POST, true);
    curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($soap_do, CURLOPT_TIMEOUT, 400);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($soap_do, CURLOPT_POSTFIELDS, $xml);
    
    $result = curl_exec($soap_do);
    
    curl_close($soap_do);
    
    $p = xml_parser_create();
    xml_parse_into_struct($p, $result, $vals, $index);
    xml_parser_free($p);
    //echo '<pre>'; print_r($vals);echo '</pre>'; 
    $vals['5']['value'];
    if ($vals['5']['value'] == 'ERROR') {
    } else if ($vals['5']['value'] == 'WARNING') {
    } else if (empty($vals)) {
    }
    
    else {
        $xmlResponseArray = array();
        foreach ($vals as $charges) {
            
            if (in_array("TOTALCHARGE", $charges)) {
                $xmlResponseArray['TOTALCHARGE'][] = $charges['value'];
            }
            if (in_array("SERVICENAME", $charges)) {
                $xmlResponseArray['SERVICENAME'][] = $charges['value'];
            }
            if (in_array("SERVICEDAYS", $charges)) {
                $xmlResponseArray['SERVICEDAYS'][] = $charges['value'];
            }
            if (in_array("RATEID", $charges)) {
                $xmlResponseArray['RATEID'][] = $charges['value'];
            }
        }
        //echo '<pre>';print_r($xmlResponseArray);
        $xmlResponse    = array();
        $countedCharges = count($xmlResponseArray['TOTALCHARGE']);
        for ($j = 0; $j <= $Carrierdisplay; $j++) {
            $xmlResponse[$j][] = $xmlResponseArray['TOTALCHARGE'][$j];
            $xmlResponse[$j][] = $xmlResponseArray['SERVICENAME'][$j];
            $xmlResponse[$j][] = $xmlResponseArray['SERVICEDAYS'][$j];
            $xmlResponse[$j][] = $xmlResponseArray['RATEID'][$j];
            
        }
        
        
        //	echo '<pre>'; print_r($xmlResponse); echo '</pre>';
        $total_chargeArr = array();
        $carriers_value  = array();
        $RATEID          = array();
        $business_day    = array();
        $business        = array();
        foreach ($xmlResponse as $xmlResponse2) {
            //echo $xmlResponse2[0];
            $total_chargeArr[] = $xmlResponse2[0];
            //$myPrice = $total_charge  + $myPrice;
            $carriers_value[]  = $xmlResponse2[1];
            $business_day[]    = $xmlResponse2[2];
            $business[]        = $xmlResponse2[2];
            $newArray[]        = $business_day;
            $business_day      = array();
            $RATEID[]          = $xmlResponse2[3];
            //
        }
        $days = array();
        foreach ($newArray as $ayy) {
            //print_r(array_filter($ayy));
            $days[] = max($ayy);
            //echo '</br>';
        }
        $bussi          = array_filter($days);
        $day            = max($bussi);
        //echo '<pre>'; print_r(array_filter($days)); echo '</pre>';
        $total_charge   = $total_chargeArr[0];
        $carriers_value = $carriers_value[0];
        $business       = $business[0];
        $RATEID         = $RATEID[0];
        $business_day   = $business_day[0];
        //$myPrice = $getprice + $myPrice;
        //$myPrice = $total_charge  + $myPrice;
        
        $resource        = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        /*  $tableNamep = $resource->getTableName('brst_freight_shipping');
        $query = "INSERT INTO ".$tableNamep." (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name) VALUES ('$quote_id','$product_id','$total_charge','$carriers_value','$loc_val','$RATEID','$warehouse_name_i')";
        $writeConnection->query($query); */
        
        $tableNamep   = $resource->getTableName('brst_freight_shipping');
        $select       = "Select * from $tableNamep where product_id='$product_id' AND quote_id='$quote_id'";
        $qry          = $writeConnection->query($select);
        $product_info = $qry->fetchAll();
        //echo "<pre>";print_r($product_info);exit;
        
        
        
        if ($markup == 'none') {
            if ($product_info == NULL) {
                
                if ($subtotal > $discount_amount) {
                    if ($total_charge < $discount_price_new) {
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$total_charge','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business','$total_charge')";
                    } else {
                        //$total_charge = $discount_price_new;
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$discount_price_new','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business','$total_charge')";
                    }
                    
                } else {
                    $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$total_charge','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business','$total_charge')";
                    
                }
                $writeConnection->query($query);
            } else {
                $rowid = $product_info[0]['id'];
                if ($subtotal > $discount_amount) {
                    if ($total_charge < $discount_price_new) {
                        
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$discount_price_new',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business' ,grandship = '$total_charge' where id=$rowid";
                    } else {
                        
                        //$total_charge = $discount_price_new;
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$discount_price_new',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    }
                    
                } else {
                    
                    $query = "UPDATE `$tableNamep` SET shipping_charge='$total_charge',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business' ,grandship = '$total_charge' where id=$rowid";
                }
                $writeConnection->query($query);
            }
            
            
        }
        if ($markup == 'markup') {
            
            if ($product_info == NULL) {
                
                if ($markup_type == 'percent') {
                    $markup_price_new = Mage::getStoreConfig('carriers/freightcenter/markup_price');
                    $gettotal_pr      = ($total_charge * $markup_price_new) / 100;
                    $gettotal_q       = $total_charge + $gettotal_pr;
                } else {
                    $gettotal_q = $total_charge + $markup_price_new;
                }
                
                
                if ($subtotal > $discount_amount) {
                    if ($gettotal_q < $discount_price_new) {
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$gettotal_q','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    } else {
                        //$total_charge = $discount_price_new;
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$discount_price_new','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    }
                    
                } else {
                    
                    $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$gettotal_q','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    
                }
                $writeConnection->query($query);
            } else {
                $rowid = $product_info[0]['id'];
                if ($markup_type == 'percent') {
                    $markup_price_new = Mage::getStoreConfig('carriers/freightcenter/markup_price');
                    $gettotal_pr      = ($total_charge * $markup_price_new) / 100;
                    $gettotal_q       = $total_charge + $gettotal_pr;
                } else {
                    $gettotal_q = $total_charge + $markup_price_new;
                }
                if ($subtotal > $discount_amount) {
                    if ($gettotal_q < $discount_price_new) {
                        
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$gettotal_q',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    } else {
                        //$total_charge = $discount_price_new;
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$discount_price_new',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    }
                    
                } else {
                    if ($gettotal_q < $discount_price_new) {
                        
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$gettotal_q',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    } else {
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$discount_price_new',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                        
                    }
                }
                $writeConnection->query($query);
            }
        }
        
        if ($markup == 'discount') {
            if ($markup_type == 'percent') {
                $markup_price_new = Mage::getStoreConfig('carriers/freightcenter/markup_price');
                $gettotal_pr      = ($total_charge * $markup_price_new) / 100;
                $gettotal_q       = $total_charge - $gettotal_pr;
                
            } else {
                $gettotal_q = $total_charge - $markup_price_new;
                
            }
            
            if ($product_info == NULL) {
                
                
                if ($subtotal > $discount_amount) {
                    if ($gettotal_q < $discount_price_new) {
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$gettotal_q','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    } else {
                        //$total_charge = $discount_price_new;
                        
                        $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$discount_price_new','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    }
                    
                } else {
                    $query = "INSERT INTO " . $tableNamep . " (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid, warehouse_name, days,grandship) VALUES ('$quote_id','$product_id','$gettotal_q','$carriers_value','$loc_val','$RATEID','$warehouse_name_i', '$business', '$total_charge')";
                    
                }
                $writeConnection->query($query);
            } else {
                if ($markup_type == 'percent') {
                    $markup_price_new = Mage::getStoreConfig('carriers/freightcenter/markup_price');
                    $gettotal_pr      = ($total_charge * $markup_price_new) / 100;
                    $gettotal_q       = $total_charge + $gettotal_pr;
                } else {
                    $gettotal_q = $total_charge + $markup_price_new;
                }
                
                $rowid = $product_info[0]['id'];
                if ($subtotal > $discount_amount) {
                    if ($gettotal_q < $discount_price_new) {
                        
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$gettotal_q',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    } else {
                        
                        //$total_charge = $discount_price_new;
                        $query = "UPDATE `$tableNamep` SET shipping_charge='$discount_price_new',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                    }
                    
                } else {
                    
                    $query = "UPDATE `$tableNamep` SET shipping_charge='$gettotal_q',carrier_name='$carriers_value', final_dest='$loc_val',rateid='$RATEID', days = '$business',grandship = '$total_charge' where id=$rowid";
                }
                $writeConnection->query($query);
            }
            
        }
        $myPrice = $total_charge + $myPrice;
    }
}
/*  $resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write'); 
$tableNamep = $resource->getTableName('brst_freight_shipping');
$query = "INSERT INTO ".$tableNamep." (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid) VALUES ('$quote_id','$product_id','$total_charge','$carriers_value','$loc_val','$RATEID')";
$writeConnection->query($query); */
// $myPrice;
//echo $myPrice;
if ($vals['5']['value'] == 'ERROR') {
?>
<div>
	<p> There was a problem with the freight.  Do you want to check-out with only non-freight products? </p>
	<button  onclick="removefreight('yes')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button>
</div>
<?php
} else if ($vals['5']['value'] == 'WARNING') {
?>
There was a problem with the freight.  Do you want to check-out with only non-freight products?
</p>
<button  onclick="removefreight('yes')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button>
</div>
<?php
}

else if (empty($vals)) {
?>
There was a problem with the freight.  Do you want to check-out with only non-freight products?
</p>
<button  onclick="removefreight('yes')" class="button" title="Remove Freight Products" type="button"><span><span>Yes</span></span></button>
</div>
<?php
} else {
    
    if ($markup == 'none') {
        $gettotal = $myPrice;
        /* if($subtotal > $discount_amount){
        if($gettotal < $discount_price){
        $gettotal;
        } else{
        $gettotal =  $discount_price;
        }
        }
        else{
        $gettotal;
        } */
    }
    if ($markup == 'markup') {
        if ($markup_type == 'percent') {
            $gettotal_pr = ($myPrice * $markup_price) / 100;
            $gettotal    = $myPrice + $gettotal_pr;
            /* if($subtotal > $discount_amount){
            if($gettotal > $discount_price){
            $gettotal = $discount_price;
            }
            } */
        } else {
            $gettotal = $myPrice + $markup_price;
            /* if($subtotal > $discount_amount){
            if($gettotal > $discount_price){
            $gettotal = $discount_price;
            }
            } */
        }
    }
    
    if ($markup == 'discount') {
        
        if ($markup_type == 'percent') {
            $gettotal_pr = ($myPrice * $markup_price) / 100;
            $gettotal    = $myPrice - $gettotal_pr;
            /*  if($subtotal > $discount_amount){
            if($gettotal > $discount_price){
            $gettotal = $discount_price;
            }
            } */
        } else {
            $gettotal = $myPrice - $markup_price;
            /* if($subtotal > $discount_amount){
            if($gettotal > $discount_price){
            $gettotal = $discount_price;
            }
            } */
        }
    }
    
    
    if ($subtotal > $discount_amount) {
        if ($gettotal > $discount_price) {
            $gettotal_add = $discount_price;
        } else {
            $gettotal_add = $gettotal;
        }
    } else {
        $gettotal_add = $gettotal;
    }
?>
<div style="clear:both;height:16px;"></div>
<div class="ajax-result" id="ajax_result">
<table><tr><td width="20">
	<input style="float: left;"  type="radio" class="ajax" value="ajax_response" name="shipping<?php
    print $product_id;
?>" onclick="ajaxcallnew_no('<?php
    print $product_id;
?>','<?php
    print $gettotal_add;
?>','<?php
    echo $quote_id;
?>','<?php
    echo $carriers_value;
?>','<?php
    echo $RATEID;
?>');" id="ajax<?php
    print $product_id;
?>" />
</td><td>
	<div> <span for="ajax<?php
    print $product_id;
?>"> Estimated Transit Time: <?php
    echo $day;
?> Business days </span> </div>
	<?php
    if ($markup == 'none') {
        
        if ($gettotal > $gettotal_add) {
?>
	Cost: $<?php
            
            
            echo $gettotal = number_format($gettotal, 2, '.', ',');
?>
	</br>
	Discount: -$<?php
            
            $discount_get = $gettotal - $discount_price;
            echo $discount_get = number_format($discount_get, 2, '.', ',');
            
?>
	</br>
	<?php
        }
    }
?>
	<?php
    if ($markup == 'markup') {
        // $gettot= number_format($gettotal, 2, '.', '');
        //echo $gettotal_add;
        if ($gettotal > $gettotal_add) {
?>
	Cost: $<?php
            echo $getto = number_format($gettotal, 2, '.', ',');
?>
	</br>
	Discount: -$<?php
            
            $discount_get = $gettotal - $discount_price;
            echo $discount_get = number_format($discount_get, 2, '.', ',');
            
?>
	</br>
	<?php
        }
    }
?>
	<?php
    if ($markup == 'discount') {
        if ($myPrice > $gettotal_add) {
?>
	Cost: $<?php
            
            
            echo $myPr = number_format($myPrice, 2, '.', ',');
?>
	</br>
	 Discount: -$<?php
            
            $discount_get = $myPrice - $gettotal_add;
            echo $discount_get = number_format($discount_get, 2, '.', ',');
            
?>
	</br>
	<?php
        }
    }
?>
	You Pay: $<?php
    echo $gettotal_a = number_format($gettotal_add, 2, '.', ',');
?> 
</td></tr></table>
	</div>
<?php
}

?>
