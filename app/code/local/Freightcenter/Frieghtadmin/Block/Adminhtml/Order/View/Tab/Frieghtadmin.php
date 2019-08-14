<?php
class Freightcenter_Frieghtadmin_Block_Adminhtml_Order_View_Tab_Frieghtadmin
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('freightcenter/frieghtadmin/order/view/tab/frieghtadmin.phtml');
    }

    public function getTabLabel() {
        return $this->__('Frieght Admin');
    }

    public function getTabTitle() {
        return $this->__('Frieght Admin');
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