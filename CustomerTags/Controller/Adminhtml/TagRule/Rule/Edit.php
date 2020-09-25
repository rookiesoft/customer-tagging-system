<?php

namespace RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule;

class Edit extends \RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule
{
    /**
     * Rule edit action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('rule_id');
        /** @var \Vendor\Rules\Model\Rule $model */
        $model = $this->ruleFactory->create();
        if ($id) {
            $model->load($id,'rule_id');
            if (!$model->getRuleId()) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                $this->_redirect('tagrule/*');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $model->getConditions()->setJsFormObject('rookiesoft_customertags_tagrule_form');

        $this->coreRegistry->register('current_rule', $model);

        $this->_initAction();
        // $this->_view->getLayout()
        //     ->getBlock('tagrule_rule_edit');
            //->setData('action', $this->getUrl('tagrule/*/save'));

        $this->_addBreadcrumb($id ? __('Edit Rule') : __('New Rule'), $id ? __('Edit Rule') : __('New Rule'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getRuleId() ? $model->getRuleName() : __('New Rule')
        );
        $this->_view->renderLayout();
    }
}