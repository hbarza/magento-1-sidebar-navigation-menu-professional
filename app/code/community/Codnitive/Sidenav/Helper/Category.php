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

/**
 * Catalog sidebar category helper
 *
 * @category   Codnitive
 * @package    Codnitive_Sidenav
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_Sidenav_Helper_Category extends Mage_Catalog_Helper_Category
{

    /**
     * Retrieve current store categories
     *
     * @param   boolean|string $sorted
     * @param   boolean $asCollection
     * @return  Varien_Data_Tree_Node_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection|array
     */
    public function getStoreCategories($parent = null, $recursionLevel = 0, $sorted = false, $asCollection = false, $toLoad = true)
    {
        $useFlat = true;
        $parent = (null === $parent) ? $this->_getParentCategory() : $parent;
        if (!$parent) {
            return array();
        }
        
        $id = 0;
        if (Mage::registry('current_product')) { 
            $id = Mage::registry('current_product')->getId();
        }
        else if ($reqPath = Mage::app()->getRequest()) {
            $id = $reqPath->getParam('id', $reqPath->getPathInfo());
        }
        $cacheKey = sprintf('codnitive-sidenav-%d-%d-%d-%d-%d-%d', $parent, $recursionLevel, $sorted, $asCollection, $toLoad, $id);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         * 
         * @var $category Codnitive_Sidenav_Model_Category
         */
        $category = Mage::getModel('sidenav/catalog_category');
        
        if (!$category->checkId($parent)) {
            $useFlat = false;
        }

        $recursionLevel = (intval($recursionLevel) > 0) ? $recursionLevel : max(0, Mage::getModel('sidenav/config')->getMaxDepth());
        if (Mage::helper('catalog/category_flat')->isEnabled() && $useFlat) {
            $storeCategories = $this->_getFlatCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
        }
        else {
            $storeCategories = $category->getCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
        }

        $this->_storeCategories[$cacheKey] = $storeCategories;
        return $storeCategories;
    }

    /**
     * Return array or collection of categories for flat categories enabled
     *
     * @param integer $parent
     * @param integer $recursionLevel
     * @param boolean|string $sorted
     * @param boolean $asCollection
     * @param boolean $toLoad
     * @return array|Varien_Data_Collection
     */
    protected function _getFlatCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad)
    {
        $flat = Mage::getResourceModel('catalog/category_flat');
        return $flat->getCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Get parent category defined be user
     *
     * @return string|int
     */
    protected function _getParentCategory()
    {
        $parent = null;
        $config = Mage::getModel('sidenav/config');
        $parentConfig = $config->getParent();
        $category = Mage::registry('current_category');
        $itIsLevel = false;
        
        switch ($parentConfig) {
            case 'siblings':
                if ($category) {
                    $parent = $category->getParentId();
                }
                break;
                
            case 'children':
                if ($category) {
                    $parent = $category->getId();
                }
                break;

            case 'current':
                if ($category) {
                    $parent = $category->getParentId();
                }
                break;

            case 'root':
                $parent = Mage::app()->getStore()->getRootCategoryId();
                break;

            default:
                /**
                 * Display from level N
                 * 
                 * Based on RicoNeitzel_VertNav extension
                 * Thanks to Rico Neitzel
                 */
                $itIsLevel = true;
                if (!$category) {
                    $category = Mage::getModel('sidenav/catalog_category')->load(Mage::app()->getStore()->getRootCategoryId());
                }
                $fromLevel = $parentConfig;
                if ($category && $category->getLevel() >= $fromLevel) {
                    while ($category->getLevel() > $fromLevel) {
                        $category = $category->getParentCategory();
                    }
                    $parent = $category->getId();
                }
                break;
        }
        
        if (!$parent && $config->loadNoCategory() === 'root' && !$itIsLevel) {
            $parent = Mage::app()->getStore()->getRootCategoryId();
        }
        
        return $parent;
    }

}
