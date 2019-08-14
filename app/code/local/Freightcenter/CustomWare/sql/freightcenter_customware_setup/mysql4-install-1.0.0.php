<?php

/* delete tables from database if exists */
$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
$warehouse_table = "Drop table if exists `brst_ship_warehouses`";
$connection->query($warehouse_table);

$carriers_table = "Drop table if exists `brst_freight_carriers`";
$connection->query($carriers_table);

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'brst_ship_warehouses'
 */
$table = $installer->getConnection()
    // The following call to getTable('foo_bar/baz') will lookup the resource for foo_bar (foo_bar_mysql4), and look
    // for a corresponding entity called baz. The table name in the XML is foo_bar_baz, so ths is what is created.
    ->newTable($installer->getTable('brst_ship_warehouses'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'ID')
    ->addColumn('short_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Short Name')
    ->addColumn('cmpny_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Company Name')
		->addColumn('cmpny_postcode', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Company PostCode')
    ->addColumn('address', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'address')
		  ->addColumn('street', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'street')
		  ->addColumn('city', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'city')
		  ->addColumn('state', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'state')
		  ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'country_id')
    ->addColumn('first_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'first_name')
    ->addColumn('last_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'last_name')
    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'phone')
     ->addColumn('email', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'email')
     ->addColumn('accessorials', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'accessorials')
     ->addColumn('location_type', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'location_type')
		 ->addColumn('hours', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'hours');

$installer->getConnection()->createTable($table);


/**
 * Create table 'brst_freight_carriers'
 */
$table1 = $installer->getConnection()
    // The following call to getTable('foo_bar/baz') will lookup the resource for foo_bar (foo_bar_mysql4), and look
    // for a corresponding entity called baz. The table name in the XML is foo_bar_baz, so ths is what is created.
    ->newTable($installer->getTable('brst_freight_carriers'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'ID')
    ->addColumn('username', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'username')
    ->addColumn('carrierid', Varien_Db_Ddl_Table::TYPE_INTEGER, 0, array(
        'nullable'  => false,
        ), 'carrierid')
    ->addColumn('carrier_scac', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'carrier_scac')
    ->addColumn('carrier_code', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'carrier_code')
    ->addColumn('carrier_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'carrier_name');

$installer->getConnection()->createTable($table1);

$installer->endSetup();
$table3 = $installer->getConnection()
    // The following call to getTable('foo_bar/baz') will lookup the resource for foo_bar (foo_bar_mysql4), and look
    // for a corresponding entity called baz. The table name in the XML is foo_bar_baz, so ths is what is created.
    ->newTable($installer->getTable('brst_freight_shipping'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Product Id')
    ->addColumn('shipping_charge', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Shipping Charge')
    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Quote Id')
    ->addColumn('carrier_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Carrier Name')
		    ->addColumn('bookingno', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Booking No')
		    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Status')
		    ->addColumn('date_from', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Date From')
		    ->addColumn('date_to', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Date TO')
		 ->addColumn('final_dest', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'Final Destination')
		 ->addColumn('rateid', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'RATEID')
		 ->addColumn('warehouse_name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'warehouse_name')
		->addColumn('days', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'days')
		->addColumn('grandship', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'grandship')
		->addColumn('mark', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
        ), 'mark');

$installer->getConnection()->createTable($table3);

$installer->endSetup();


/* create new attribute group */
$groups = Mage::getModel('eav/entity_attribute_group')->getCollection()->getData();
foreach($groups as $grp) {
    $all_groups[] = $grp['attribute_group_name'];
}

if(!in_array('Freight Shipping',$all_groups)) {
    $modelGroup = Mage::getModel('eav/entity_attribute_group');
    $modelGroup->setAttributeGroupName('Freight Shipping')
        ->setAttributeSetId('4')
        ->setSortOrder(100);
    $modelGroup->save();
}


/* create product attributes */
function createAttribute($code, $label, $attribute_type, $product_type, $attribute_set_name, $group_name )
{
    $_attribute_data = array(
        'attribute_code' => $code,
        'is_global' => '1',
        'frontend_input' => $attribute_type, //'boolean',
        'default_value_text' => '',
        'default_value_yesno' => '0',
        'default_value_date' => '',
        'default_value_textarea' => '',
        'is_unique' => '0',
        'is_required' => '0',
        'apply_to' => array($product_type), //array('grouped')
        'is_configurable' => '0',
        'is_searchable' => '0',
        'is_visible_in_advanced_search' => '0',
        'is_comparable' => '0',
        'is_used_for_price_rules' => '0',
        'is_wysiwyg_enabled' => '0',
        'is_html_allowed_on_front' => '1',
        'is_visible_on_front' => '0',
        'used_in_product_listing' => '0',
        'used_for_sort_by' => '0',
        'frontend_label' => array($label)
    );
 
    $model = Mage::getModel('catalog/resource_eav_attribute');
 
    if (!isset($_attribute_data['is_configurable'])) {
        $_attribute_data['is_configurable'] = 0;
    }
    if (!isset($_attribute_data['is_filterable'])) {
        $_attribute_data['is_filterable'] = 0;
    }
    if (!isset($_attribute_data['is_filterable_in_search'])) {
        $_attribute_data['is_filterable_in_search'] = 0;
    }
 
    if (is_null($model->getIsUserDefined()) || $model->getIsUserDefined() != 0) {
        $_attribute_data['backend_type'] = $model->getBackendTypeByInput($_attribute_data['frontend_input']);
    }
 
    $defaultValueField = $model->getDefaultValueByInput($_attribute_data['frontend_input']);
    if ($defaultValueField) {
      //  $_attribute_data['default_value'] = $this->getRequest()->getParam($defaultValueField);
    }
 
    $model->addData($_attribute_data);
 
    $model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
    $model->setIsUserDefined(1);
    try {
        $model->save();
        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
            //-------------- add attribute to set and group

            $attribute_code = $code;

            $attribute_set_id=$setup->getAttributeSetId('catalog_product', $attribute_set_name);
            $attribute_group_id=$setup->getAttributeGroupId('catalog_product', $attribute_set_id, $group_name);
            $attribute_id=$setup->getAttributeId('catalog_product', $attribute_code);

            $setup->addAttributeToSet($entityTypeId='catalog_product',$attribute_set_id, $attribute_group_id, $attribute_id);
        
    } catch (Exception $e) { echo '<p>Sorry, error occured while trying to save the attribute. Error: '.$e->getMessage().'</p>'; }
}


$attribute_set_name="Default";  //attribute set name
$group_name="Freight Shipping";  //Inside attribue set you will get groups  ex:- General, prices etc

/* ship via freight */
$check = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','ship_via_freight');
if($check->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'Ship via freight')),'Ship this item via freight',"boolean","simple",$attribute_set_name,$group_name);
}

/* freight class */
$check2 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','freight_class');
if($check2->getId() == NULL) {
    $attr_code = 'Freight Class';
    createAttribute(strtolower(str_replace(" ", "_", $attr_code)), "$attr_code", "select", "simple", $attribute_set_name,$group_name);
}

/* NMFC */
$check3 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','NMFC');
if($check3->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'NMFC')),'NMFC',"text","simple",$attribute_set_name,$group_name);
}

/* Length */
$check4 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','freight_length');
if($check4->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'freight_length')),'Length (inches)',"text","simple",$attribute_set_name,$group_name);
}

/* Width */
$check5 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','freight_width');
if($check5->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'freight_width')),'Width (inches)',"text","simple",$attribute_set_name,$group_name);
}

/* Height */
$check6 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','freight_height');
if($check6->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'freight_height')),'Height (inches)',"text","simple",$attribute_set_name,$group_name);
}

/* weight */
//$check7 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','freight_weight');
//if($check7->getId() == NULL) {
//    createAttribute(strtolower(str_replace(" ", "_", 'freight_weight')),'Weight',"text","simple",$attribute_set_name,$group_name);
//}

/* ship via freight */
/* $check8 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','hazardous_materials');
if($check8->getId() == NULL) {
    createAttribute(strtolower(str_replace(" ", "_", 'Hazardous Materials')),'Hazardous Materials',"boolean","simple",$attribute_set_name,$group_name);
} */

/* packaging types */
/* $check9 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','packaging_type');
if($check9->getId() == NULL) {
    $attr_code = 'Packaging Type';
    createAttribute(strtolower(str_replace(" ", "_", $attr_code)), "$attr_code", "select", "simple", $attribute_set_name,$group_name);
} */

/* origin warehouse */
$check7 = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product','origin_warehouse');
if($check7->getId() == NULL) {
    $attr_code = 'Origin Warehouse';
    createAttribute(strtolower(str_replace(" ", "_", $attr_code)), "$attr_code", "select", "simple", $attribute_set_name,$group_name);
}