<?php

namespace RookieSoft\CustomerTags\Helper\UiComponent\DataProvider;

use RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;

class TagRule extends AbstractDataProvider
{
    protected $_resource;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ResourceConnection $resource
     * @param CollectionFactory $tagCollection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ResourceConnection $resource,
        CollectionFactory $tagRuleCollection,
        array $meta = [],
        array $data = []
    ) {
        $this->_resource          = $resource;
        $this->name               = $name;
        $this->primaryFieldName   = $primaryFieldName;
        $this->requestFieldName   = $requestFieldName;

        $this->collection         = $tagRuleCollection
            ->create();
        $this->collection->getSelect()
            ->joinLeft([
                'entity_tag' => 'rookiesoft_customertags_entity_tag'],
                'main_table.rule_id = entity_tag.entity_id',
                ['tag_code' => 'entity_tag.tag_code','entity_type_id' => 'entity_tag.entity_type_id']
            )
            ->joinLeft([
            'tag' => 'rookiesoft_customertags_tag'],
            'entity_tag.tag_code = tag.code',
            ['tags' => 'tag.label']
        )->where('entity_tag.entity_type_id IS NULL OR entity_type_id = 4');
        $this->collection->addFilterToMap('tags', 'tag.label');
        $this->collection->getSelect()->group('rule_id');

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    }
}