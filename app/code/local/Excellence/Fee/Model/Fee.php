<?php
class Excellence_Fee_Model_Fee extends Varien_Object{
	//const FEE_AMOUNT = 10;

	public static function getFee(){
            $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
            $session = Mage::getSingleton('checkout/session');
            $quoteid = $session->getQuoteId();
            
            $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
            $gettotal = '';
            foreach($items as $item) {
                $getqty = $item->getQty();
               $getid = $item->getProductId();

                $select = "Select * from `brst_freight_shipping` where quote_id='$quoteid' AND product_id='$getid' AND mark = '1'";
                $getselect = $connection->query($select);
                $pro_data = $getselect->fetchAll();
                if($pro_data != NULL) {
                    $getprice = $pro_data[0]['shipping_charge'];
                   $gettotal += ($getprice);
				  
				
	  
				  
                }
            }
			//echo $gettotal; die('here');
			/*  $MarkupType = Mage::getStoreConfig('carriers/freightcenter/markup_type');
				   $MarkupPrice = Mage::getStoreConfig('carriers/freightcenter/markup_price');
				   $Markuptypeadd = Mage::getStoreConfig('carriers/freightcenter/markup');
				   $Discount = Mage::getStoreConfig('carriers/freightcenter/discount');
				   $DiscountAmount = Mage::getStoreConfig('carriers/freightcenter/discount_amount');
				   $DiscountPrice = Mage::getStoreConfig('carriers/freightcenter/discount_price');
				   
				  if($Markuptypeadd == 'markup'){
										 if($MarkupType == 'percent'){
										   $gettotal_pr = ($gettotal *  $MarkupPrice)/100;
										   $gettotal = $gettotal + $gettotal_pr;
											}
											else{
											$gettotal = $gettotal + $MarkupPrice ; 
											}
											}
								if($Markuptypeadd == 'none'){
										 if($MarkupType == 'percent'){
										  // $gettotal_pr = ($shipping_charge *  $MarkupPrice)/100;
										   $gettotal = $gettotal;
											}
											else{
											$gettotal = $gettotal; 
											}
											}
								if($Markuptypeadd == 'discount'){
										 if($MarkupType == 'percent'){
										   $gettotal_pr = ($gettotal *  $MarkupPrice)/100;
										   $gettotal = $gettotal - $gettotal_pr;
											}
											else{
											$gettotal = $gettotal - $MarkupPrice ; 
											}
											}
											
											if($Discount == '1'){
												if($gettotal > $DiscountAmount) {
												$gettotal = $gettotal - $DiscountPrice;
												
												}
											} */
			
            return $gettotal;
	}
	
	
	public static function canApply($address){
	
	  $order = Mage::getModel("sales/order")->load(1);
	  $count = count($order);
	  if($count < 0){
		//put here your business logic to check if fee should be applied or not
		if($address->getAddressType() == 'billing'){
		return true;
		} 
		}
		else{
		//if($address->getAddressType() == 'billing'){
		return true;
		//} 
		
		}
	}
}