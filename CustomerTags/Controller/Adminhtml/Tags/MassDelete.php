<?php

namespace RookieSoft\CustomerTags\Controller\Adminhtml\Tags;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory;
use Magento\Framework\App\ResponseInterface;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\TagRuleFactory as TagRuleFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter.​_
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        TagRuleFactory $tagRuleFactory

    ) {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_entityTagCollectionFactory = $entityTagCollectionFactory;
        $this->_tagRuleFactory = $tagRuleFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $item) {
            $tagCodes = $this->_entityTagCollectionFactory->create()->addFieldToFilter('tag_Code', $item->getCode())->addFieldToFilter('entity_type_id', 4);

            if($tagCodes != NULL){
                foreach($tagCodes as $tagCode){
                    $tagRule =  $this->_tagRuleFactory->create()->load($tagCode->getEntity_id());
                    $this->messageManager->addErrorMessage(__('the tag: '.$item->getLabel().' is still used in the rule: '.$tagRule->getRuleName()));
                }
                return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
            }
            $item->delete();
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    /**
     * Check Category Map recode delete Permission.
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RookieSoft_CustomerTags::row_data_delete');
    }
}