<?php
namespace RookieSoft\CustomerTags\Block\Adminhtml\Order\View\Tab;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\OrderFactory as SalesOrderFactory;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as SalesOrderCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\TagFactory as TagFactory;
use RookieSoft\CustomerTags\Model\GuestCustomerFactory as GuestCustomerFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;

class OrderTags extends Template
{
    /**
     * construct
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Request\Http              $request
     * @param TagCollectionFactory                             $tagCollectionFactory
     * @param SalesOrderFactory                                $salesFactory
     * @param GuestCustomerCollectionFactory                   $guestCustomerCollectionFactory
     */
    protected $_template = 'order/view/tab/order-tags.phtml';
    // protected $_uicomponent = 'ui_component/rookiesoft_customertags_guestcustomer_form.xml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        //\Magento\Framework\App\Request\Http $request,
        //\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        SalesOrderFactory $salesFactory,
        TagFactory $tagFactory,
        GuestCustomerFactory $guestCustomerFactory,
        PriceCurrencyInterface $priceFormatter,
        SalesOrderCollectionFactory $salesOrderCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
        $this->salesFactory = $salesFactory;
        $this->tagCollectionFactory =$tagCollectionFactory;
        $this->salesOrderCollectionFactory = $salesOrderCollectionFactory;
        $this->request = $request;
        $this->guestCustomerFactory = $guestCustomerFactory;
        $this->tagFactory = $tagFactory;
        $this->priceFormatter = $priceFormatter;
        $this->_coreRegistry = $registry;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * @return string|null
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }
    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrderId()
    {
        return $this->getOrder()->getEntityId();
    }

    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getOrder()->getIncrementId();
    }
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('My Custom Tab');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('My Custom Tab');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * getTags
     * @return [string] get all tags and implode them into a string
     */
    public function getTags()
    {
        $tagCode = [];
        $select  = $this->entityTagCollectionFactory->create()
                    ->addFieldToFilter('entity_id', $this->getCustomerId())
                    ->addFieldToFilter('entity_type_id', 3)
                    ->load();
        foreach($select as $tag) {

            $tagCollection = $this->tagCollectionFactory->create()
                ->addFieldToFilter('code', $tag->getTagCode())
                ->load();
            foreach($tagCollection as $tag) {
                $tagCode[] = $tag->getLabel();
            }
        }

        $tags = implode(', ', $tagCode);

        return $tags;
    }

    public function getSelectedTags()
    {
        $tagCode = [];
        $select  = $this->entityTagCollectionFactory->create()
                    ->addFieldToFilter('entity_id', $this->getCustomerId())
                    ->addFieldToFilter('entity_type_id', 3)
                    ->load();
        foreach($select as $tag) {

            $tagCollection = $this->tagCollectionFactory->create()
                ->addFieldToFilter('code', $tag->getTagCode())
                ->load();
            foreach($tagCollection as $tag) {
                $tagCode[] = $tag;
            }
        }

        return $tagCode;
    }

    /**
     * getBaseGrandTotal
     * @return [double] get sum of prices
     */
    public function getBaseGrandTotal()
    {
        $prices = $this->salesOrderCollectionFactory->create()
                        ->addFieldToFilter(
                            'customer_email',
                            ['eq' => $this->getEmailAdress()]);
            $sum = [];

        foreach($prices as $price){
            $sum[] = $price->getBaseGrandTotal();
        }
        return array_sum($sum);
    }

    /**
     * getPrice
     * @return [string] formates the price and adds euro sign
     */
    public function getPrice()
    {
        $currencyCode = isset($item['order_currency_code']) ? $item['order_currency_code'] : null;
        $e = $this->priceFormatter->format(
                $this->getBaseGrandTotal(),
                false,
                null,
                null,
                $currencyCode
            );
        return $e;
    }

    /**
     * sumOrders
     * @return [integer] counts all orders
     */
    public function sumOrders()
    {
        $orders = $this->salesOrderCollectionFactory->create()
                        ->addFieldToFilter(
                            'customer_email',
                            ['eq' => $this->getEmailAdress()]);
        $count = [];

        foreach($orders as $order){
            $count[] = $order->getIncrementId();
        }
        return count($count);
    }
    public function tagsOptions()
    {
        $select = $this->tagCollectionFactory->create();
        return $select;
    }
    public function getFormKey()
    {
         return $this->formKey->getFormKey();
    }
}