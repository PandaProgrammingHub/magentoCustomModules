<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Fedobe_Sortby_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    protected function _getSelectCountSql($select = null, $resetLeftJoins = true)
    {
       $this->_renderFilters();
       $countSelect = (is_null($select)) ? $this->_getClearSelect() :  $this->_buildClearSelect($select);
       
       if(count($countSelect->getPart(Zend_Db_Select::GROUP)) > 0) {
           $countSelect->reset(Zend_Db_Select::GROUP);
       }
 
       $countSelect->columns('COUNT(DISTINCT e.entity_id)');
       if ($resetLeftJoins) {
           $countSelect->resetJoinLeft();
       }
       return $countSelect;
    }
    public function sortByReview($dir)
    {
        $table = $this->getTable('review/review');
        $entity_code_id = Mage::getModel('review/review')->getEntityIdByCode(Mage_Rating_Model_Rating::ENTITY_PRODUCT_CODE); 
        $cond = $this->getConnection()->quoteInto('t2.entity_pk_value = e.entity_id and ','').$this->getConnection()->quoteInto('t2.entity_id = ? ',$entity_code_id); 
        $this->getSelect()->joinLeft(array('t2'=>$table), $cond,array('review' => new Zend_Db_Expr('count(review_id)'))) 
       ->group('e.entity_id')->order("review $dir"); 
    }
}