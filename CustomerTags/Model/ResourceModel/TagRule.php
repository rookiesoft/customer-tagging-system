<?php
namespace RookieSoft\CustomerTags\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use RookieSoft\CustomerTags\Model\EntityTagFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;


class TagRule extends AbstractDb
{
    /**
     * Consructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagFactory $entityTagFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        TagRuleFactory $tagRuleFactory

    ) {
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->entityTagFactory = $entityTagFactory;
        $this->tagRuleFactory = $tagRuleFactory;
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Define main table
     */
    protected function _construct()
    {

        $this->_init('rookiesoft_customertags_tag_rules', 'rule_id');
    }
    protected function saveTags($object)
    {
        if($object->getTagId() != null) {
            foreach($object->getTagId() as $tagId) {
                $select = $this->tagCollectionFactory
                    ->create()
                    ->addFieldToFilter(
                        'id', $tagId
                    );
                foreach($select->getItems() as $item){
                    $this->entityTagFactory
                        ->create()
                        ->setData([
                            'entity_id' => $object->getRuleId(),
                            'entity_type_id' => 4,
                            'tag_code' => $item->getTagCode()
                        ])
                        ->save();
                        // echo '<pre>';
                        // var_dump('CCCCCCCCCC');
                        // exit();
                }
            }
        }
    }

    protected function removeEntityId($object)
    {

        if($object->getTagId() != null) {
        //     echo '<pre>';
        // var_dump("cccccccc");
        // exit();
            $ruleId = $this->entityTagCollectionFactory
                ->create()
                ->addFieldToFilter(
                    'entity_id', $object->getRuleId()
                )->addFieldToFilter(
                    'entity_type_id','4'
                );
            foreach ($ruleId as $deleteExistingEntityId) {
                $deleteExistingEntityId->delete();
            }
        }
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // $this->modelGuestCustomerFactroy
        //     ->create()
        //     ->load(
        //         $this->getEntityId($object)
        //     )->delete();
        $this->removeEntityId($object);
        $this->saveTags($object);
        // echo '<pre>';
        // var_dump($object->getTagId());
        // exit();
        return parent::_beforeSave($object);
    }
}
