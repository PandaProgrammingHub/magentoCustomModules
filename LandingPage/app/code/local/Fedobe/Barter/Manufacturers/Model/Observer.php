<?php
class Fedobe_Barter_Manufacturers_Model_Observer {
    public function insertBlock($observer) {
        $request = Mage::app()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $matchneeded = "{$module}_{$controller}_{$action}";
        //Here let's check for category
        if ($matchneeded == 'catalog_category_view') {
            $_block = $observer->getBlock();
            $_type = $_block->getType();
            if ($_type == 'catalog/product_price') {
                /* Clone block instance */
                $_child = clone $_block;
                /* set another type for block */
                $_child->setType('manufacturers/searchresult');
                /* set child for block */
                $_block->setChild('child' . $_child->getProduct()->getId(), $_child);
                /* set our template */
                $_block->setTemplate('fedobe/manufacturers/productlist.phtml');
            }
        }
        if($matchneeded == 'catalog_product_view'){
            $_block = $observer->getBlock();
            $_type = $_block->getType();
            if ($_type == 'catalog/product_view_additional') {
                /* Clone block instance */
                $_child = clone $_block;
                /* set another type for block */
                $_child->setType('manufacturers/searchresult');
                /* set child for block */
                $_block->setChild('child', $_child);
                /* set our template */
                $_block->setTemplate('fedobe/manufacturers/productsfromsamebrand.phtml');
            }
        }
    }
}
