<?php

class Fedobe_Imggallery_Block_Adminhtml_Image_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct() {
        parent::__construct();
        $this->setId('image_grid');
        $this->setDefaultSort('imagedetails_id');//database Id name is imagedetails_id
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        
    }
    
    protected function _prepareCollection() {
        $collection = Mage::getModel('imggallery/imgdetails')->getCollection();
       // echo '<pre>';
       // print_r($collection->getData());
       $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns() {
	 $this->addColumn('imagedetails_id', [
			'header' => Mage::helper('imggallery')->__('ID'),
			'align' => 'right',
			'width' => '10px',
			'index' => 'imagedetails_id',
		]);
		$this->addColumn('image_title', array(
			'header' => Mage::helper('imggallery')->__('Image Title'),
			'align' => 'right',
			'width' => '100px',
			'index' => 'image_title',
		));
		$this->addColumn('gallery_img', array(
			'header' => Mage::helper('imggallery')->__('Image'),
			'align' => 'left',
			'width' => '200px',
			'index' => 'gallery_img',
			'renderer' => 'imggallery/adminhtml_imggallery_renderer_catimage',
		));
                  $this->addColumn('category_id', array(
            'header' => Mage::helper('imggallery')->__('Image Category'),
            'align' => 'right',
            'width' => '100px',
            'index' => 'category_id',
        ));

		$this->addColumn('image_description', array(
			'header' => Mage::helper('imggallery')->__('Image Description'),
			'align' => 'right',
			'width' => '200px',
			'index' => 'image_description',
		));
      
         $this->addColumn('position', array(
            'header' => Mage::helper('imggallery')->__('Position'),
            'align' => 'right',
            'width' => '100px',
            'index' => 'position',
        ));

            $this->addColumn('status',array(
            'header' => Mage::helper('imggallery')->__('Status'),
             'sortable'=>false,
            'align' => 'right',
            'width' => '70px',
            'index' => 'status',
                //'type'=> 'options',
   
                
        ));


        return parent::_prepareColumns();
    }
    protected function _prepareMassaction() {
        $this->setMassactionIdField('imagedetails_id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->addItem('delete', [
           'label' => Mage::helper('imggallery')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete', ['' => '']),
            'confirm' => Mage::helper('imggallery')->__('Are Your Sure ?')
        ]);
        return $this;
    }
    
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}

