<?php

namespace RookieSoft\CustomerTags\Ui\Component\Listing\Tag\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerFactory;

class CustomerTags extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        CustomerFactory $customerFactory,
        array $components = [],
        array $data = []

    ) {
        $this->tagCollectionFactory            = $tagCollectionFactory;
        $this->entityTagCollectionFactory      = $entityTagCollectionFactory;
        $this->_customerFactory = $customerFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {

            foreach($dataSource['data']['items'] as &$item) {

                $customerCollection  = $this->_customerFactory->create()
                    ->addFieldToFilter('email', $item['email'])
                    ->load();

                $tagCode = [];
                foreach($customerCollection as $tag) {

                    $entityTagCollection = $this->entityTagCollectionFactory->create()
                        ->addFieldToFilter('entity_id', $tag->getId())
                        ->addFieldToFilter('entity_type_id', 2)
                        ->load();

                    foreach($entityTagCollection as $tag) {
                        $tagCollectionFactory = $this->tagCollectionFactory->create()->addFieldToFilter('code',$tag->getTagCode())
                            ->load();

                        foreach($tagCollectionFactory as $tag){
                            $tagCode[] = $tag->getLabel();
                        }
                    }
                }

                $item['label'] = implode(', ', $tagCode);
            }
        }
        return $dataSource;
    }

}
