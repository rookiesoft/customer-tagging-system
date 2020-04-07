<?php
namespace RookieSoft\CustomerTags\Controller\Adminhtml\Customer;

use Magento\Framework\Model\ResourceModel\Db\Context;
use RookieSoft\CustomerTags\Model\EntityTagFactory as EntityTagFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;


class Save extends \Magento\Backend\App\Action
{
    var $tagFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        EntityTagFactory $entityTagFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory
    ) {
        parent::__construct($context);
        $this->entityTagFactory = $entityTagFactory;
        $this->_entityTagCollectionFactory = $entityTagCollectionFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('customer/*/*');
            return;
        }
        try {
            $select = $this->_entityTagCollectionFactory
            ->create()
            ->addFieldToFilter(
                'entity_id', $data['entity_id']
            )->addFieldToFilter(
                'entity_type_id','2'
            );
            foreach ($select as $deleteExistingEntityId) {
                $deleteExistingEntityId->delete();
            }
            $rowData = $this->entityTagFactory->create();
            foreach($data['tag'] as $tag){
                $rowData->setData([
                            'entity_id' => $data['entity_id'],
                            'entity_type_id' => '2',
                            'tag_code' => $tag
                        ])
                        ->save();
            }
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
         $this->_redirect('customer/index/index');
    }
}