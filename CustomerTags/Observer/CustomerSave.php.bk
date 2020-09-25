<?php
namespace RookieSoft\CustomerTags\Observer;

class CustomerSave implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_layout;
    protected $_objectManager = null;
    protected $_customerGroup;

    /**
     * @param \Magento\Framework\View\Element\Context   $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_layout = $context->getLayout();
        $this->_request = $context->getRequest();
        $this->_objectManager = $objectManager;
    }
    /**
     * @param  \Magento\Framework\Event\Observer $observer
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $email = $customer->getEmail();
        $id = $customer->getId();
    }
}
