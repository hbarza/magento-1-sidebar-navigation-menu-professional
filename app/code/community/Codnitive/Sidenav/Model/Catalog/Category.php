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
 * Catalog sidebar category
 *
 * @category   Codnitive
 * @package    Codnitive_Sidenav
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_Sidenav_Model_Catalog_Category extends Mage_Catalog_Model_Category
{

    /**
     * Retrieve Thumbnail image URL
     *
     * @return string
     */
    public function getThumbnailImageUrl()
    {
        $url = false;
        if ($image = $this->getThumbnail()) {
            $url = Mage::getBaseUrl('media') . 'catalog/category/' . $image;
        }
        return $url;
    }
    
    /**
     * Retrieves product category path to active it
     * 
     * @param Mage_Catalog_Model_Category $category
     * @param array $classes
     * @return array
     */
    public function getProductCategoriesInDirectAccess($category, $classes)
    {
        $prodModel = Mage::registry('current_product');
        $categories = $prodModel->getCategoryIds();
        $catArray = array();
        foreach ($categories as $catId) {
            $catModel = Mage::getModel('catalog/category')->load($catId);
            $catArray = array_merge_recursive($catArray, explode('/', $catModel->getPath()));
        }
        $pathAllCats = array_merge_recursive($catArray, $categories);
        if (in_array($category->getId(), $pathAllCats)) {
            $classes[] = 'active';
        }

        return $classes;
    }

}
