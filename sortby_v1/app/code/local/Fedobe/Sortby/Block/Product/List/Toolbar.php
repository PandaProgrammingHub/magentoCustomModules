<?php 
class Fedobe_Sortby_Block_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    protected $_direction = 'asc';

    public function setCollection($collection)
    {
        parent::setCollection($collection);
 
        if ($this->getCurrentOrder()) {
            if($this->getCurrentOrder() == 'qty_ordered') 
                {
                
                    $this->getCollection()->getSelect()
                     ->joinLeft(
                            array('sfoi' => $collection->getResource()->getTable('sales/order_item')),
                             'e.entity_id = sfoi.product_id',
                             array('qty_ordered' => 'SUM(sfoi.qty_ordered)')
                         )
                     ->group('e.entity_id')
                     ->order('qty_ordered ' . $this->getCurrentDirection());
                 //echo "<script>alert('Best Sells');</script>".$this;
                } 
                else if($this->getCurrentOrder() == 'most_viewed') 
                {
                
                     $this->_collection->sortByReview($this->getCurrentDirection());
                 //echo "<script>alert('Most Viewed');</script>".$this;
                }   
            else
                {
                $this->getCollection()
                     ->setOrder($this->getCurrentOrder(), $this->getCurrentDirection())->getSelect();
                }
        }
 
        return $this;
    }
    
}