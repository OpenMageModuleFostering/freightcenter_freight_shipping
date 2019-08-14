<?php
class Freightcenter_Ship_Model_CarrierDisplay
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        for($i=1;$i<=100;$i++) {
            $array[] = array('value' => $i, 'label'=>Mage::helper('adminhtml')->__($i));
        }
        return $array;
    }

}