<?php
namespace RookieSoft\CustomerTags\Block\Adminhtml\TagRule\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _beforeToHtml()
    {
       //other tabs
        $this->addTab(
            'rule_info',
            [
                'content' => $this->getLayout()->createBlock(
                    'RookieSoft\CustomerTags\Block\Adminhtml\TagRule\Edit\Tab\Main'
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}