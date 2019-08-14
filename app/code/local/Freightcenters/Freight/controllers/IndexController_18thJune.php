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
	$quoteid = $postData['qtid'];
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
            $query = "INSERT INTO ".$tableNamep." (quote_id,product_id,shipping_charge,carrier_name) VALUES ('$quoteid','$cartproid','$freight_price','$carrier')";
            $writeConnection->query($query);
        } else {
            $rowid = $product_info[0]['id'];
            $query = "UPDATE `$tableNamep` SET shipping_charge='$freight_price',carrier_name='$carrier' where id=$rowid";
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
 
 $ware_id = $_GET['id'];
	//$postData = $this->getRequest()->getPost();
	
	//echo $_GET['var'] = $postData['val'];
	//echo $_GET['diq'] = $postData['diq'];
	//echo  $hello = $_GET['var'];
	//$make = explode(" ,",$hello);
	//print_r($make);
	//die('stop');
				
										$resource = Mage::getSingleton('core/resource');
										$writeConnection = $resource->getConnection('core_read'); 
										$tableNamep = $resource->getTableName('sales_flat_order');
										$queryp = 'SELECT * FROM ' . $tableNamep. ' WHERE entity_id = ' . $ware_id ;
										$results = $writeConnection->fetchAll($queryp);
										echo '<pre>'; print_r($results); echo '</pre>';
 
	}
 public function rowAction(){
 
 echo "this is another function";

 
}


}
