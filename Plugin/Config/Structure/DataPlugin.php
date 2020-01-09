<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
namespace Magenerds\CountryPopUp\Plugin\Config\Structure;

use Magenerds\CountryPopUp\Helper\Config as ConfigHelper;
use Magento\Config\Model\Config\Structure\Data;
use Magento\Directory\Model\Country;

/**
 * @copyright  Copyright (c) 2020 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     MET <met@techdivision.com >
 */
class DataPlugin
{
    /** @var ConfigHelper */
    private $configHelper;

    /** @var Country */
    private $country;

    /**
     * @param ConfigHelper $configHelper
     * @param Country $country
     */
    public function __construct(ConfigHelper $configHelper, Country $country)
    {
        $this->configHelper = $configHelper;
        $this->country = $country;
    }

    /**
     * @param Data $subject
     * @param array $config
     * @return array
     * @see Data::merge()
     */
    public function aroundMerge(Data $subject, callable $proceed, array $config)
    {
        $dynamicFields = $this->getDynamicConfigFields();
        $systemConfig = $config['config']['system']['sections']['countrypopup']['children']['popup_values']['children'];

        if(!empty($dynamicFields)) {
            $config['config']['system']['sections']['countrypopup']['children']
            ['popup_values']['children'] = array_merge($systemConfig, $dynamicFields);
        }

        if (isset($config['config']['system'])) {
            $config = $config['config']['system'];
        }

        return $proceed($config);
    }

    /**
     * @return array
     */
    protected function getDynamicConfigFields()
    {
        $selectedCountries = $this->configHelper->getCountries();
        $dynamicConfigFields = [];
        foreach($selectedCountries as $index => $countryCode) {
            $configId = ConfigHelper::COUNTRY_PREFIX . $countryCode;
            $country = $this->country->loadByCode($countryCode);

            $dynamicConfigFields[$configId] = [
                'id' => $configId,
                'type' => 'editor',
                'sortOrder' => 20 + ($index * 10),
                'showInDefault' => '1',
                'showInWebsite' => '0',
                'showInStore' => '0',
                'label' => __('Modaltext for Country: %1', $country->getName())->render(),
                'comment' => __(
                    'The Value of this Field will be shown only for users with the browser Country "%1".',
                    $country->getName()
                )->render(),
                '_elementType' => 'field',
                'frontend_model' => ConfigHelper::EDITOR_CONFIG_PATH,
                'path' => ConfigHelper::COUNTRY_GROUP_PREFIX
            ];
        }

        return $dynamicConfigFields;
    }
}
