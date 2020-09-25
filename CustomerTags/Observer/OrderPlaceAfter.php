<?php
namespace RookieSoft\CustomerTags\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Checkout\Model\Session;
use RookieSoft\CustomerTags\Model\EntityTagFactory as EntityTagFactory;

class OrderPlaceAfter implements ObserverInterface {

    protected $connector;

    /**
     * __construct
     * @param \RookieSoft\CustomerTags\Model\TagRuleFactory                            $ruleFactory
     * @param \RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory   $ruleCollection
     * @param \RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory $entityTagCollectionFactory
     * @param Session                                                                  $checkoutSession
     * @param EntityTagFactory                                                         $entityTagFactory
     */
    public function __construct(
        \RookieSoft\CustomerTags\Model\TagRuleFactory $ruleFactory,
        \RookieSoft\CustomerTags\Model\ResourceModel\TagRule\CollectionFactory $ruleCollection,
        \RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory  $entityTagCollectionFactory,
        Session $checkoutSession,
        EntityTagFactory $entityTagFactory

    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_ruleFactory = $ruleFactory;
        $this->_ruleCollection = $ruleCollection;
        $this->checkoutSession = $checkoutSession;
        $this->entityTagFactory = $entityTagFactory;
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
    }

    /**
     * execute
     * @param  \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $shippingAddress = $this->getQuoteData();
        $rules = $this->_ruleCollection->create();
        foreach($rules as $rule){
            if($rule->getEventName() == 'sales_order_save_after' && $rule->getState() == 1){
                if($rule && $rule->validate($shippingAddress)) {
                    $tagCodes = $this->entityTagCollectionFactory->create()->addFieldToFilter('entity_id', $rule->getRuleId());
                    foreach($tagCodes as $tagCode) {
                        $rowData = $this->entityTagFactory->create();
                        $rowData->setData([
                            'entity_id' => $order->getEntityId(),
                            'entity_type_id' => '3',
                            'tag_code' => $tagCode->getTagCode()
                        ])->save();
                    }
                    return;
                }
            }
        }
    }

    /**
     * getQuoteData
     * @return array
     */
    private function getQuoteData()
    {
        $quote = $this->checkoutSession->getQuote();
        if (!$quote) {
            return null;
        }
        return $quote->getIsVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

}
