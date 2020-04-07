<?php

namespace RookieSoft\CustomerTags\Ui\Component\Listing\Tag\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\GuestCustomer\CollectionFactory as GuestCustomerCollectionFactory;

class Tags extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        GuestCustomerCollectionFactory $guestCustomerCollectionFactory,
        array $components = [],
        array $data = []

    ) {
        $this->tagCollectionFactory            = $tagCollectionFactory;
        $this->entityTagCollectionFactory      = $entityTagCollectionFactory;
        $this->_guestCustomerCollectionFactory = $guestCustomerCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {

            foreach($dataSource['data']['items'] as &$item) {

                $guestCustomerCollection  = $this->_guestCustomerCollectionFactory->create()
                    ->addFieldToFilter('email', $item['customer_email'])
                    ->load();

                $tagCode = [];
                foreach($guestCustomerCollection as $tag) {

                    $entityTagCollection = $this->entityTagCollectionFactory->create()
                        ->addFieldToFilter('entity_id', $tag->getId())
                        ->load();

                    foreach($entityTagCollection as $tag) {
                        $tagCollectionFactory = $this->tagCollectionFactory->create()->addFieldToFilter('code',$tag->getTagCode())
                            ->load();

                        foreach($tagCollectionFactory as $tag){
                            $tagCode[] = $tag->getLabel();
                        }
                    }
                }

                $item['tags'] = implode(', ', $tagCode);
            }
        }
        return $dataSource;
    }

}
