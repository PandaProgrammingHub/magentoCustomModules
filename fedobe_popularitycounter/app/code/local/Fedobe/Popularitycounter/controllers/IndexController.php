<?php
class Fedobe_Popularitycounter_IndexController extends Mage_Core_Controller_Front_Action {        
    public function indexAction() {

    $productId = $this->getRequest()->getParam('productId');
    $item =  $this->getRequest()->getParam('item');
    //$productId = 120;
    //$item = 'love';
    
    
  
    if(Mage::getSingleton('customer/session')->isLoggedIn()){
 		
 		

        $usercollection = $this->_getUserCollection($productId);
                  
          foreach ($usercollection as $row) {
              $id=$row->getPopularitycounterusers_id();
              $love=$row->getLove();
              $viewed=$row->getViewed();
              $like=$row->getLike();
              $recommended=$row->getRecommended();
             }
             
            if($id)
            { 
                if ($item == 'love')
                {
                    if ($love>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$love);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId);	
                    }
                }
                elseif($item == 'viewed')
                {
                    if ($viewed>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$viewed);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }
                elseif($item == 'like')
                {
                    if ($like>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$like);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }
                elseif($item == 'recommended')
                {
                    if ($recommended>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$recommended);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }

            }

            
        else
            	{	
                    if($item == 'love')
                    {
                         $this->_insertUsers($item,$productId);
                         $this->_Voters($item,$productId);
                         $this->_getCurrentVoterCollection($item,$productId);
                    }
                   else if($item == 'viewed')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
                   else if($item == 'like')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
                  else  if($item == 'recommended')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
            	}
            	
            



    }
    else{
        
     $usercollection = $this->_getUserCollection($productId);
                  
          foreach ($usercollection as $row) {
              $id=$row->getPopularitycounterusers_id();
              $love=$row->getLove();
              $viewed=$row->getViewed();
              $like=$row->getLike();
              $recommended=$row->getRecommended();
             }
             
            if($id)
            { 
                if ($item == 'love')
                {
                    if ($love>0)
                    {
                       //echo "allready Voted";
                        $this->_minusUsers($item,$id,$love);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId);   
                    }
                }
                elseif($item == 'viewed')
                {
                    if ($viewed>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$viewed);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }
                elseif($item == 'like')
                {
                    if ($like>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$like);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }
                elseif($item == 'recommended')
                {
                    if ($recommended>0)
                    {
                        //echo "allready Voted";
                        $this->_minusUsers($item,$id,$recommended);
                        $this->_minusVoters($item,$id,$productId);
                        $this->_getCurrentVoterCollection($item,$productId);
                    }
                    else {
                    $this->_updateUsers($item,$id);
                    $this->_Voters($item,$productId);
                    $this->_getCurrentVoterCollection($item,$productId); 
                    }
                }

            }

            
        else
                {   
                    if($item == 'love')
                    {
                         $this->_insertUsers($item,$productId);
                         $this->_Voters($item,$productId);
                         $this->_getCurrentVoterCollection($item,$productId);
                    }
                   else if($item == 'viewed')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
                   else if($item == 'like')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
                  else  if($item == 'recommended')
                    {
                            $this->_insertUsers($item,$productId);
                            $this->_Voters($item,$productId);
                            $this->_getCurrentVoterCollection($item,$productId);
                    }
                }
                
            
   
            


        
    }
   
    

}
    private function _getCurrentCustomerId() {
    	return Mage::getSingleton('customer/session')->getCustomer()->getId();
    }
    private function _getCurrentSessionId() {
    	return Mage::getSingleton('customer/session')->getSessionId();
    }
    private function _getVotersModel(){
        return Mage::getModel('popularitycounter/popularitycountervotes');
    }
    private function _getUsersModel() {
       return Mage::getModel('popularitycounter/popularitycounterusers');
    }
    private function _getUserCollection($productId)
    {
        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $CurrentCustomerId = $this->_getCurrentCustomerId();
            $usercollection= Mage::getModel('popularitycounter/popularitycounterusers')->getCollection()
                   ->addFieldToFilter('product_id',array('eq'=>$productId))
                   ->addFieldToFilter('customer_id',array('eq'=>$CurrentCustomerId));
        } 
        else{
            $CurrentSessionId = $this->_getCurrentSessionId();
            $usercollection= Mage::getModel('popularitycounter/popularitycounterusers')->getCollection()
                   ->addFieldToFilter('product_id',array('eq'=>$productId))
                   ->addFieldToFilter('customer_id',array('eq'=>$CurrentSessionId)); 

        }
        return $usercollection;

    }
    private function _getVoterCollection($productId)
    {
        $votercollection= Mage::getModel('popularitycounter/popularitycountervotes')->getCollection()
                   ->addFieldToFilter('product_id',array('eq'=>$productId));
        
        return $votercollection;

    }
    private function _updateUsers($items,$id)
    {
        
        $usermodel = $this->_getUsersModel();
        
        if ($items == 'love')
        {
            
                    $usermodel->load($id)->setLove(1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'viewed')
        { 
             
                    $usermodel->load($id)->setViewed(1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                           //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'like')
        {
             
                    $usermodel->load($id)->setLike(1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                           // echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'recommended')
        {
              $usermodel->load($id)->setRecommended(1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                    
        }
    }
    private function _insertUsers($items,$productId)
    {
       // echo $productId;
        //echo $items;
        $usermodel = $this->_getUsersModel();
        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $CurrentCustomerId = $this->_getCurrentCustomerId();
            //echo $CurrentCustomerId;
        }
        else{
            $CurrentCustomerId = $this->_getCurrentSessionId();
            //echo $CurrentCustomerId;
        }

        if ($items == 'love')
        {
            $data = array('product_id'=>$productId,'customer_id'=>$CurrentCustomerId,'love'=>1);
                        $usermodel->addData($data);
                                    try {
                                         $usermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                   // echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'viewed')
        {
             
                    $data = array('product_id'=>$productId,'customer_id'=>$CurrentCustomerId,'viewed'=>1);
                        $usermodel->addData($data);
                                    try {
                                         $usermodel->save();
                                   // echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'like')
        {
           
                    $data = array('product_id'=>$productId,'customer_id'=>$CurrentCustomerId,'like'=>1);
                        $usermodel->addData($data);
                                    try {
                                         $usermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                   // echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'recommended')
        {
             
                     $data = array('product_id'=>$productId,'customer_id'=>$CurrentCustomerId,'recommended'=>1);
                        $usermodel->addData($data);
                                    try {
                                         $usermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                    
        }
    }
    private function _minusUsers($items,$id,$uitems)
    {
        
        $usermodel = $this->_getUsersModel();
        
        if ($items == 'love')
        {
            
                    $usermodel->load($id)->setLove($uitems-1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'viewed')
        { 
             
                    $usermodel->load($id)->setViewed($uitems-1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                           //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'like')
        {
             
                    $usermodel->load($id)->setLike($uitems-1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                           // echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'recommended')
        {
              $usermodel->load($id)->setRecommended($uitems-1);
                    try {
                            $usermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                    
        }
    }
    private function _Voters($item,$productId)
    {
        $votercollection = $this->_getVoterCollection($productId);
        foreach ($votercollection as $row) {
              $id=$row->getPopularitycountervotes_id();
              $love=$row->getLove();
              $viewed=$row->getViewed();
              $like=$row->getLike();
              $recommended=$row->getRecommended();
        }
        
        if($id)
            { 
                if ($item == 'love')
                {
                    $this->_updatevoters($item,$id,$love); 
                }
                elseif($item == 'viewed')
                {
                    $this->_updatevoters($item,$id,$viewed); 
                }
                elseif($item == 'like')
                {
                    $this->_updatevoters($item,$id,$like); 
                }
                elseif($item == 'recommended')
                {
                    $this->_updatevoters($item,$id,$recommended); 
                }

            }
        else
                {   
                    if($item == 'love')
                    {
                         $this->_insertVoters($item,$productId);
                    }
                   else if($item == 'viewed')
                    {
                            $this->_insertVoters($item,$productId);
                    }
                   else if($item == 'like')
                    {
                            $this->_insertVoters($item,$productId);
                    }
                  else  if($item == 'recommended')
                    {
                            $this->_insertVoters($item,$productId);
                    }
                }


    }
    private function _updateVoters($items,$id,$vitems)
    {
        
        $votermodel = $this->_getVotersModel();
        
        if ($items == 'love')
        {
            
                    $votermodel->load($id)->setLove($vitems+1);
                    try {
                            $votermodel->save();
                           // echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'viewed')
        { 
             
                    $votermodel->load($id)->setViewed($vitems+1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'like')
        {
             
                    $votermodel->load($id)->setLike($vitems+1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'recommended')
        {
              $votermodel->load($id)->setRecommended($vitems+1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Inserted.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                    
        }
    }
    private function _insertVoters($items,$productId)
    {
       // echo $productId;
        //echo $items;
        $votermodel = $this->_getVotersModel();
        

        if ($items == 'love')
        {
            $data = array('product_id'=>$productId,'love'=>1);
            $votermodel->addData($data);
                                    try {
                                         $votermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'viewed')
        {
             
                    $data = array('product_id'=>$productId,'viewed'=>1);
                        $votermodel->addData($data);
                                    try {
                                         $votermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'like')
        {
           
                    $data = array('product_id'=>$productId,'like'=>1);
                        $votermodel->addData($data);
                                    try {
                                         $votermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                
        }
        elseif ($items == 'recommended')
        {
             
                     $data = array('product_id'=>$productId,'recommended'=>1);
                        $votermodel->addData($data);
                                    try {
                                         $votermodel->save();
                                    //echo "Data successfully Inserted.";
                                    } catch (Exception $e){
                                    //echo $e->getMessage();
                                    }
                    
        }
    }
    private function _minusVoters($items,$id,$productId)
    {
        $votermodel = $this->_getVotersModel();

        $votercollection = $this->_getVoterCollection($productId);
        foreach ($votercollection as $row) {
              $id=$row->getPopularitycountervotes_id();
              $love=$row->getLove();
              $viewed=$row->getViewed();
              $like=$row->getLike();
              $recommended=$row->getRecommended();
        }
        if ($items == 'love')
        {
            
                    $votermodel->load($id)->setLove($love-1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Reduced.";
                        } catch (Exception $e){
                                           // echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'viewed')
        { 
             
                    $votermodel->load($id)->setViewed($viewed-1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Reduced.";
                        } catch (Exception $e){
                                          //  echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'like')
        {
             
                    $votermodel->load($id)->setLike($like-1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Reduced.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                
        }
        elseif ($items == 'recommended')
        {
              $votermodel->load($id)->setRecommended($recommended-1);
                    try {
                            $votermodel->save();
                            //echo "Data successfully Reduced.";
                        } catch (Exception $e){
                                            //echo $e->getMessage();
                                            }
                    
        }

    }
    private function _getCurrentVoterCollection($items,$productId){

        $votermodel = $this->_getVotersModel();

        $votercollection = $this->_getVoterCollection($productId);
        foreach ($votercollection as $row) {
              $id=$row->getPopularitycountervotes_id();
              $love=$row->getLove();
              $viewed=$row->getViewed();
              $like=$row->getLike();
              $recommended=$row->getRecommended();
        }

        if ($items == 'love')
        {
            
            $votercount = $love ;      
                
        }
        elseif ($items == 'viewed')
        { 
             $votercount = $viewed ; 
                    
                
        }
        elseif ($items == 'like')
        {
             $votercount = $like ; 
        }
        elseif ($items == 'recommended')
        {
              
              $votercount = $recommended ;       
        }
        echo $votercount;
    }
       
}