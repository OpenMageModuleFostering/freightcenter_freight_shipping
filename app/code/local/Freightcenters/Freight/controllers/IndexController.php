<?php
class Freightcenters_Freight_IndexController extends Mage_Checkout_Controller_Action{
 public function newindexAction(){
  die('hello');
 //// $bla = Mage::getSingleton('fee/index');
 //$bla->setMsg("Thank You for your visit!");
  //$this->loadLayout();
  //$this->renderLayout();
 
 } 
 public function myfunAction() {
	$postData = $this->getRequest()->getPost();
        //echo "<pre>";print_r($postData);exit;
        
	$freight_price = $postData['val'];
	$cartproid = $postData['diq'];
        $carrier = $postData['carname'];
        $dest = $postData['dest'];
	$quoteid = $postData['qtid'];
	$days = $postData['days'];
	$grandship = $postData['grandship'];
	$rateid = $postData['rateid'];
        if($quoteid == NULL) {
            $session = Mage::getSingleton('checkout/session');
            $quoteid  = $session->getQuoteId();
        }
									
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write'); 
        $tableNamep = $resource->getTableName('brst_freight_shipping');
        $select = "Select * from $tableNamep where product_id='$cartproid' AND quote_id='$quoteid'";
        $qry = $writeConnection->query($select);
        $product_info = $qry->fetchAll();
        //echo "<pre>";print_r($product_info);exit;
        if($product_info == NULL) {
            $query = "INSERT INTO ".$tableNamep." (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid,days,grandship,mark) VALUES ('$quoteid','$cartproid','$freight_price','$carrier','$dest','$rateid','$days','$grandship','1')";
            $writeConnection->query($query);
        } else {
            $rowid = $product_info[0]['id'];
            $query = "UPDATE `$tableNamep` SET shipping_charge='$freight_price',carrier_name='$carrier', final_dest='$dest',rateid='$rateid',days='$days',grandship = '$grandship' where id=$rowid";
            $writeConnection->query($query);
        }
        
//	$quote = Mage::getSingleton('checkout/session')->getQuote();
//	foreach($quote->getAllVisibleItems() as $quote_item) {
//            $product = Mage::getModel('catalog/product')->load($quote_item->getProductId());
//            $productData = $product->getData();
//            $productsku = $product->getSku();
//            $productprice = $product->getPrice();
//            $productid = $product->getId();
//
//            if($productid == $cartproid) {
//                $new_price = $freight_price + $productprice;
//                $orig_price = $quote_item->getOriginalPrice();
//                //$new_price = $orig_price;
//                $quote_item->setOriginalCustomPrice($new_price);
//                $quote_item->setCustomPrice($new_price);        
//            }
//        }
//
//        Mage::register('basket_observer_executed', true);
//
//        $quote->save();
//        $quote->setTotalsCollectedFlag(false)->collectTotals();
 }
 public function mynofunAction() {
	$postData = $this->getRequest()->getPost();
        //echo "<pre>";print_r($postData);exit;
        
	$freight_price = $postData['val'];
	$cartproid = $postData['diq'];
        $carrier = $postData['carname'];
        $dest = $postData['dest'];
	$quoteid = $postData['qtid'];
	$rateid = $postData['rateid'];
        if($quoteid == NULL) {
            $session = Mage::getSingleton('checkout/session');
            $quoteid  = $session->getQuoteId();
        }
									
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write'); 
        $tableNamep = $resource->getTableName('brst_freight_shipping');
        $select = "Select * from $tableNamep where product_id='$cartproid' AND quote_id='$quoteid' AND mark = '1'";
        $qry = $writeConnection->query($select);
        $product_info = $qry->fetchAll();
        //echo "<pre>";print_r($product_info);exit;
      if($product_info == NULL) {
            $query = "INSERT INTO ".$tableNamep." (quote_id,product_id,shipping_charge,carrier_name,final_dest,rateid,mark) VALUES ('$quoteid','$cartproid','$freight_price','$carrier','$dest','$rateid','1')";
            $writeConnection->query($query);
       } 
		else {
           $rowid = $product_info[0]['id'];
            $query = "UPDATE `$tableNamep` SET shipping_charge='$freight_price',carrier_name='$carrier', final_dest='$dest',rateid='$rateid' where id=$rowid AND mark = '1'";
           $writeConnection->query($query);
        }
        
//	$quote = Mage::getSingleton('checkout/session')->getQuote();
//	foreach($quote->getAllVisibleItems() as $quote_item) {
//            $product = Mage::getModel('catalog/product')->load($quote_item->getProductId());
//            $productData = $product->getData();
//            $productsku = $product->getSku();
//            $productprice = $product->getPrice();
//            $productid = $product->getId();
//
//            if($productid == $cartproid) {
//                $new_price = $freight_price + $productprice;
//                $orig_price = $quote_item->getOriginalPrice();
//                //$new_price = $orig_price;
//                $quote_item->setOriginalCustomPrice($new_price);
//                $quote_item->setCustomPrice($new_price);        
//            }
//        }
//
//        Mage::register('basket_observer_executed', true);
//
//        $quote->save();
//        $quote->setTotalsCollectedFlag(false)->collectTotals();
 }
 public function saveAction(){
 
$postData = $this->getRequest()->getPost();
        //echo "<pre>";print_r($postData);exit;
        
	$freight_price = $postData['val'];
    $cartproid = $postData['diq'];
	$quoteid = $postData['qtid'];
	$order_id = $postData['order_id'];
	$order = Mage::getModel('sales/order')->load(31);
	$shippingId = $order->getShippingAddress()->getId();
	$address = Mage::getModel('sales/order_address')->load($shippingId);
 echo 	$quote = $order->getQuote();
		$address->setGrandTotal($freight_price);
  $address->setBaseGrandTotal($freight_price);
	//$postData = $this->getRequest()->getPost();
	
	//echo $_GET['var'] = $postData['val'];
	//echo $_GET['diq'] = $postData['diq'];
	//echo  $hello = $_GET['var'];
	//$make = explode(" ,",$hello);
	//print_r($make);
	//die('stop');
				
									/* 	$resource = Mage::getSingleton('core/resource');
										$writeConnection = $resource->getConnection('core_read'); 
										$tableNamep = $resource->getTableName('sales_flat_order');
										$queryp = 'SELECT * FROM ' . $tableNamep. ' WHERE entity_id = ' . $ware_id ;
										$results = $writeConnection->fetchAll($queryp);
										echo '<pre>'; print_r($results); echo '</pre>'; */
 
	}
 public function rowAction(){
 
 echo "this is another function";

 
}


}
