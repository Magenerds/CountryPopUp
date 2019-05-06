<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace Magenerds\CountryPopUp\Model\Config;

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement as Element;

/**
 * @category    Magenerds
 * @package     Magenerds_CountryPopUp
 * @subpackage  Model
 * @copyright   Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Philipp Steinkopff <p.steinkopff@techdivision.com>
 */
class Editor extends Field
{
    /**
     * config elements that needs to be removed from default config
     */
    const UNSET_MAGENTO_WYSIWYG_BUTTONS = [
        'add_variables' => ['widget_plugin_src', 'widget_placeholders', 'widget_window_url'],
        'add_widgets' => ['plugins']
    ];

    /**
     * @var  WysiwygConfig
     */
    protected $wysiwygConfig;

    /**
     * @param Context $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $data);
    }

    /**
     * generated wysiwyg editor as possible backend field
     *
     * @param Element $element
     * @return string
     */
    protected function _getElementHtml(Element $element)
    {
        $element->setWysiwyg(true);

        $wysiwygConfig = $this->wysiwygConfig->getConfig($element);

        foreach (self::UNSET_MAGENTO_WYSIWYG_BUTTONS as $button => $removes) {
            $wysiwygConfig->setData($button, false);
            $wysiwygConfig->setData('height', '250px');
            $wysiwygConfig->unsetData($removes);
        }

        $element->setConfig($wysiwygConfig);
        return parent::_getElementHtml($element);
    }
}
