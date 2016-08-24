<?php

class Inchoo_CustomerCollection_Model_Resource_Customer_Totals_Collection extends Mage_Reports_Model_Resource_Customer_Totals_Collection
{
    
    /**
     * Add Total Order Amoount field to select
     *
     * @return Inchoo_MagenticAlpha_Model_Resource_Customer_Totals_Collection
     */
    public function addTotalOrderAmount()
    {
        $this->addFieldToFilter('state', array('neq' => Mage_Sales_Model_Order::STATE_CANCELED));
        $this->getSelect()
            ->columns(array('orders_sum_amount' => 'SUM(main_table.subtotal)'));

        return $this;
    }
    
    /**
     * Add billing fields to select
     *
     * @return Inchoo_MagenticAlpha_Model_Resource_Customer_Totals_Collection
     */
    public function addBillingData()
    {
        $billingAliasName = 'billing_o_a';
        $joinTable = $this->getTable('sales/order_address');

        $this->getSelect()
            ->joinLeft(
                array($billingAliasName => $joinTable),
                "(main_table.entity_id = {$billingAliasName}.parent_id"
                    . " AND {$billingAliasName}.address_type = 'billing')",
                array($billingAliasName . '.postcode', 
                    $billingAliasName . '.telephone',
                    $billingAliasName . '.region',
                    $billingAliasName . '.country_id'
                    )            
            );
                    
        return $this;
    }
    
     /**
     * Add customer fields to select
     *
     * @return Inchoo_MagenticAlpha_Model_Resource_Customer_Totals_Collection
     */
    public function addCustomerData()
    {
        $customerAliasName = 'customer_e_a';
        $joinTable = $this->getTable('customer/entity');

        $this->getSelect()
            ->joinLeft(
                array($customerAliasName => $joinTable),
                "(main_table.customer_id = {$customerAliasName}.entity_id)",
                array($customerAliasName . '.created_at as customer_since', 
                    $customerAliasName . '.website_id'
                    )          
            );
    
        return $this;
    }
}

