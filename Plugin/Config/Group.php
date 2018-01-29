<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Plugin\Config;

use Magento\Config\Model\Config\Structure\Element\Group as OriginalGroup;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magenerds\CountryPopUp\Helper\Config as ConfigHelper;
use Magento\Directory\Model\Country;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Model
 * @copyright   Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Philipp Steinkopff <p.steinkopff@techdivision.com>
 */
class Group
{
    /**
     * Config XML path of target group
     */
    const DIRECTORY_REGION_REQUIRED_GROUP_ID = 'popup_values';

    /**
     * @var DirectoryHelper
     */
    protected $directoryHelper;

    /**
     * @var Country
     */
    private $country;

    /**
     * Group constructor.
     *
     * @param DirectoryHelper $directoryHelper
     * @param Country $country
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        DirectoryHelper $directoryHelper,
        Country $country,
        ConfigHelper $configHelper
    )
    {
        $this->directoryHelper = $directoryHelper;
        $this->country = $country;
        $this->configHelper = $configHelper;
    }

    /**
     * Get dynamic config fields depending on the config
     *
     * @return []
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
                'label' => __('Modaltext for Country: %1', $country->getName()),
                'comment' => __(
                    'The Value of this Field will be shown only for users with the browser Country "%1".',
                    $country->getName()
                ),
                '_elementType' => 'field',
                'frontend_model' => ConfigHelper::EDITOR_CONFIG_PATH,
                'path' => ConfigHelper::COUNTRY_GROUP_PREFIX
            ];
        }

        return $dynamicConfigFields;
    }

    /**
     * Add dynamic editor field for each country configured
     *
     * @param OriginalGroup $subject
     * @param $proceed
     * @param [] $data
     * @param $scope
     * @return mixed
     */
    public function aroundSetData(OriginalGroup $subject, $proceed, $data, $scope)
    {
        if($data['id'] == self::DIRECTORY_REGION_REQUIRED_GROUP_ID) {
            $dynamicFields = $this->getDynamicConfigFields();

            if(!empty($dynamicFields)) {
                $data['children'] += $dynamicFields;
            }
        }

        return $proceed($data, $scope);
    }
}