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
     * Config path to default country
     */
    const STORE_DEFAULT_COUNTRY = 'general/country/default';

    /**
     * Config path to modal text value
     */
    const POPUP_TEXT = 'countrypopup/popup_values/country_';

    /**
     * Config path to modal text value
     */
    const POPUP_FALLBACK_TEXT = 'countrypopup/popup_values/editor';

    /**
     * Config path to modal text value
     */
    const POPUP_COUNTRIES = 'countrypopup/popup_values/countries';

    /**
     * Config path to modal text value
     */
    const POPUP_IMAGE = 'countrypopup/popup_values/country_modal_image';

    /**
     * Config path to modal text value
     */
    const COOKIE_DURATION = 'countrypopup/general/cookie';

    /**
     * Config path to show for unselected Countries
     */
    const SHOW_FOR_UNSELECTED = 'countrypopup/general/unselected_countries_text';

    /**
     * Config field prefix
     */
    const COUNTRY_GROUP_PREFIX = 'countrypopup/popup_values';

    /**
     * Config element prefix
     */
    const COUNTRY_PREFIX = 'country_';

    /**
     * config path of the editor source model
     */
    const EDITOR_CONFIG_PATH = 'Magenerds\CountryPopUp\Model\Config\Editor';

    /**
     * config path for show only on homepage
     */
    const SHOW_ON_HOMEPAGE_ONLY = 'countrypopup/general/homepage_only';

    /**
     * config path for use delay
     */
    const USE_POPUP_DELAY = 'countrypopup/popup_delay/use_delay';

    /**
     * config path for popup delay duration
     */
    const POPUP_DELAY_DURATION = 'countrypopup/popup_delay/delay_duration';

    /**
     * config path for show modal
     */
    const SHOW_MODAL = 'countrypopup/general/show_modal';


    /**
     * fallback detect string
     */
    const FALLBACK = 'fallback';

    /**
     * return modal text
     *
     * @param string $locale
     * @param string $scope
     * @return string
     */
    public function getModalText($locale, $scope = ScopeInterface::SCOPE_STORE)
    {
        $configPath = ($locale === self::FALLBACK) ? self::POPUP_FALLBACK_TEXT : self::POPUP_TEXT . $locale;
        return $this->scopeConfig->getValue($configPath, $scope);
    }

    /**
     * return cookie duration
     *
     * @param string $scope
     * @return string
     */
    public function getCookieDuration($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::COOKIE_DURATION, $scope);
    }

    /**
     * return show for unselected state
     *
     * @param string $scope
     * @return integer
     */
    public function getShowForUnselected($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::SHOW_FOR_UNSELECTED, $scope);
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
     * return default country code
     *
     * @param string $scope
     * @return string image upload path
     */
    public function getStoreCountry($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::STORE_DEFAULT_COUNTRY, $scope);
    }

    /**
     * returns selected countries
     *
     * @param string $scope
     * @return []
     */
    public function getCountries($scope = ScopeInterface::SCOPE_STORE)
    {
        $values = $this->scopeConfig->getValue(self::POPUP_COUNTRIES, $scope);

        if ($values) {
            return explode(',', $values);
        }

        return [];
    }

    /**
     * return if modal should be only used on homeopage
     *
     * @param string $scope
     * @return bool
     */
    public function homepageOnly($scope = ScopeInterface::SCOPE_STORE)
    {
        return boolval($this->scopeConfig->isSetFlag(self::SHOW_ON_HOMEPAGE_ONLY, $scope));
    }

    /**
     * return if modal should use delay
     *
     * @param string $scope
     * @return bool
     */
    public function useDelay($scope = ScopeInterface::SCOPE_STORE)
    {
        return boolval($this->scopeConfig->isSetFlag(self::USE_POPUP_DELAY, $scope));
    }

    /**
     * return duration of display delay
     *
     * @param string $scope
     * @return string duration
     */
    public function getDelayDuration($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::POPUP_DELAY_DURATION, $scope);
    }

    /**
     * return true if show as modal dialog
     *
     * @param string $scope
     * @return bool
     */
    public function getShowModal($scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->isSetFlag(self::SHOW_MODAL, $scope);
    }
}