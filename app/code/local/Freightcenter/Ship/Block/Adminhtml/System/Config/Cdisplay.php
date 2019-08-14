<?php
class Freightcenter_Ship_Block_Adminhtml_System_Config_Cdisplay extends Mage_Adminhtml_Block_System_Config_Form_Field{
    const CONFIG_PATH = 'carriers/freightcenter/carrier_display'; //put here the full path from the config to your element
    protected $_values = null;
    protected function _construct()
    {
        $this->setTemplate('freightcenter_ship/system/config/cdisplay.phtml');
        return parent::_construct();
    }
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setNamePrefix($element->getName())
            ->setHtmlId($element->getHtmlId());
        return $this->_toHtml();
    }
    public function getValues(){
        $values = array();
        //get the available values (use the source model from your question)
        for($i=1;$i<=100;$i++) {
            $values[$i] = $i;
        }
        return $values;
    }
    public function getIsChecked(){
        if (is_null($this->_values)){
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH])){
                $data = $data[self::CONFIG_PATH];
            }
            else{
                $data = '';
            }
            $this->_values = $data;
        }
        return $this->_values;
    }
}