<?php
class Fedobe_Imggallery_Block_Adminhtml_Image_Edit_Tabs_Form extends Mage_Adminhtml_Block_Widget_Form{
	Protected function _prepareForm(){
		if (Mage::registry('image_data')){
			$data=Mage::registry('image_data')->getData();

		}else{
			$data=array();
		}
		$form=new Varien_Data_Form();
		$this->setForm($form);
		$fieldset=$form->addFieldset('imggallery_image', array('legand'=>Mage::helper('imggallery')->__('Caption Information')));
     	/*$wysiwygConfig=Mage::getSingleton('cms/wysiwy_config')->getConfig();
     	$wysiwygConfig->addData(array('add_variables'=>false,
              'add_widgets'=>false,
              'add_Images'=>false,
              'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
			'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
			'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
			'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
			'files_browser_window_width' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width'),
			'files_browser_window_height' => (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height')
		));*/
     $fieldset->addField('image_title', 'text', array(
     	'label'=>Mage::helper('imggallery')->__('Image Title'),
     	'class'=>'required_entry',
     	'required'=>true,
     	'name'=>'image_title',
     	));
     $fieldset->addField('gallery_img', 'image', array(
     	'label'=>Mage::helper('imggallery')->__('Gallery Image'),
     	'required'=>false,
     	'name'=>'gallery_img',
     	'note'=>'(*.jpg, *.jpeg, *.png, *.gif)',

       	));
  $fieldset->addField('category_id', 'select', array(
     	'label'=>Mage::helper('imggallery')->__('Image Category'),
     	'required'=>false,
     	'name'=>'category_id',
     	//'note'=>'(*.jpg, *.jpeg, *.png, *.gif)',
     	'note'      => $note,
      'values'    => array(
          array(
              'value'     => not_selected,
              'label'     => Mage::helper('imggallery')->__('Plese select'),
          ),

          array(
              'value'     => Dfault,
              'label'     => Mage::helper('imggallery')->__('Default'),
          ),
     
       array(
              'value'     => watch,
              'label'     => Mage::helper('imggallery')->__('watches'),
          ),
      ),
        

       	));

     $fieldset->addField('image_description', 'editor', array(
     	'name'=>'image_description',
     	'label'=>Mage::helper('imggallery')->__('Image Description'),
     	'title'=>Mage::helper('imggallery')->__('Image Description'),
     	'style'=>'width:400px; height:250px;',
     	'config'=>$wysiwygConfig,
     	'required'=>false,
     	'wysiwyg'=>true
     	));
     $fieldset->addField('position', 'text', array(
     	'label'=>Mage::helper('imggallery')->__('position'),
     	'class'=>'required_entry',
     	'required'=>false,
     	'name'=>'position',
     	));
     
       $fieldset->addField('status', 'select', array(
     	'label'=>Mage::helper('imggallery')->__('Status'),
     	'required'=>false,
     	'name'=>'status',
     	//'note'=>'(*.jpg, *.jpeg, *.png, *.gif)',
     	'note'      => $note,
      'values'    => array(
          array(
              'value'     => 'active',
              'label'     => Mage::helper('imggallery')->__('Enable'),
          ),

          array(
              'value'     => 'Deactive',
              'label'     => Mage::helper('imggallery')->__('Desable'),
          ),
     
      
      ),
        

       	));
     
     $form->setValues($data);
     return parent:: _prepareForm();
	
	}
}



?>