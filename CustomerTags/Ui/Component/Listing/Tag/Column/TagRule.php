<?php

namespace RookieSoft\CustomerTags\Ui\Component\Listing\Tag\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory as TagRuleCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;

class TagRule extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        TagRuleCollectionFactory $tagRuleCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        array $components = [],
        array $data = []

    ) {
        $this->tagCollectionFactory        = $tagCollectionFactory;
        $this->tagRuleCollectionFactory    = $tagRuleCollectionFactory;
        $this->entityTagCollectionFactory  = $entityTagCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }


    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {

            foreach($dataSource['data']['items'] as &$item) {

                // echo '<pre>';
                // var_dump($item['rule_id']);
                // exit();
                $entityTagCollection = $this->entityTagCollectionFactory->create()
                    ->addFieldToFilter('entity_id', $item['rule_id'])
                    ->addFieldToFilter('entity_type_id', 4)
                    ->load();
                $tagLabel = [];
                foreach($entityTagCollection as $tag) {
                    $tagCollectionFactory = $this->tagCollectionFactory->create()->addFieldToFilter('code',$tag->getTagCode())
                        ->load();

                    foreach($tagCollectionFactory as $tag){
                        $tagLabel[] = $tag->getLabel();
                    }
                }
                $item['tags'] = implode(', ', $tagLabel);
            }
        }
        // echo '<pre>';
        // var_dump($item['tags']);
        // exit();
        return $dataSource;
    }

}
