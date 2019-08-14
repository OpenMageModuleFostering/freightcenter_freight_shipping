<?php
class Freightcenter_CustomWare_Block_Adminhtml_Collect_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
         
        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('freightcenter_customware_collect_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }
     
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'freightcenter_customware/collect_collection';
    }
     
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'id'
            )
        );
         
        $this->addColumn('short_name',
            array(
                'header'=> $this->__('Short Name'),
                'index' => 'short_name'
            )
        );
        
        $this->addColumn('cmpny_name',
            array(
                'header'=> $this->__('Company Name'),
                'index' => 'cmpny_name'
            )
        );
		$this->addColumn('cmpny_postcode',
            array(
                'header'=> $this->__('Company PostCode'),
                'index' => 'cmpny_postcode'
            )
        );
        
        $this->addColumn('address',
            array(
                'header'=> $this->__('Address'),
                'index' => 'address'
            )
        );
        
        $this->addColumn('first_name',
            array(
                'header'=> $this->__('First Name'),
                'index' => 'first_name'
            )
        );
        
        $this->addColumn('last_name',
            array(
                'header'=> $this->__('Last Name'),
                'index' => 'last_name'
            )
        );
        
        $this->addColumn('phone',
            array(
                'header'=> $this->__('Phone'),
                'index' => 'phone'
            )
        );
        
        $this->addColumn('email',
            array(
                'header'=> $this->__('Email'),
                'index' => 'email'
            )
        );
        
        $this->addColumn('location_type',
            array(
                'header'=> $this->__('Location Type'),
                'index' => 'location_type'
            )
        );
         
        return parent::_prepareColumns();
    }
     
    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}