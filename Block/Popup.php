<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Block;

use Magento\Framework\UrlInterface;
use Magenerds\CountryPopUp\Helper\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Block
 * @copyright   Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
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
    private $config;

    /**
     * @var Http
     */
    private $http;


    /**
     * @param Config $config
     * @param Context $context
     * @param Http $http
     * @param array $data
     */
    public function __construct(
        Config $config,
        Context $context,
        Http $http,
        array $data = []
    ) {
        $this->config = $config;
        $this->http = $http;
        parent::__construct($context, $data);
    }

    /**
     * loads modal text
     *
     * @return string
     */
    public function getModalText()
    {
        return $this->config->getModalText();
    }

    /**
     * loads configured locales
     *
     * @return string
     */
    public function getHintedLocales()
    {
        return $this->config->getLocales();
    }

    /**
     * loads accepted languages
     *
     * @return string
     */
    public function getUserLocales()
    {
        return $this->parseLanguages($this->http->getHeader('Accept-Language'));
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
     * format the accepted languages
     *
     * @param $acceptedLangs string
     * @return string
     */
    private function parseLanguages($acceptedLangs)
    {
        $str = '';
        $acceptedLangs = str_replace('-','_', $acceptedLangs);
        foreach (explode(',', $acceptedLangs) as $lang) {
            $ident = ';q=';
            if (strpos($lang, $ident) !== false) {
                $str .= strstr($lang, $ident, true) . ',';
            } else {
                $str .= $lang . ',';
            }
        }

        return $str;
    }
}