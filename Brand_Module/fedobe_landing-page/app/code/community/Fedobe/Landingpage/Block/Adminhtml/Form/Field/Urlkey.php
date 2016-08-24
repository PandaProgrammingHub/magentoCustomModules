<?php

/**
 * @category    Fedobe
 * @package     Fedobe_Landingpage
 */
class Fedobe_Landingpage_Block_Adminhtml_Form_Field_Urlkey extends Mage_Adminhtml_Block_System_Config_Form_Field {

    /**
     * Retrieve the HTML for the element
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        if ($type = $this->getSplashType()) {
            if (($object = Mage::registry('splash_page')) !== null) {
                $this->setSplashBaseUrl($object->getUrlBase());
                $this->setUrlSuffix($object->getUrlSuffix());

                return parent::_getElementHtml($element) . Mage::helper('landingpage')->storecheckbox($element)
                        . $this->_getRouteJs($element);
            }
        }

        return parent::_getElementHtml($element) . Mage::helper('landingpage')->storecheckbox($element);
    }

    /**
     * Retrieve the JS to display the route
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getRouteJs($element) {
        return sprintf("
			<script type=\"text/javascript\">
				(function() {
					var inp = $('%s');
					var SPLASH_BASE_URL = '%s';
					var URL_SUFFIX = '%s';
                                        var chkurl = '%s';
                                        var inival = inp.getValue();
					
					inp.insert({'after': new Element('p', {'class': 'note', 'id': inp.id + '-note'})});
					
					var nt = $(inp.id + '-note');

					inp.observe('blur', function(event) {
                                                checkpageurlexists(inp.getValue(),chkurl,inival,nt);
						inp.setValue(inp.getValue().toLowerCase().replace(/([^a-z0-9\-\/]{1,})/, ''));
						nt.innerHTML = SPLASH_BASE_URL + inp.getValue() + URL_SUFFIX;
					});
					
					setTimeout(function() {
						inp.focus();
						inp.blur();
					}.bind(this), 1000);
				})();
                                function checkpageurlexists(url_key,chkurl,inivalue,nt){
                                    if(inivalue != url_key){
                                        new Ajax.Request(chkurl, {
                                            method: 'get',
                                            parameters: {url_key:url_key},
                                            onSuccess: function(response){
                                                if(response.responseText){
                                                    nt.innerHTML = '<span class=\'error\'>'+response.responseText+'</span><br/>'+ nt.innerHTML;
                                                }else{
                                                    nt.innerHTML = '<span style=\'color:#34a853;\'><b>You can Proceed</b></span><br/>'+ nt.innerHTML;
                                                }
                                            },
                                            onFailure:  function(response){
                                                //alert(response.responseText);
                                            }
                                        });
                                    }
                                }
			</script>
		", $element->getHtmlId(), $this->getSplashBaseUrl(), $this->getUrlSuffix(),Mage::helper("adminhtml")->getUrl('*/landingpage_page/checkuniqueurlkey'));
    }

}
