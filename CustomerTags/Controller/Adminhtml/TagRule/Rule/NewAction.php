<?php

namespace RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule;

class NewAction extends \RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule
{
    /**
     * New action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}