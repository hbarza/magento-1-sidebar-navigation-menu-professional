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

class Codnitive_Sidenav_Model_System_Config_Source_Column extends Mage_Core_Model_Config_Data
{

    const LEFT_COL_VALUE = 'left_col';
    const RIGHT_COL_VALUE = 'right_col';
    const BOTH_COL_VALUE = 'both_col';

    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::LEFT_COL_VALUE,
                'label' => Mage::helper('sidenav')->__('Left Column')
            ),
            array(
                'value' => self::RIGHT_COL_VALUE,
                'label' => Mage::helper('sidenav')->__('Right Column')
            ),
            array(
                'value' => self::BOTH_COL_VALUE,
                'label' => Mage::helper('sidenav')->__('Both Columns')
            ),
        );
    }

}
