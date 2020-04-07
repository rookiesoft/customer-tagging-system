<?php
namespace RookieSoft\CustomerTags\Model\Plugin\ResourceModel\Customer;

class Grid
{
    public static $table = 'customer_grid_flat';

    public function afterSearch($intercepter, $collection)
    {
        if ($collection->getMainTable() === $collection->getConnection()->getTableName('customer_grid_flat')) {
            $collection
                ->getSelect()
                ->joinLeft([
                    'entity_tag' => 'rookiesoft_customertags_entity_tag'],
                    'main_table.entity_id = entity_tag.entity_id',
                    ['tag_code' => 'entity_tag.tag_code','entity_type_id' => 'entity_tag.entity_type_id'])
                ->joinLeft([
                    'tag' => 'rookiesoft_customertags_tag'],
                    'entity_tag.tag_code = tag.code',
                    ['label' => 'tag.label']
                )->where('entity_type_id IS NULL OR entity_type_id = 2');
                $collection->addFilterToMap('label', 'tag.label');
                $collection->getSelect()->group('email');
        }
        return $collection;
    }

}