<?php
class Freightcenter_Frieghtadmin_Block_Adminhtml_Sales_Order_View_Tab_Frieghtadmin
    extends Mage_Adminhtml_Block_Sales_Order_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{    
    protected function _construct()
    {
	//die('here');
        parent::_construct();
        $this->setTemplate('freightcenter/frieghtadmin/sales/order/view/tab/frieghtadmin.phtml');
    }
	public function getOrderTotalData()
    {
        return array(
            'can_display_total_due'      => true,
            'can_display_total_paid'     => true,
            'can_display_total_refunded' => true,
        );
    }
	 public function getSource()
    {
        return $this->getOrder();
    }
	 public function getOrderInfoData()
    {
        return array(
            'no_use_order_link' => true,
        );
    }
	 public function getViewUrl($orderId)
    {
        return $this->getUrl('*/*/*', array('order_id'=>$orderId));
    }
	 public function getTrackingHtml()
    {
        return $this->getChildHtml('order_tracking');
    }
	 public function getItemsHtml()
    {
        return $this->getChildHtml('order_items');
    }
	public function getPaymentHtml()
    {
        return $this->getChildHtml('order_payment');
    }

    public function getTabLabel() {
        return $this->__('Freight Admin');
    }

    public function getTabTitle() {
        return $this->__('Freight Admin');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }
} 
?>