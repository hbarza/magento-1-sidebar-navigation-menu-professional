<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_Sidenav
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2011 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Codnitive_Sidenav_Model_System_Config_Source_Before extends Mage_Core_Model_Config_Data
{

    protected $_blocks = array(
        'before_all' => 'Before All',
        'layered_nav' => 'Layered Navigation',
        'cart_sidebar' => 'My Cart',
        'customer_nav' => 'Customer Account Navigation',
        'customer_nav_after' => 'No! After Customer Account Navigation'
    );

    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        
        foreach ($this->_blocks as $key => $val) {
            $options[] = array(
                'value' => $key,
                'label' => Mage::helper('sidenav')->__($val)
            );
        }
        
        return $options;
    }

}
