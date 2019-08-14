<?php 
define('MAGENTO_ROOT', getcwd());
    $mageFilename = MAGENTO_ROOT . '/app/Mage.php';
    require_once $mageFilename;
    Mage::init();
	 $coreSession = Mage::getSingleton('core/session', array('name' => 'frontend'));
	  $cart = Mage::getModel('checkout/cart')->getQuote();
	   $session = Mage::getSingleton('checkout/session'); 
    $items = $session->getQuote()->getAllItems();
	
	$myarray = array();
foreach($items as $item) {
    $id = $item->getProductId();
    $qtyordered = $item->getQty();
    $cart_products[$id] = $qtyordered;
    $product = Mage::getModel('catalog/product')->load($id);
    $ship_via_freight = $product->getData('ship_via_freight');
    if($ship_via_freight!=1) { 
	$non_freight[] = $product;
	?>
    <?php } else {
        $myarray[] = $product;
    }
}

if($_GET['yes'] == 'yes'){
$cartHelper = Mage::helper('checkout/cart');
$items = $cartHelper->getCart()->getItems();
foreach($myarray as $mainarray){

 $product_id = $mainarray['entity_id'];
foreach($items as $item){
 if ($item->getProduct()->getId() == $product_id) {
 $itemId = $item->getItemId();
  $cartHelper->getCart()->removeItem($itemId)->save();
  }
  }
}
echo $_GET['yes'];
}
else{
echo "no";
}

?>