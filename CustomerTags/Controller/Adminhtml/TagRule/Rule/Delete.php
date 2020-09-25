<?php

namespace RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule;

class Delete extends \RookieSoft\CustomerTags\Controller\Adminhtml\TagRule\Rule
{
    /**
     * Delete rule action
     *
     * @return void
     */
    public function execute()
    {
        // echo '<pre>';
        // print_r($this->getRequest()->getParam('selected'));
        // exit();
        $ids = $this->getRequest()->getParam('selected');
        if ($ids) {
            try {
                /** @var \Vendor\Rules\Model\Rule $model */
                foreach($ids as $id){
                    $model = $this->ruleFactory->create();
                    $model->load($id);
                    $model->delete();
                }
                $this->messageManager->addSuccessMessage(__('You deleted the rule.'));
                $this->_redirect('customertags/tagrule_rule/index');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete the rule right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
                $this->_redirect('customertags/tagrule_rule/index');
                return;
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete.'));
        $this->_redirect('customertags/tagrule_rule/index');
    }
}