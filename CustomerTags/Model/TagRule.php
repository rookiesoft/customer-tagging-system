<?php

namespace RookieSoft\CustomerTags\Model;
//use \Magento\Framework\Model\AbstractModel;
use \Magento\Framework\DataObject\IdentityInterface;
use \RookieSoft\CustomerTags\Api\Data\TagRuleInterface;
//use \Magento\Quote\Model\Quote\Address;
use \Magento\Rule\Model\AbstractModel;

/**
 * Class File
 * @package RookieSoft\CustomerTags\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TagRule extends AbstractModel implements TagRuleInterface, IdentityInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'rookiesoft_customertags_tag_rules';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\SalesRule\Model\Rule\Condition\CombineFactory $condCombineFactory,
        \Magento\SalesRule\Model\Rule\Condition\Product\CombineFactory $condProdCombineF,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->condCombineFactory = $condCombineFactory;
        $this->condProdCombineF = $condProdCombineF;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    /**
     * Post Initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init('RookieSoft\CustomerTags\Model\ResourceModel\TagRule');
        $this->setIdFieldName('rule_id');
    }

    /**
     * Get rule condition combine model instance
     *
     * @return \Magento\SalesRule\Model\Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->condCombineFactory->create();
    }

    /**
     * Get rule condition product combine model instance
     *
     * @return \Magento\SalesRule\Model\Rule\Condition\Product\Combine
     */
    public function getActionsInstance()
    {
        return $this->condProdCombineF->create();
    }


    /**
     * Get Id
     *
     * @return int|null
     */
    public function getRuleId()
    {
        return $this->getData(self::RULE_ID);
    }

    /**
     * Get Rule Name
     *
     * @return string|null
     */
    public function getRuleName()
    {
        return $this->getData(self::RULE_NAME);
    }

    /**
     * Get Tag Id
     *
     * @return id|null
     */
    // public function getTagId()
    // {
    //     return $this->getData(self::TAG_ID);
    // }

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getConditionsSerialized()
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    /**
     * Get State
     *
     * @return int|null
     */
    public function getState()
    {
        return $this->getData(self::STATE);
    }

    /**
     * Get Event Name
     *
     * @return string|null
     */
    public function getEventName()
    {
        return $this->getData(self::EVENT_NAME);
    }

    /**
     * Return identities
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Set Id
     *
     * @param int $id
     * @return $this
     */
    public function setRuleId($ruleId)
    {
        error_log(print_r('PPPPPPPPPPPPPPPPPPPPPPPPPPP',true));
        return $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * Set Rule Name
     *
     * @param string $ruleName
     * @return $this
     */
    public function setRuleName($ruleName)
    {
        return $this->setData(self::RULE_NAME, $ruleName);
    }

    /**
     * Set Tag Id
     *
     * @param int $tagId
     * @return $this
     */
    // public function setTagId($tagId)
    // {
    //     return $this->setData(self::TAG_ID, $tagId);
    // }

    /**
     * Set Conditions Serialized
     *
     * @param string $conditionsSerialized
     * @return $this
     */
    public function setConditionsSerialized($conditionsSerialized)
    {
        return $this->setData(self::CONDITIONS_SERIALIZED, $conditionsSerialized);
    }

    /**
     * Set State
     *
     * @param int $state
     * @return $this
     */
    public function setState($state)
    {
        return $this->setData(self::STATE, $state);
    }

    /**
     * Set Event Name
     *
     * @param string $eventName
     * @return $this
     */
    public function setEventName($eventName)
    {
        return $this->setData(self::EVENT_NAME, $eventName);
    }
}
