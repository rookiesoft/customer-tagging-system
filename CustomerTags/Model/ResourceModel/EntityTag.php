<?php
namespace RookieSoft\CustomerTags\Model\ResourceModel;

class EntityTag extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('rookiesoft_customertags_entity_tag', 'id');   //here id is the primary key of table
    }
}
