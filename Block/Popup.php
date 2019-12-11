<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Block;

use Magento\Framework\Url;
use Magento\Framework\UrlInterface;
use Magenerds\CountryPopUp\Helper\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Header\Logo as Header;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Block
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Philipp Steinkopff <p.steinkopff@techdivision.com>
 */
class Popup extends Template
{
    /**
     * Constant country_modal_image dir name
     */
    const SUBMEDIA_FOLDER = 'country_modal_image';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Http
     */
    protected $http;

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var Header
     */
    protected $header;

    /**
     * @param Config $config
     * @param Context $context
     * @param Http $http
     * @param Url $urlHelper
     * @param Header $header
     * @param array $data
     */
    public function __construct(
        Config $config,
        Context $context,
        Http $http,
        Url $urlHelper,
        Header $header,
        array $data = []
    )
    {
        $this->config = $config;
        $this->http = $http;
        parent::__construct($context, $data);
        $this->urlHelper = $urlHelper;
        $this->header = $header;
    }

    /**
     * loads modal text
     *
     * @param $locale string
     * @return string
     */
    public function getModalText($locale)
    {
        $locale = ($locale) ? $locale : Config::FALLBACK;
        return $this->config->getModalText($locale);
    }

    /**
     * loads modal text
     *
     * @return string
     */
    public function getCookieDuration()
    {
        return $this->config->getCookieDuration();
    }

    /**
     * return show for unselected state
     *
     * @return integer
     */
    public function getShowForUnselected()
    {
        return $this->config->getShowForUnselected();
    }

    /**
     * loads configured locales
     *
     * @return []
     */
    public function getHintedLocales()
    {
        return $this->config->getCountries();
    }

    /**
     * provides current store url
     *
     * @param string $param
     * @return string
     */
    public function getModalContentUrl($param)
    {
        return $this->urlHelper->getUrl($param);
    }

    /**
     * loads accepted languages and compare these languages with all
     * hinted languages and break
     *
     * @return []
     */
    public function hintedCountry()
    {
        $hintedLangs = $this->getHintedLocales();
        $formattedUserLangs = $this->parseUserLanguages($this->http->getHeader('Accept-Language'));
        $processedArray = array_intersect($hintedLangs, $formattedUserLangs);
        $lang = array_shift($processedArray);
        $hit = $lang !== null;

        return [
            'hinted'            => $hit,
            'locale'            => $lang,
            'userLocales'       => implode(',', $formattedUserLangs),
            'languageOk'        => $this->checkLanguage($formattedUserLangs),
            'modalImage'        => $this->getModalImage(),
            'storeUrl'          => $this->getBaseUrl(),
            'useDelay'          => $this->config->useDelay(),
            'delayDuration'     => (int)$this->config->getDelayDuration(),
            'cookieLifetime'    => (int)$this->config->getCookieDuration(),
            'modalContent'      => $this->getModalText($lang, $this->getShowForUnselected()),
            'showModalOverlay'  => $this->config->getShowModal(),
            'responsiveModal'   => $this->config->isResponsiveModal()
        ];
    }

    /**
     * check if config or url compare allows modal to be shown
     *
     * @return bool
     */
    public function showModal()
    {
        // return true if the modal is allowed on all pages
        if (!$this->config->homepageOnly()) {
            return true;
        }

        return $this->header->isHomePage();
    }

    /**
     * loads configured modal image
     *
     * @return mixed
     */
    public function getModalImage()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if (!empty($this->config->getPopUpImage())) {
            return $mediaUrl . self::SUBMEDIA_FOLDER . '/' . $this->config->getPopUpImage();
        }

        return false;
    }

    /**
     * Format the accepted user languages into a comparable array
     *
     * @param $acceptedLangs string
     * @return array
     */
    protected function parseUserLanguages($acceptedLangs)
    {
        $acceptedLangs = str_replace(' ', '', $acceptedLangs);
        $acceptedUserLang = [];
        foreach (explode(',', $acceptedLangs) as $lang) {
            $lang = strtoupper($lang);
            $ident = ';Q=';
            $exp = '-';
            if (strpos($lang, $ident) !== false) {
                $str = strstr($lang, $ident, true);
            } else {
                $str = $lang;
            }

            if (strpos($str, $exp)) {
                $acceptedUserLang[] = trim(strstr($str , $exp), $exp);
            } else {
                $acceptedUserLang[] = $str;
            }
        }

        return $acceptedUserLang;
    }

    /**
     * Check if the locale is the default store locale
     *
     * @param array $formattedUserLangs
     * @return boolean
     */
    public function checkLanguage(array $formattedUserLangs)
    {
        return array_shift($formattedUserLangs) === $this->config->getStoreCountry();
    }
}
