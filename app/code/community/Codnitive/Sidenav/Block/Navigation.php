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

class Codnitive_Sidenav_Block_Navigation extends Mage_Catalog_Block_Navigation
{

    /**
     * Extension config model object
     *
     */
    protected $_config;

    /**
     * Construct parent and define $_config
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_config = Mage::getModel('sidenav/config');
    }
    
    /**
     * Retrieves extension's configuration settings
     * 
     * @return Codnitive_Sidenav_Model_Config
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getModel('sidenav/config');
        }
        
        return $this->_config;
    }

    /**
     * Get store categories navigation menu
     *
     * @return string
     */
    public function getCategoriesNavMenu()
    {
        $navigationMenu = $this->renderCategoriesMenuHtml(0);
        return $navigationMenu ? $navigationMenu : false;
    }
    
    /**
     * We set cache to null when product direct access option is enabled and customer
     * is in product page to avoid wrong category tree showing with enabled caches
     * 
     * Adds 1 extra second to page load
     * Ultra version has caching and best performance
     * 
     * @return null
     */
    public function getCacheLifetime()
    {
        $condition = (Mage::registry('current_product') !== null) 
                && ($this->getConfig()->activeProductCategoriesInDirectAccess());
        if ($condition) {
            return null;
        }
        
        return parent::getCacheLifetime();
    }

    /**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection
     */
    public function getStoreCategories()
    {
        return Mage::helper('sidenav/category')->getStoreCategories();
    }
    
    /**
     * Returns category model
     * 
     * @return Codnitive_Sidenav_Model_Category
     */
    protected function _getCategoryModel()
    {
        return Mage::getModel('sidenav/catalog_category');
    }
    
    /**
     * Retruns data helper
     * 
     * @return Codnitive_Sidenav_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('sidenav');
    }

    /**
     * Render category to html
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int Nesting level number
     * @param boolean Whether ot not this item is last, affects list item class
     * @param boolean Whether ot not this item is first, affects list item class
     * @param boolean Whether ot not this item is outermost, affects list item class
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @param boolean Whether ot not to add on* attributes to list item
     * @return string
     */
    protected function _renderCategoryMenuItemHtml(
            $category, $level = 0, $isLast = false, 
            $isFirst = false, $isOutermost = false, $outermostItemClass = '', 
            $childrenWrapClass = '', $noEventAttributes = false
    ) {
        if (!$category->getIsActive()) {
            return '';
        }
        
        $config = $this->getConfig();
        $html = array();
        $expanded = null;
        $ulThumb = '';
        $image = '';
        $thumb = '';
        $htmlLi = '';

        // get all children
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = (array) $category->getChildrenNodes();
            $childrenCount = count($children);
        }
        else {
            $children = $category->getChildren();
            if (!$this->_getHelper()->isSearchResultsPage()) {
                $childrenCount = $children->count();
            }
            else {
                if (is_string($children)) {
                    $children = explode(',', $children);
                }
                $childrenCount = count($children);
            }
        }
        
        // select active children
        $activeChildren = array();
        foreach ($children as $child) {
            if ($child->getIsActive()) {
                $activeChildren[] = $child;
            }
        }
        $activeChildrenCount = count($activeChildren);
        $hasActiveChildren = ($activeChildrenCount > 0);

        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        if ($this->isCategoryActive($category)) {
            $classes[] = 'active';
        }
        else if ((Mage::registry('current_product') !== null) && ($config->activeProductCategoriesInDirectAccess())) {
            $classes = $this->_getCategoryModel()->getProductCategoriesInDirectAccess($category, $classes);
        }
        
        $linkClass = '';
        if ($isOutermost && $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="' . $outermostItemClass . '"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        if ($hasActiveChildren) {
            $classes[] = 'parent';
        }

        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
        if ($hasActiveChildren && !$noEventAttributes) {
            $attributes['onmouseover'] = 'toggleMenu(this,1)';
            $attributes['onmouseout'] = 'toggleMenu(this,0)';
        }

        // assemble list item with attributes
        $thumbWidth = 14;
        $thumbHeight = 14;
        $thumbPosition = $config->getThumbPosition();
        $liMarginLeft = 0;
        $ulMarginLeft = 5;
        $ulPaddingLeft = 10;

        // define image thumbnail variables
        if ($config->isThumbImageActive()) {
            if ($config->getThumbSize()) {
                $thumbWidth = $config->getThumbWidth();
                $thumbHeight = $config->getThumbHeight();
            }
            $thumbnail = $this->_getCategoryModel()->load($category->getId())->getThumbnailImageUrl();
            $ulThumb = ' ul-thumb';
            if (!empty($thumbnail)) {
                $image = '<img class="thumb-img-' . $thumbPosition . '" src="' . $thumbnail . '" style= "width:' . $thumbWidth . 'px; height:' . $thumbHeight . 'px; float: ' . $thumbPosition . ';" />';
                $thumb = ' thumb';
        
                if ($thumbPosition === 'left') {
                    if ($config->isCollapsible() && $config->isThumbImageActive()) {
                        $liMarginLeft = $thumbWidth + 3;
                        $ulMarginLeft = 0;
                    }
                    else {
                        $liMarginLeft = 0;
                        $ulMarginLeft = $thumbWidth + 3;
                    }
                    $ulPaddingLeft = 0;
                }
            }
            else {
                $thumb = ' no-thumb';
                $liMarginLeft = ($thumbPosition === 'right') ? 0 : $thumbWidth + 3;
                if ($thumbPosition === 'left') {
                    $ulMarginLeft = 0;
                    $ulPaddingLeft = 0;
                }
            }
        }
        
        $collapsibleClass = '';
        if ($config->isCollapsible()) {
            $collapsibleClass = ' collapsible';
        }

        // add collapsible arrow and wrraper
        $arrow = '';
        $extraStyle = '';
        $collapsibleIconPosition = $config->getCollapsibleIconPosition();
        if ($config->isCollapsible()) {
            $width = $config->getCollapsibleIconType() === 'arrow' ? 8 : 16;
            $height = 0;
            $expanded = 0;
            if ($hasActiveChildren) {
                $width = $config->getCollapsibleIconType() === 'arrow' ? 8 : 16;
                $height = 16;
            }
            if ($height == 0) {
                $extraStyle = ' display:none;';
            }
            if ($height == 0 && $collapsibleIconPosition === 'left') {
                $liMarginLeft += $width;
            }
            if ($this->isCategoryActive($category)) {
                $expanded = 1;
            }
            $expanded = ' expanded="' . $expanded .'"';
            $spanOnclick = 'onclick="Codnitive.expandMenu(this.parentNode)';
            $spanClass   = $config->getCollapsibleIconType() . '-' . $collapsibleIconPosition;
            $arrow = '<span class="' . $spanClass . ' " ' . $spanOnclick . '" style="width: ' . $width . 'px; height: ' . $height . 'px;' . $extraStyle . '"></span>';
        }
            
        $htmlLi .= '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . $thumb . $collapsibleClass . '"';
        }
        
        $htmlLi .= ' style="margin-left: ' . $liMarginLeft . 'px;' . '">';
        $html[] = $htmlLi;
        
        $html[] = $arrow;

        // add wrapper
        $aClass  = '';
        $aStyle  = '';
        $onclick = '';
        if ($config->isCollapsible() || $config->isThumbImageActive()) {
            $wrapperMargin = ($config->isCollapsible() && $collapsibleIconPosition === 'left') ? 14 : 0;
            $extraMargin = !$config->isThumbImageActive() ? 0 : (!empty($thumbnail) && ($thumbPosition === 'left')) ? $thumbWidth + 3 : 0;
            $collWrapper = $wrapperMargin + $extraMargin;

            // makes parent category name clickable to open/close collapsible menus if option is enabled
            $collapseName = '';
            if ($hasActiveChildren && $config->isCollapsible() && $config->expandByParentName()) {
                $onclick = ' onclick="Codnitive.expandMenu(this.parentNode);return false;"';
                $collapseName = ' collapse-name';
            }
            $aClass = 'class="collapsible-wrapper' . $collapseName . '"';
            $aStyle = ' style="margin-left: ' . $collWrapper . 'px;"';
        }
        
        $html[] = '<a ' . $aClass . $onclick . 'href="' . $this->getCategoryUrl($category) . '"'
                . $linkClass .'>' . '<span class="category_name">'
                . $this->escapeHtml($category->getName()) . '</span></a>';

        // add thumbnail image
        $html[] = $image;
        
        // add product count
        if ($config->showProductCount()) {
            $count = Mage::getModel('catalog/layer')
                    ->setCurrentCategory($category->getID())
                    ->getProductCollection()
                    ->getSize();
            if (($config->removeZeroCount() && $count > 0) || !$config->removeZeroCount()) {
                $html[] = '<span class="product-count">(' . $count . ')</span>';
            }
        }
        
        // render children
        $htmlChildren = '';
        $j = 0;
        foreach ($activeChildren as $child) {
            $htmlChildren .= $this->_renderCategoryMenuItemHtml(
                    $child, ($level + 1), ($j == $activeChildrenCount - 1), ($j == 0), false, $outermostItemClass, $childrenWrapClass, $noEventAttributes
            );
            $j++;
        }
        if (!empty($htmlChildren)) {
            if ($childrenWrapClass) {
                $html[] = '<div class="' . $childrenWrapClass . '">';
            }
            $html[] = '<ul class="level' . $level . $ulThumb . $collapsibleClass .
                    '" style="margin-left: ' . $ulMarginLeft .
                    'px; padding-left: ' . $ulPaddingLeft . 'px;"' . $expanded . '>';
            $html[] = $htmlChildren;
            $html[] = '</ul>';
            if ($childrenWrapClass) {
                $html[] = '</div>';
            }
        }

        $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;
    }

    /**
     * Render categories menu in HTML
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function renderCategoriesMenuHtml($level = 0, $outermostItemClass = '', $childrenWrapClass = '')
    {
        $currentId    = Mage::app()->getStore()->getRootCategoryId();
        $currentLevel = $this->_getCategoryModel()->load($currentId)->getLevel();
        if ($registerdCategory = Mage::registry('current_category')) {
            $currentId    = $registerdCategory->getId();
            $currentLevel = $registerdCategory->getLevel();
        }
        
        $config     = $this->getConfig();
        $paretnType = $config->getParent();
        $categories = $this->getStoreCategories();
        
        $activeCategories = array();
        foreach ($categories as $child) {
            // this condition use for "Current Category and Children" option
            $condition = ($paretnType == 'current') 
                    && ($child->getLevel() == $currentLevel) 
                    && ($child->getId() != $currentId);
            if ($child->getIsActive() && !$condition) {
                $activeCategories[] = $child;
            }
        }
        $activeCategoriesCount = count($activeCategories);
        $hasActiveCategoriesCount = ($activeCategoriesCount > 0);

        if (!$hasActiveCategoriesCount) {
            return '';
        }

        $html = '';
        $j = 0;
        foreach ($activeCategories as $category) {
            $html .= $this->_renderCategoryMenuItemHtml(
                    $category, $level, ($j == $activeCategoriesCount - 1), 
                    ($j == 0), true, $outermostItemClass, $childrenWrapClass, true
            );
            $j++;
        }

        return $html;
    }

    /**
     * Get category title
     *
     * @return string
     */
    public function getTitle()
    {
        $title = '';
        $currentCategory = Mage::registry('current_category');
        
        switch ($this->getConfig()->getTitleType()) {
            case 'current':
                if ($currentCategory) {
                    $title = $currentCategory->getName();
                }
                break;
                
            case 'parent':
                if ($currentCategory) {
                    $parent = $currentCategory->getParentCategory();
                    $rootId = Mage::app()->getStore()->getRootCategoryId();
                    if ($parent->getId() != $rootId) {
                        $title = $parent->getName();
                    }
                }
                break;
                
            case 'static':
                $title = $this->getConfig()->getStaticTitle();
        }
        
        if (!$title) {
            $title = $this->getConfig()->getStaticTitle();
        }
        
        return $title;
    }

    /**
     * Retrieves home page link must show
     * 
     * @return boolean
     */
    public function showHome()
    {
        if ($this->_getHelper()->isHome() && $this->getConfig()->removeHomeInHome()) {
            return false;
        }
        return $this->getConfig()->showHome();
    }
    
    /**
     * Returns all classes for home link
     * 
     * @return string
     */
    public function getHomeClasses()
    {
        $classes = 'level0 nav-0 parent home';
        
        if ($this->getConfig()->isCollapsible()) {
            $classes .= ' collapsible';
        }
        
        if ($this->_getHelper()->isHome()) {
            $classes .= ' active';
        }
        
        return $classes;
    }
    
    /**
     * Retrieves which CODNITIVE logo must show or not
     * 
     * @return boolean
     */
    public function showSupportLogo()
    {
        return $this->getConfig()->showSupportLogo();
    }
    
    /**
     * Retrieves which CODNITIVE logo must show as image or text
     * 
     * @return boolean
     */
    public function showAsImage()
    {
        return $this->getConfig()->showAsImage();
    }

    /**
     * Get extension enable status
     *
     * @deprecated after 1.7.20
     *     We don't need to check for module activation option
     *     in template, we check it in layout.
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->getConfig()->isActive();
    }

    /**
     * Get selected column
     *
     * @deprecated after 1.7.20
     *     We don't need to check for selected column option
     *     in template, we check it in layout.
     * 
     * @return string
     */
    public function getColumn()
    {
        return $this->getConfig()->getColumn();
    }

}