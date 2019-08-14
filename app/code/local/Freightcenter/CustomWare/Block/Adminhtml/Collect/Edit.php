<?php
class Freightcenter_CustomWare_Block_Adminhtml_Collect_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init class
     */
    public function __construct()
    {  
        $this->_blockGroup = 'freightcenter_customware';
        $this->_controller = 'adminhtml_collect';
     
        parent::__construct();
     
        $this->_updateButton('save', 'label', $this->__('Save Warehouse'));
        $this->_updateButton('delete', 'label', $this->__('Delete Warehouse'));
    }  
     
    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {  
        if (Mage::registry('freightcenter_customware')->getId()) {
            return $this->__('Edit Warehouse');
        }  
        else {
            return $this->__('New Warehouse');
        }  
    }  
}