<?php
class Fedobe_Assembleproduct_Adminhtml_AjaxactionController extends Mage_Core_Controller_Front_Action {        
    
    public function indexAction() {
    	
    	$productId = $this->getRequest()->getParam('productId');
    	$option_id = $this->getRequest()->getParam('option_id');
    	$option_label = $this->getRequest()->getParam('option_label');
    	$_product = Mage::getModel('catalog/product')->load($productId);
		
		$option_ids = array();
		$option_lables = array();
		$bundled_items = array();

		$optionCollection = $_product->getTypeInstance()->getOptionsCollection();
		
		$selectionCollection = $_product->getTypeInstance(true)->getSelectionsCollection(
		$_product->getTypeInstance(true)->getOptionsIds($_product), $_product
	);

		foreach ($optionCollection as $option) {
			$option_ids[] = $option->option_id;
			$option_labels[] =$option->default_title;
			$option_required[] =$option->required; 
		} ?>

    <table cellspacing="0" class="form-list">
			<colgroup class="label"></colgroup>
			<colgroup class="value"></colgroup>
			<colgroup class="scope-label"></colgroup>
			<colgroup class=""></colgroup>
			<tbody>

		<?php
		$x=1;
	for($i=0; $i<count($option_ids); $i++){

		if($option_ids[$i] == $option_id){ 

		?>
	<tr >
	<td class="label">
	<label for="<?php echo $option_labels[$i];?>">
	<?php echo $option_labels[$i];?>
	<span class="required">*</span>
	</label>
	</td>
	<td class="value">
	<select name="<?php echo $option_ids[$i];?>" id="<?php echo $option_labels[$i];?>" class="select validate-select" title="" >
            <option value=""></option>
            
        <?php
		foreach($selectionCollection as $option){
			if($option_ids[$i] == $option->option_id){
				?>
				<option value="<?php echo $option->product_id; ?>"><?php echo $this->__($option->name); ?></option>
			<?php }	
		} ?>
		</select>
		</td></tr>
		
		<?php
	}
		$x++;
	}?>
	
<?php
      
		$a=1;
	for($i=0; $i<count($option_ids); $i++){
		if($option_ids[$i] != $option_id && $option_labels[$i] != $option_label ){ 

		?>
	<tr >
	<td class="label">
	<label for="<?php echo $option_labels[$i];?>">
	<?php echo $option_labels[$i];?>
	<?php if($option_required[$i] == 1){ ?>
	<span class="required">*</span>
	<?php } ?>
	</label>
	</td>
	<td class="value">
	<select name="<?php echo $option_ids[$i];?>[]" id="<?php echo $option_labels[$i];?>" class="select multiselect <?php if($option_required[$i] == 1){ echo 'validate-select';  } ?>" title="" multiple >
            <option value=""></option>
            
            
    


		<?php
		foreach($selectionCollection as $option){
			if($option_ids[$i] == $option->option_id){
				?>
				<option value="<?php echo $option->product_id; ?>" <?php if($option->status != 1){ echo "disabled"; } ?> ><?php echo $this->__($option->name); ?></option>
			<?php }	
		} ?>
		</select>
		</td></tr>
		
		<?php
	}
		$a++;
	}?>
	</tbody></table>
 	<?php
 	}

 	public function deleteAction(){
    	$proId = $this->getRequest()->getParam('productId');
 		
 		if($proId){
        
            
            $primaryOptionRows = $this->getPrimaryoptionModel()->getCollection()
                                ->addFieldToFilter('product_id',array('eq'=>$proId ));
          foreach ($primaryOptionRows as $row) {
              $priId = $row->getPrimaryoption_id();
              $primaryOptionId = $row->getPrimaryoption();
             }
            if($priId){
              $primaryOptionModel = $this->getPrimaryoptionModel()->load($priId);
                  if($primaryOptionId){
                     $dependencyOptionRows = $this->getDependencyoptionModel()->getCollection()
                                            ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryOptionId));
                     foreach ($dependencyOptionRows as $row) {
                          $depId[] = $row->getDependencyoption_id();
                       }
                    if($depId){
                        
                      $dependencyOptionModel = $this->getDependencyoptionModel()->getCollection()
                                               ->addFieldToFilter('dependencyoption_id', array('in' => $depId));;
                      }
                      
                  }
                try {
                      
                     $primaryOptionModel->delete();
                     foreach ($dependencyOptionModel as $val) {
                    $objModel = $this->getDependencyoptionModel()->load($val->getId());
                    $objModel->delete();
                    }
                     
                
                   echo '<ul class=\'messages\' ><li class=\'success-msg\'><ul><li><span>The Dependencyoption was deleted.</span></li></ul></li></ul>';
                } catch (Exception $e) {
                    
                   echo '<ul class=\'messages\' ><li class=\'error-msg\'><ul><li><span>The Dependencyoption was not deleted.</span></li></ul></li></ul>';
                    
                }
            }

        } 
        
  }
  public function singledeleteAction(){
    	$mainProId = $this->getRequest()->getParam('mainProId');
    	$currentproId = $this->getRequest()->getParam('currentproId');
    	$primaryOption = $this->getPrimaryOption($mainProId);
    	if($currentproId){

    		$dependencyOptionRows = $this->getDependencyoptionModel()->getCollection()
    									 ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryOption))
                                         ->addFieldToFilter('primaryoption_product_id',array('eq'=>$currentproId));
                     foreach ($dependencyOptionRows as $row) {
                          $depId[] = $row->getDependencyoption_id();
                       }
                    if($depId){
                        
                      $dependencyOptionModel = $this->getDependencyoptionModel()->getCollection()
                                               ->addFieldToFilter('dependencyoption_id', array('in' => $depId));;
                    }
                     try {
                      
                     
                     foreach ($dependencyOptionModel as $val) {
                    $objModel = $this->getDependencyoptionModel()->load($val->getId());
                    $objModel->delete();
                    }
                     
                
                   echo '<ul class=\'messages\' ><li class=\'success-msg\'><ul><li><span>The Dependencyoption was deleted.</span></li></ul></li></ul>';
                } catch (Exception $e) {
                    
                   echo '<ul class=\'messages\' ><li class=\'error-msg\'><ul><li><span>The Dependencyoption was not deleted.</span></li></ul></li></ul>';
                    
                }
                      
        }
  }

  

public function getPrimaryOption($id){

    	$primaryOptionCollection = $this->getPrimaryoptionModel()
                          ->getCollection()
                          ->addFieldToFilter('product_id',array('eq'=>$id));
         
         foreach ($primaryOptionCollection as $val) {
    		$primaryoption = $val->getPrimaryoption();
			}
         return $primaryoption;
    }



public function getPrimaryoptionModel(){

        return Mage::getModel('fedobe_assembleproduct/primaryoption');
   }


  public function getDependencyoptionModel(){

        return Mage::getModel('fedobe_assembleproduct/dependencyoption');
   }




    public function singleeditAction() {
    	
    	$productId = $this->getRequest()->getParam('productId');
    	$option_id = $this->getRequest()->getParam('option_id');
    	$option_label = $this->getRequest()->getParam('option_label');
    	$mainProId = $this->getRequest()->getParam('mainProId');
    	$currentproId = $this->getRequest()->getParam('currentproId');
    	$primaryOption = $this->getPrimaryOption($mainProId);
    	if($currentproId){

    		$dependencyOptionRows = $this->getDependencyoptionModel()->getCollection()
    									 ->addFieldToFilter('primaryoption_id',array('eq'=>$primaryOption))
                                         ->addFieldToFilter('primaryoption_product_id',array('eq'=>$currentproId));
                     foreach ($dependencyOptionRows as $row) {
                     	  $dependency_option_array[] = $row->getDependentoption_id(); 
                          $depArray[$row->getDependentoption_id()] =  $row->getDependentoption_product_id();
                       }
                       
                       
    	$_product = Mage::getModel('catalog/product')->load($productId);
		
		$option_ids = array();
		$option_lables = array();
		$bundled_items = array();

		$optionCollection = $_product->getTypeInstance()->getOptionsCollection();
		
		$selectionCollection = $_product->getTypeInstance(true)->getSelectionsCollection(
		$_product->getTypeInstance(true)->getOptionsIds($_product), $_product
	); ?>
	
	<?php 

		foreach ($optionCollection as $option) {
			$option_ids[] = $option->option_id;
			$option_labels[] =$option->default_title; 
		} ?>
		<table cellspacing="0" class="form-list">
			<colgroup class="label"></colgroup>
			<colgroup class="value"></colgroup>
			<colgroup class="scope-label"></colgroup>
			<colgroup class=""></colgroup>
			<tbody>

		<?php
		$x=1;
	for($i=0; $i<count($option_ids); $i++){

		if($option_ids[$i] == $option_id){ 

		?>
	<tr >
	<td class="label">
	<label for="<?php echo $option_labels[$i];?>">
	<?php echo $option_labels[$i];?>
	<span class="required">*</span>
	</label>
	</td>
	<td class="value">
	<select name="<?php echo $option_ids[$i];?>" id="<?php echo $option_labels[$i];?>" class="select validate-select" title="" >
            <option value=""></option>
            
        <?php
		foreach($selectionCollection as $option){
			if($option_ids[$i] == $option->option_id){
				?>
				<option value="<?php echo $option->product_id; ?>" <?php if($currentproId == $option->product_id){echo "selected='selected'" ;}?> ><?php echo $this->__($option->name); ?></option>
			<?php }	
		} ?>
		</select>
		</td></tr>
		
		<?php
	}
		$x++;
	}?>
	
<?php
      
		$a=1;
		
	for($i=0; $i<count($option_ids); $i++){
		if($option_ids[$i] != $option_id && $option_labels[$i] != $option_label ){ 
			if (in_array($option_ids[$i], $dependency_option_array)){
				$dep_pro_id = explode(",", $depArray[$option_ids[$i]]);
			}
			
		?>
	<tr >
	<td class="label">
	<label for="<?php echo $option_labels[$i];?>">
	<?php echo $option_labels[$i];?>
	
	</label>
	</td>
	<td class="value">
	<select name="<?php echo $option_ids[$i];?>[]" id="<?php echo $option_labels[$i];?>" class="select multiselect" title="" multiple >
            <option value=""></option>
            
            
    


		<?php
		
		foreach($selectionCollection as $option){
			if($option_ids[$i] == $option->option_id){
				?>
				<option value="<?php echo $option->product_id; ?>" 
				<?php 
					foreach ($dep_pro_id  as $key => $value) {
				if($value == $option->product_id){echo "selected='selected'" ;}
				 }?> > <?php echo $this->__($option->name); ?></option>
			<?php }	
		} ?>
		</select>

		</td></tr>
		
		<?php
	}
		$a++;
		
	}?>
	</tbody></table>
 	<?php
 	}
  }

}