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

class Codnitive_Sidenav_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * Returns extension's configuration settings
     * 
     * @return Codnitive_Sidenav_Model_Config
     */
    protected function getConfig()
    {
        return Mage::getModel('sidenav/config');
    }
    
    /**
     * Retrieves which we are in quick search result page or not
     * 
     * @return boolean
     */
    public function isSearchResultsPage()
    {
        return Mage::app()->getFrontController()->getAction() instanceof Mage_CatalogSearch_ResultController;
    }
    
    /**
     * Retrieves current page is homepage or not
     * 
     * @return boolean
     */
    public function isHome()
    {
        return Mage::getSingleton('cms/page')->getIdentifier() == 'home' 
                && Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms';
    }
    
}
