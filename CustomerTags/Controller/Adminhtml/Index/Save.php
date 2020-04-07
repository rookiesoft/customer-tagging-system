<?php
namespace RookieSoft\CustomerTags\Controller\Adminhtml\Index;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use Magento\Sales\Model\OrderFactory as SalesOrderFactory;
use RookieSoft\CustomerTags\Model\GuestCustomerFactory as ModelGuestCustomerFactory;

class Save extends \Magento\Customer\Controller\Adminhtml\Index\Save
{
    var $tagFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \RookieSoft\CustomerTags\Model\TagFactory $tagFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \RookieSoft\CustomerTags\Model\TagFactory $tagFactory
    ) {
        parent::__construct($context);
        $this->tagFactory = $tagFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        // var_dump($data);
            // echo "<pre>";
            // echo var_dump($data);
            // exit();

        // if (!$data) {
        //     $this->_redirect('customertags/guestcustomer/addrow');
        //     return;
        // }
        // try {
        //     // $rowData = $this->modelGuestCustomerFactroy->create();
        //     $rowData = $this->modelGuestCustomerFactory->create();
        //     $rowData->setData($data);
        //     //$rowData->setData($data);
        //     // $rowData->setData([
        //     //     'email' => $data['customer_email']
        //     // ]);
        //     if(isset($data['entity_id'])) {
        //         $rowData->setId($data['entity_id']);
        //     }

        //     $rowData->save();


        //     $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        // } catch (\Exception $e) {
        //     $this->messageManager->addError(__($e->getMessage()));
        // }
        // $this->_redirect('customertags/guestcustomer/index');
        error_log(print_r('MMMMMMMMMMMMMMMMMMMM',true));
    }
}