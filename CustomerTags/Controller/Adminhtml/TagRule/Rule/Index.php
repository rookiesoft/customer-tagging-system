<?php
namespace RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule;

class Index extends \RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Tag Rule'), __('Tag Rule'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Tag Rule'));
        $this->_view->renderLayout('root');
    }
}
