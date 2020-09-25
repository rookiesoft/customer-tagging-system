<?php
namespace RookieSoft\CustomerTags\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;
use RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory as TagRuleCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;

class TagRuleForm extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\App\Request\Http $request,
        TagCollectionFactory $tagCollectionFactory,
        TagRuleCollectionFactory $tagRuleCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tagCollectionFactory->create();
        $this->tagCollection = $tagCollectionFactory;
        $this->tagRuleCollection = $tagRuleCollectionFactory->create();
        $this->entityTagCollection = $entityTagCollectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $ruleId = $this->request->getParam('rule_id');
        $items = $this->tagRuleCollection->addFieldToFilter('rule_id', $ruleId);

        foreach ($items as $item) {
            $item->setTagId($this->getTagByRule($ruleId));
            $this->_loadedData[$item->getId()] = $item->getData();
            // echo '<pre>';
            // var_dump($item->getData());
            // exit();
        }

        return $this->_loadedData;
    }

    public function getTagByRule($ruleId){
        $tagId = [];
        $tagCodes = $this->entityTagCollection->addFieldToFilter('entity_id', $ruleId);
        foreach($tagCodes as $tagCode) {
            $tagIds = $this->tagCollection->create()
                        ->addFieldToFilter(
                            'code',$tagCode->getTagCode()
                        );
                foreach($tagIds as $id) {
                    $tagId[] = $id->getId();
                }
        }
        return $tagId;
    }
}