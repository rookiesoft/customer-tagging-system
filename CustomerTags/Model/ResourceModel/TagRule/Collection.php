<?php
namespace RookieSoft\CustomerTags\Model\ResourceModel\TagRule;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'RookieSoft\CustomerTags\Model\TagRule',
            'RookieSoft\CustomerTags\Model\ResourceModel\TagRule'
        );

    }
}
