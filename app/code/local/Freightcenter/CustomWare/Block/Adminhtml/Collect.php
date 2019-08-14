<?php
class Freightcenter_CustomWare_Block_Adminhtml_Collect extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. foo_bar/adminhtml_baz
        $this->_blockGroup = 'freightcenter_customware';
        $this->_controller = 'adminhtml_collect';
        $this->_headerText = $this->__('All Warehouses');
         
        parent::__construct();
    }
}