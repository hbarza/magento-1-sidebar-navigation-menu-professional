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

class Codnitive_Sidenav_Model_System_Config_Source_Parent extends Mage_Core_Model_Config_Data
{
    
    const ROOT_VALUE     = 'root';
    const CURRENT_VALUE  = 'current';
    const SIBLINGS_VALUE = 'siblings';
    const CHILDREN_VALUE = 'children';

    protected $_options;

    /**
     * Fills the select field with values
     * 
     * @return array
     */
    public function toOptionArray()
    {
        if (!isset($this->_options)) {
            $options = array(
                array(
                    'value' => self::ROOT_VALUE,
                    'label' => Mage::helper('sidenav')->__('Store Base'),
                ),
                array(
                    'value' => self::SIBLINGS_VALUE,
                    'label' => Mage::helper('sidenav')->__('Current Category and Its Siblings'),
                ),
                array(
                    'value' => self::CURRENT_VALUE,
                    'label' => Mage::helper('sidenav')->__('Current Category and Children'),
                ),
                array(
                    'value' => self::CHILDREN_VALUE,
                    'label' => Mage::helper('sidenav')->__('Children of Current Category'),
                ),
            );
            
            /**
             * Based on RicoNeitzel_VertNav extension
             * Thanks to Rico Neitzel
             *
             */
            $resource = Mage::getModel('catalog/category')->getResource();
            $select = $resource->getReadConnection()->select()->reset()
                    ->from($resource->getTable('catalog/category'), new Zend_Db_Expr('MAX(`level`)'));
            $maxDepth = $resource->getReadConnection()->fetchOne($select);
            for ($i = 2; $i < $maxDepth; $i++) {
                $options[] = array(
                    'value' => $i,
                    'label' => Mage::helper('sidenav')->__('Category Level %d', $i),
                );
            }
            $this->_options = $options;
        }
        return $this->_options;
    }

}
