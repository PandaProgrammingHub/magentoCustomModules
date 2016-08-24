<?php

class Fedobe_Avatar_Carousel_Block_Adminhtml_System_Config_Fieldset_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."skin/adminhtml/default/default/images/fedobe";
        $instructions = <<<INSTRUC
                <div class="fedobe-info">
                    <table>
                        <tr>
                            <td>
                                <div class="fedobe-logo"></div>
                                <!--img src="$url/logo.png" alt="Fedobe Logo" style="width:175px;" /-->
                                <div class="social-icons">
                                    <a href="https://www.facebook.com/fedobe" style="background-position:-60px 0; width:30px; height:30px;" class="icon1-class" title="Facebook" target="_blank">&nbsp;</a>
                                    <a href="http://twitter.com/fedobe" style="background-position:0 0; width:30px; height:30px;" class="icon2-class" title="Twitter" target="_blank">&nbsp;</a>
                                    <a href="skype:support.fedobe?chat" style="background-position:-150px -30px; width:30px; height:30px;" class="icon3-class" title="Skype" target="_blank">&nbsp;</a>
                                </div>
                            </td>
                            <td>
                                <div><strong><a href="dev.fedobe.org/avatar/category-carousel/" target="_blank">Module Demo</a></strong></div>
                            </td>
                            <td>
                                <div>
                                    <ul>
                                        <li>
                                            <strong><a href="mailto:support@fedobe.com?subject=Fedobe+Store+Suppor" target="_blank">Email Support</a></strong>
                                        </li>
                                        <li>
                                            <strong><a href="http://support.fedobe.com" target="_blank">Helpdesk Support</a></strong>
                                        </li>
                                        <li>
                                            <strong><a href="skype:support.fedobe?chat" target="_blank">Skype Support</a></strong>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <ul>
                                        <li>
                                            <strong><a href="http://fedobe.com/magento/" target="_blank">Fedobe Magento Services</a></strong>
                                        </li>
                                        <li>
                                            <strong><a href="http://store.fedobe.com/" target="_blank">Fedobe Magento Store</a></strong>
                                        </li>
                                        <li>
                                             <strong><a href=" http://fedobe.com/contacts/" target="_blank">Request A Quote</a></strong>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <style>
                .fedobe-logo{
                    background: url($url/logo.png) 0 0 no-repeat;
                    width: 200px;
                    height: 54px;
                    background-size: 200px;
                }
                .fedobe-info{
                    background: #EAF0EE;
                    border: 1px solid #CCCCCC;
                    margin-bottom: 10px;
                    padding: 5px 0 5px 5px;
                }
                .social-icons a{
                    background: url($url/social-icons-sprite.png) 0 0 no-repeat;
                    display: inline-block;
                    background-color: #9e9e9e;
                    border-radius: 50%;
                }
                .social-icons a.icon1-class:hover {
                    background-color: #3c599b;
                }
                .social-icons a.icon2-class:hover {
                    background-color: #1ca8e3;
                }
                .social-icons a.icon3-class:hover {
                    background-color: #4975b6;
                }
                .fedobe-info table td{
                    width:25%;
                    padding: 0 25px;
                }
                </style>
INSTRUC;
        return $instructions;
    }


}

