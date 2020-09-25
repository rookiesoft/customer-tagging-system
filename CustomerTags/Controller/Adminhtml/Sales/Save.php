<?php
namespace RookieSoft\CustomerTags\Controller\Adminhtml\Sales;

use Magento\Framework\Model\ResourceModel\Db\Context;
use RookieSoft\CustomerTags\Model\EntityTagFactory as EntityTagFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;


class Save extends \Magento\Backend\App\Action
{
    var $tagFactory;
    protected $resultPageFactory;

    protected $resultJsonFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        EntityTagFactory $entityTagFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->entityTagFactory = $entityTagFactory;
        $this->_entityTagCollectionFactory = $entityTagCollectionFactory;
    }

    public function execute()
    {
        // $result = $this->resultJsonFactory->create();
        // $result->setData(['test' => 'xxx']);
        // return $result;
        //return $this->resultPageFactory->create();
        // $data = $this->getRequest()->getGetValue();

        // // // if (!$data) {
        // // //     $this->_redirect('customer/*/*');
        // // //     return;
        // // // }
            $data = $this->getRequest()->getparams();
            $select = $this->_entityTagCollectionFactory
            ->create()
            ->addFieldToFilter(
                'entity_id', $data['orderId']
            )->addFieldToFilter(
                'entity_type_id','3'
            );
            foreach ($select as $deleteExistingEntityId) {
                $deleteExistingEntityId->delete();
            }
            $rowData = $this->entityTagFactory->create();
            foreach($data['tagCode'] as $tag){
                $rowData->setData([
                            'entity_id' => $data['orderId'],
                            'entity_type_id' => '3',
                            'tag_code' => $tag
                        ])
                        ->save();
            }
            //$this->_redirect('sales/order/index');
            return $this->resultPageFactory->create();
        //     $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        // error_log(print_r($this->getRequest()->getParam('orderId'),true));

        // echo '<pre>';
        // var_dump($data['orderId']);
        // exit();
    }
}