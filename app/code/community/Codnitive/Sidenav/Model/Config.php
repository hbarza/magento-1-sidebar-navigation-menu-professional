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
class Codnitive_Sidenav_Model_Config /*extends Mage_Catalog_Model_Category*/
{
    
    const PATH_NAMESPACE      = 'codnitivecatalog';
    const EXTENSION_NAMESPACE = 'sidenav';
    
    const EXTENSION_NAME    = 'Sidebar Navigation Menu Professional';
    const EXTENSION_VERSION = '1.8.14';
    const EXTENSION_EDITION = '';

    public static function getNamespace()
    {
        return self::PATH_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
    }

    public function getExtensionName()
    {
        return self::EXTENSION_NAME;
    }

    public function getExtensionVersion()
    {
        return self::EXTENSION_VERSION;
    }

    public function getExtensionEdition()
    {
        return self::EXTENSION_EDITION;
    }
    
    protected function _getHelper()
    {
        return Mage::helper('sidenav');
    }

    /**
     * Check for extension enable option status
     *
     * @deprecated since version 1.7.58
     * @return boolean
     */
    public function checkActive()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'active');
    }
    
    
    /**
     * Check for extension enable option status
     *
     * @return boolean
     */
    public function isActive()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'active');
    }

    /**
     * Check for top navigation remove setting
     *
     * @return boolean
     */
    public function removeTopNav()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'remove_top_nav');
    }

    /**
     * Check for remove browse by category from layered navigation
     *
     * @return boolean
     */
    public function removeLayeredNavCat()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'remove_layered_nav');
    }

    /**
     * Get column option value to define selected column
     *
     * @return string
     */
    public function getColumn()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'column');
    }

    /**
     * Returns sidenav block position in side columns
     *
     * @return string
     */
    public function defaultBefore()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'before');
    }

    /**
     * Gets defined parent category
     *
     * @return string
     */
    public function getParent()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'parent');
    }

    /**
     * Get gategory maximal depth number
     *
     * @return string
     */
    public function getMaxDepth()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'max_depth');
    }

    /**
     * Check for extension enable option status
     *
     * @return boolean
     */
    public function activeProductCategoriesInDirectAccess()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'product_direct_access');
    }

    /**
     * Retruns block title type
     *
     * @return string
     */
    public function getTitleType()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'title');
    }

    /**
     * Retruns static title name
     *
     * @return string
     */
    public function getStaticTitle()
    {
        $title = Mage::getStoreConfig(self::getNamespace() . 'static_title');
        return !empty($title) ? $title : 'Categories';
    }

    /**
     * Get show product count setting
     *
     * @return boolean
     */
    public function showProductCount()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'product_count');
    }

    /**
     * Get remove product count for categories by zero product number
     *
     * @return boolean
     */
    public function removeZeroCount()
    {
        if (!$this->showProductCount()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'remove_zero_count');
    }

    /**
     * Returns that home page link must show or not
     *
     * @return boolean
     */
    public function showHome()
    {
        if (!$this->isActive()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'home');
    }

    /**
     * Returns that CODNITIVE support logo must show or not
     *
     * @return boolean
     */
    public function showSupportLogo()
    {
        if (!$this->isActive()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'logo');
    }

    /**
     * Returns that CODNITIVE support logo must be image or text
     *
     * @return boolean
     */
    public function showAsImage()
    {
        if (!$this->showSupportLogo()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'show_image');
    }
    
    /**
     * Get collapsible menu status
     *
     * @return boolean
     */
    public function isCollapsible()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'collapsible');
    }
    
    /**
     * Retrieves name of parent categories use for open/close menu or use as link
     * 
     * @return boolean
     */
    public function expandByParentName()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'expand_by_name');
    }
    
    /**
     * Get collapsible icon position
     *
     * @return string
     */
    public function getCollapsibleIconPosition()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'collapsible_icon_position');
    }
    
    /**
     * Get collapsible icon type
     *
     * @return string
     */
    public function getCollapsibleIconType()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'collapsible_icon_type');
    }
    
    /**
     * Check for extension enable option status
     *
     * @return boolean
     */
    public function isThumbImageActive()
    {
        return Mage::getStoreConfigFlag(self::getNamespace() . 'thumbnail');
    }
    
    /**
     * Get thumbnail position
     *
     * @return string
     */
    public function getThumbPosition()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'thumb_position');
    }

    /**
     * Get thumbnail size setting
     *
     * @return string(numeric)
     */
    public function getThumbSize()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'thumb_size');
    }

    /**
     * Get thumbnail width size
     *
     * @return string
     */
    public function getThumbWidth()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'thumb_width');
    }

    /**
     * Get thumbnail height size
     *
     * @return string
     */
    public function getThumbHeight()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'thumb_height');
    }
    
    /**
     * Retrieves it must load store root category if parent category isn't availabel
     * 
     * @return string
     */
    public function loadNoCategory()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'load_no_category');
    }
    
    /**
     * Retrieves we are currently in homepage or not
     * 
     * @return boolean
     */
    public function isHome()
    {
        return $this->_getHelper()->isHome();
    }
    
    /**
     * Returns hide settings in homepage
     * 
     * @return boolean
     */
    public function showInHome()
    {
        if (!$this->isActive()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'home_active');
    }
    
    /**
     * Returns hide settings in homepage
     * 
     * @return boolean
     */
    public function hideTopInHome()
    {
        if (!$this->removeTopNav()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'home_hide_top');
    }
    
    /**
     * Retrieves home link must remove in homepage
     * 
     * @return boolean
     */
    public function removeHomeInHome()
    {
        if (!$this->showHome()) {
            return true;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'home_hide_home');
    }
    
    /**
     * Retrieves sidebar navigation is available in homepage content
     * 
     * @return boolean
     */
    public function availabelInHomeContent()
    {
        if (!$this->showInHome()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'home_content');
    }
    
    /**
     * Returns hide settings in customer account pages
     * 
     * @return boolean
     */
    public function showInCustomerAccount()
    {
        if (!$this->isActive()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'customer_active');
    }
    
    /**
     * Returns hide settings in customer account pages
     * 
     * @return boolean
     */
    public function hideTopInCustomerAccount()
    {
        if (!$this->removeTopNav()) {
            return false;
        }
        return Mage::getStoreConfigFlag(self::getNamespace() . 'customer_hide_top');
    }

    /**
     * Returns column option value to define selected column in customer account page
     *
     * @return string
     */
    public function getColumnInCustomerAccount()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'customer_column');
    }

    /**
     * Returns sidenav block position in side columns in customer account page
     *
     * @return string
     */
    public function customerAccountBefore()
    {
        return Mage::getStoreConfig(self::getNamespace() . 'customer_before');
    }

}
