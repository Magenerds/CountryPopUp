<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Controller\Popup;

use Magenerds\CountryPopUp\Block\Popup;
use Magenerds\CountryPopUp\Helper\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Controller
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link        https://www.techdivision.com/
 * @author      Philipp Steinkopff <p.steinkopff@techdivision.com>
 */
class Index extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Popup
     */
    private $popup;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Popup $popup
     * @param Config $config
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Popup $popup,
        Config $config
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->config = $config;
        $this->popup = $popup;
    }

    /**
     * @return JsonFactory
     */
    public function execute() {
        /** @var JsonFactory $result */
        $result = $this->resultJsonFactory->create();
        return $result->setData($this->popup->hintedCountry());
    }
}
