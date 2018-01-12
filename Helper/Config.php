<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Helper
 * @copyright   Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link        https://www.techdivision.com/
 * @author      Philipp Steinkopff <p.steinkopff@techdivision.com>
 */
class Config extends AbstractHelper
{
    /**
     * Config path to modal text value
     */
    const POPUP_TEXT = 'countrypopup/popup_values/editor';

    /**
     * Config path to modal text value
     */
    const POPUP_LOCALES = 'countrypopup/popup_values/locale';

    /**
     * Config path to modal text value
     */
    const POPUP_IMAGE = 'countrypopup/popup_values/country_modal_image';

    /**
     * return newsletter modal text
     *
     * @param string $scope
     * @return string
     */
    public function getModalText($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::POPUP_TEXT, $scope);
    }

    /**
     * return popup image url
     *
     * @param string $scope
     * @return string image upload path
     */
    public function getPopUpImage($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::POPUP_IMAGE, $scope);
    }

    /**
     * return newsletter modal text
     *
     * @param string $scope
     * @return string
     */
    public function getLocales($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::POPUP_LOCALES, $scope);
    }
}