<?php

class Fedobe_Landingpage_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('splash_page_grid');
        $this->setDefaultSort('page_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);
    }


    public function getMainButtonsHtml() {
        return parent::getMainButtonsHtml()
                . $this->getChildHtml('add_button');
    }

    /**
     * Initialise and set the collection for the grid
     *
     */
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('landingpage/page_collection');
        $this->addcustomfilter($collection,'url_key');
        $this->addcustomfilter($collection,'display_name');
        $this->addcustomfilter($collection,'is_enabled');
        $this->setCollection(
                $collection
        );
        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column) {
        if ($this->getCollection()) {
            $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
            if ($column->getFilterConditionCallback()) {
                call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
            } else {
                $cond = $column->getFilter()->getCondition();
                $pagecolumns = $this->getPageCloumns();
                if ($field && isset($cond)) {
                    if(!in_array($field, $pagecolumns))
                        $field = "`$field`.page_attribute_value";
                    $this->getCollection()->addFieldToFilter($field, $cond);
                }
            }
        }
        return $this;
    }

    /**
     * Add the columns to the grid
     *
     */
    protected function _prepareColumns() {
        $this->addColumn('page_id', array(
            'header' => $this->__('ID'),
            'align' => 'right',
            'width' => 1,
            'index' => 'page_id',
        ));

        $this->addColumn('display_name', array(
            'header' => $this->__('Name'),
            'align' => 'left',
            'index' => 'display_name'
        ));

        $this->addColumn('url_key', array(
            'header' => $this->__('URL Key'),
            'align' => 'left',
            'index' => 'url_key'
        ));

        $this->addColumn('is_enabled', array(
            'width' => 1,
            'header' => $this->__('Enabled'),
            'index' => 'is_enabled',
            'type' => 'options',
            'options' => array(
                1 => $this->__('Enabled'),
                0 => $this->__('Disabled'),
            ),
        ));
        $this->addColumn('is_featured', array(
            'width' => 1,
            'header' => $this->__('Brand Page'),
            'index' => 'is_featured',
            'type' => 'options',
            'options' => array(
                1 => $this->__('Yes'),
                0 => $this->__('No'),
            ),
        ));

        $this->addColumn('page_actions', array(
            'header'    => Mage::helper('landingpage')->__('Action'),
            'width'     => 10,
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'landingpage/adminhtml_page_grid_renderer_action',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('page_id');
        $this->getMassactionBlock()->setFormFieldName('page');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/landingpage_page/massDelete'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
    }

    /**
     * Retrieve the URL used to modify the grid via AJAX
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/pageGrid');
    }

    /**
     * Retrieve the URL for the row
     *
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/landingpage_page/edit', array('id' => $row->getId()));
    }

    /**
     * Retrieve an array of all of the stores
     *
     * @return array
     */
    protected function getStores() {
        $options = array(0 => $this->__('Global'));
        $stores = Mage::getResourceModel('core/store_collection')->load();

        foreach ($stores as $store) {
            $options[$store->getId()] = $store->getWebsite()->getName() . ' &gt; ' . $store->getName();
        }

        return $options;
    }

    protected function getPageCloumns() {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $tableName = $resource->getTableName('landingpage/page');
        $pagecolumns = $readConnection->describeTable($tableName);
        return array_keys($pagecolumns);
    }
    
    protected function addcustomfilter($collection,$field){
        $store_id = (Mage::app()->getRequest()->getParam('store')) ? Mage::app()->getRequest()->getParam('store') : Mage::app()->getStore()->getId();
        $collection->getSelect()->join(array(
            $field => Mage::getSingleton('core/resource')->getTableName('landingpage/page_store')), "`main_table`.page_id=`$field`.page_id AND `$field`.page_attribute_name = '$field' AND `$field`.store_id = $store_id ", array("$field.page_attribute_value AS $field")
        );
    }
}
