<?php

namespace RookieSoft\CustomerTags\Observer;

use Magento\Framework\Event\ObserverInterface;

class CustomerRegisterSuccess implements ObserverInterface
{
    /**
     * __construct
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                        $customerRepositoryInterface
     * @param \RookieSoft\CustomerTags\Model\TagRuleFactory                            $ruleFactory
     * @param \RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory   $ruleCollection
     * @param \Magento\Customer\Model\ResourceModel\Customer                           $resource
     * @param \Magento\Customer\Model\CustomerFactory                                  $customer
     * @param \Amasty\Conditions\Model\Rule\Condition\CustomerAttributes               $customerAttributes
     * @param \RookieSoft\CustomerTags\Model\EntityTagFactory                          $entityTagFactory
     * @param \RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory $entityTagCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \RookieSoft\CustomerTags\Model\TagRuleFactory $ruleFactory,
        \RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory $ruleCollection,
        \Magento\Customer\Model\ResourceModel\Customer $resource,
        \Magento\Customer\Model\CustomerFactory $customer,
        \Amasty\Conditions\Model\Rule\Condition\CustomerAttributes $customerAttributes,
        \RookieSoft\CustomerTags\Model\EntityTagFactory $entityTagFactory,
        \RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory  $entityTagCollectionFactory
    ) {
        $this->resource = $resource;
        $this->_ruleFactory = $ruleFactory;
        $this->_ruleCollection = $ruleCollection;
        $this->entityTagFactory = $entityTagFactory;
        $this->customer = $customer;
        $this->customerAttributes = $customerAttributes;
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
    }
    /**
     * execute
     * @param  \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $currentCustomer = $observer->getEvent()->getCustomer();
        $customer = $this->customer->create()->load($currentCustomer->getId());
        $rules = $this->_ruleCollection->create();
        foreach($rules as $rule) {

            if($rule->getEventName() == 'customer_register_success') {

                if($rule && $rule->validate($customer)) {
                    $rowData = $this->entityTagFactory->create();
                    $tagCodes = $this->entityTagCollectionFactory->create()
                        ->addFieldToFilter('entity_id', $rule->getRuleId());
                    foreach($tagCodes as $tagCode){
                        $rowData->setData([
                            'entity_id' => $customer->getEntityId(),
                            'entity_type_id' => '2',
                            'tag_code' => $tagCode->getTagCode()
                        ])->save();
                    }
                    return;
                }else{
                    return;
                }
            }
        }
    }
}
