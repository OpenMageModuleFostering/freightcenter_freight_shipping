<?php
class Freightcenter_CustomWare_Block_Adminhtml_Collect_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {  
        parent::__construct();
     
        $this->setId('freightcenter_customware_collect_form');
        $this->setTitle($this->__('Collect Information'));
    }  
     
    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {  
        $model = Mage::registry('freightcenter_customware');
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('checkout')->__('Collect Information'),
            'class'     => 'fieldset-wide',
        ));
     
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }  
     
        $fieldset->addField('short_name', 'text', array(
            'name'      => 'short_name',
            'label'     => Mage::helper('checkout')->__('Short Name'),
            'title'     => Mage::helper('checkout')->__('Short Name'),
            'required'  => true,
        ));
        
        $fieldset->addField('cmpny_name', 'text', array(
            'name'      => 'cmpny_name',
            'label'     => Mage::helper('checkout')->__('Company Name'),
            'title'     => Mage::helper('checkout')->__('Company Name'),
            'required'  => true,
        ));
		 $fieldset->addField('cmpny_postcode', 'text', array(
            'name'      => 'cmpny_postcode',
            'label'     => Mage::helper('checkout')->__('Company PostCode'),
            'title'     => Mage::helper('checkout')->__('Company PostCode'),
            'required'  => true,
        ));
        
        $fieldset->addField('address', 'text', array(
            'name'      => 'address',
            'label'     => Mage::helper('checkout')->__('Full Address'),
            'title'     => Mage::helper('checkout')->__('Full Address'),
            'required'  => true,
        ));
		$fieldset->addField('street', 'text', array(
            'name'      => 'street',
            'label'     => Mage::helper('checkout')->__('Full Street'),
            'title'     => Mage::helper('checkout')->__('Full Street'),
            'required'  => true,
        ));
		$fieldset->addField('city', 'text', array(
            'name'      => 'city',
            'label'     => Mage::helper('checkout')->__('Full City'),
            'title'     => Mage::helper('checkout')->__('Full City'),
            'required'  => true,
        ));
		$fieldset->addField('state', 'text', array(
            'name'      => 'state',
            'label'     => Mage::helper('checkout')->__('Full State'),
            'title'     => Mage::helper('checkout')->__('Full State'),
            'required'  => true,
        ));
		$fieldset->addField('country_id', 'text', array(
            'name'      => 'country_id',
            'label'     => Mage::helper('checkout')->__('Full Country'),
            'title'     => Mage::helper('checkout')->__('Full Country'),
            'required'  => true,
        ));
		
        
        $fieldset->addField('first_name', 'text', array(
            'name'      => 'first_name',
            'label'     => Mage::helper('checkout')->__('First Name'),
            'title'     => Mage::helper('checkout')->__('First Name'),
            'required'  => true,
        ));
        
        $fieldset->addField('last_name', 'text', array(
            'name'      => 'last_name',
            'label'     => Mage::helper('checkout')->__('Last Name'),
            'title'     => Mage::helper('checkout')->__('Last Name'),
            'required'  => true,
        ));
        
        $fieldset->addField('phone', 'text', array(
            'name'      => 'phone',
            'label'     => Mage::helper('checkout')->__('Phone'),
            'title'     => Mage::helper('checkout')->__('Phone'),
            'required'  => true,
        ));
        
        $fieldset->addField('email', 'text', array(
            'name'      => 'email',
            'label'     => Mage::helper('checkout')->__('Email'),
            'title'     => Mage::helper('checkout')->__('Email'),
            'required'  => true,
        ));
        
//        $fieldset->addField('accessorials', 'checkboxes', array(
//            'label'     => $this->__('Accessorials'),
//            'name'      => 'accessorials[]',
//            'values'    => array(
//                              array('value'=>'1','label'=>'Include A Lift Gate At Pickup'),
//                              array('value'=>'2','label'=>'Is Limited Access Pickup Area'),
//                              array('value'=>'3','label'=>'Is Inside Pickup'),
//                              array('value'=>'4','label'=>'Call For Pickup Appointment'),
//                         ),
//            'required'  => true,
//        ));
        
       /* $fieldset->addField('accessorials', 'multiselect', array(
            'label'     => $this->__('Accessorials'),
            'name'      => 'accessorials[]',
            'values'    => array(
                              array('value'=>'1','label'=>'Include A Lift Gate At Pickup'),
                              array('value'=>'2','label'=>'Is Limited Access Pickup Area'),
                              array('value'=>'3','label'=>'Is Inside Pickup'),
                              array('value'=>'4','label'=>'Call For Pickup Appointment'),
                         ),
            'required'  => true,
        ));  */
		
		
		
		
/* 		$fieldset->addField('accessorials', 'checkboxes', array(
          'label'     => $this->__('Accessorials'),
          'name'      => 'accessorials[]',
          'values' => array(
                              array('value'=>'1','label'=>'Include A Lift Gate At Pickup'),
                              array('value'=>'2','label'=>'Is Limited Access Pickup Area'),
                              array('value'=>'3','label'=>'Is Inside Pickup'),
                              array('value'=>'4','label'=>'Call For Pickup Appointment'),
                       ),
          
          'value'  => array('1','2','3','4'),
		  'Enabled' => true,
          'required'  => true,
         
        ));
 */
		
		
		

        
        $fieldset->addField('location_type', 'select', array(
            'label'     => $this->__('Origin Location Type'),
            'class'     => 'required-entry',
            'required'  => true,
			'onclick' => "AccessSelect(this.value)",
            'name'      => 'location_type',
            'values'    => array(''=>'Please Select','Residential' => 'Residential / Curb-side','Business With Dock or Forklift' => 'Business With Dock or Forklift'
                            ,'Business Without Dock or Forklift' => 'Business Without Dock or Forklift','Construction Site' => 'Construction Site',
                            'Convention Center or Tradeshow' => 'Convention Center or Tradeshow','Terminal'=>'Terminal'),
        ));
		 
		 
		 $fieldset->addField('accessorials', 'checkboxes', array(
            'label'     => $this->__('Accessorials'),
            'name'      => 'accessorials[]',
            'values'    => array(
                              array('value'=>'1','label'=>'Include A Lift Gate At Pickup'),
                              array('value'=>'2','label'=>'Is Limited Access Pickup Area'),
                              array('value'=>'3','label'=>'Is Inside Pickup'),
                              array('value'=>'4','label'=>'Call For Pickup Appointment'),
                         ),
            'required'  => true,
        ));
		
		 $fieldset->addField('hours', 'text', array(
            'name'      => 'hours',
            'label'     => Mage::helper('checkout')->__('Hours of Operation'),
            'title'     => Mage::helper('checkout')->__('Hours of Operation'),
             'style'   => "275px !important",
            'required'  => true,
        ));
        
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        
        return parent::_prepareForm();
    }  
}
$params = Mage::app()->getRequest()->getParam('id');
if($params != NULL) {
    $warehouses = Mage::getModel('freightcenter_customware/collect')->load($params);
    $getaccessorials = $warehouses['accessorials'];
    $location_type = $warehouses['location_type'];
    //echo "<pre>";print_r($location_type);exit;
    $baseurl = Mage::getBaseUrl();
    if(strstr($baseurl,'index.php')) {
        $baseurl = str_replace('/index.php','',$baseurl);
    }
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type=text/javascript>
jQuery(document).ready(function() {
AccessSelect_one('<?php echo $location_type; ?>');
    var access = '<?php echo $getaccessorials; ?>';
    var myacess = access.split(",");
    var i;
    for(i = 0; i < myacess.length; i++) {
        jQuery('#accessorials_'+myacess[i]).attr('checked','checked');
    }
	
});
function AccessSelect(id){
if(id == ''){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);

}
if(id == 'Residential'){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Business With Dock or Forklift'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Business Without Dock or Forklift'){

jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false); 
}
if(id == 'Construction Site'){

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ; 
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Convention Center or Tradeshow'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Terminal'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
}
function AccessSelect_one(id){
if(id == ''){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;

}
if(id == 'Residential'){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;

}
if(id == 'Business With Dock or Forklift'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'block') ;

}
if(id == 'Business Without Dock or Forklift'){

jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;

}
if(id == 'Construction Site'){

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ; 

}
if(id == 'Convention Center or Tradeshow'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;

}
if(id == 'Terminal'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;

}
}

</script>
<?php } else { ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type=text/javascript>
jQuery(document).ready(function() {
var id = '';
AccessSelect_one(id);
	
});
function AccessSelect(id){
if(id == ''){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);

}
if(id == 'Residential'){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Business With Dock or Forklift'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Business Without Dock or Forklift'){

jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false); 
}
if(id == 'Construction Site'){

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ; 
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Convention Center or Tradeshow'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
if(id == 'Terminal'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes input[type=checkbox]").attr('checked', false);
}
}
function AccessSelect_one(id){
if(id == ''){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;


}
if(id == 'Residential'){

jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;

}
if(id == 'Business With Dock or Forklift'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'block') ;

}
if(id == 'Business Without Dock or Forklift'){

jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'block') ;

}
if(id == 'Construction Site'){

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ; 

}
if(id == 'Convention Center or Tradeshow'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'block') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;

}
if(id == 'Terminal'){ 

jQuery(".checkboxes li:nth-of-type(1)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(2)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(3)").css('display', 'none') ;
jQuery(".checkboxes li:nth-of-type(4)").css('display', 'none') ;

}
}
</script>

<?php } ?>
