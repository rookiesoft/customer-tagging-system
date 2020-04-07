<?php
namespace RookieSoft\CustomerTags\Ui\Component\Listing\Tag\Column;

use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;

class FilterOptions implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        TagCollectionFactory $tagCollectionFactory

    ) {
        $this->tagCollectionFactory = $tagCollectionFactory;
    }

    public function toOptionArray()
    {
        $options = [];
        $tags = $this->tagCollectionFactory->create();
        foreach($tags as $tag){
            $options[] = ['label' => $tag->getLabel(), 'value' => $tag->getLabel()];
        }
        return $options;
    }
}