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

class Codnitive_Sidenav_Model_System_Config_Source_Thumbsize extends Mage_Core_Model_Config_Data
{

    const DEFAULT_VALUE = 0;
    const CUSTOM_VALUE = 1;

    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::DEFAULT_VALUE,
                'label' => Mage::helper('sidenav')->__('Default')
            ),
            array(
                'value' => self::CUSTOM_VALUE,
                'label' => Mage::helper('sidenav')->__('Custom')
            ),
        );
    }

}
