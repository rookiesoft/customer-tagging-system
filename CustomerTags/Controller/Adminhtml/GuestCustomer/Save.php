<?php

namespace RookieSoft\CustomerTags\Controller\Adminhtml\GuestCustomer;

use Magento\Framework\Model\ResourceModel\Db\Context;
use RookieSoft\CustomerTags\Model\GuestCustomerFactory as ModelGuestCustomerFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \RookieSoft\CustomerTags\Model\GuestCustomer
     */
    var $guestCustomerFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \RookieSoft\CustomerTags\Model\GuestCustomerFactory $guestCustomerFactory
     */
    public function __construct(
        ModelGuestCustomerFactory $modelGuestCustomerFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
    	$this->modelGuestCustomerFactory = $modelGuestCustomerFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('customertags/guestcustomer/addrow');
            return;
        }
        try {
			$rowData = $this->modelGuestCustomerFactory->create();
            $rowData->setData($data);

           	if(isset($data['entity_id'])) {
           		$rowData->setId($data['entity_id']);
           	}
           	$rowData->save();
    		$this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('customertags/guestcustomer/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RookieSoft_CustomerTags::save');
    }
}
