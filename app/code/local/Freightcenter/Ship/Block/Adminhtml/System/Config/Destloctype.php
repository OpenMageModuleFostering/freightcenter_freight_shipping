<?php
class Freightcenter_Ship_Block_Adminhtml_System_Config_Destloctype extends Mage_Adminhtml_Block_System_Config_Form_Field{
    const CONFIG_PATH = 'carriers/freightcenter/dest_loctype'; //put here the full path from the config to your element
    protected $_values = null;
    protected function _construct()
    {
        $this->setTemplate('freightcenter_ship/system/config/destloctype.phtml');
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
        foreach (Mage::getSingleton('ship/loctypes')->toOptionArray() as $value){
            $values[$value['value']] = $value['label'];
        }
        return $values;
    }
    public function getIsChecked($name){
        return in_array($name, $this->getCheckedValues());
    }
    public function getCheckedValues(){
        if (is_null($this->_values)){
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH])){
                $data = $data[self::CONFIG_PATH];
            }
            else{
                $data = '';
            }
            $this->_values = explode(',', $data);
        }
        return $this->_values;
    }
}