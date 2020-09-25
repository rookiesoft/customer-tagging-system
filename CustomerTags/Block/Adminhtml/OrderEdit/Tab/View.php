<?php
namespace RookieSoft\CustomerTags\Block\Adminhtml\OrderEdit\Tab;

use Magento\Customer\Controller\RegistryConstants;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\TagFactory as TagFactory;

/**
 * Order custom tab
 *
 */
class View extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * View constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        TagFactory $tagFactory,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList,
        array $data = []
    ) {
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
        $this->tagCollectionFactory =$tagCollectionFactory;
        $this->request = $request;
        $this->tagFactory = $tagFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
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
        return __('Order Tags');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Order Tags');
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

    public function tagsOptions()
    {
        $select = $this->tagCollectionFactory->create();
        return $select;
    }

    public function getSelectedTags()
    {
        $tagCode = [];
        $select  = $this->entityTagCollectionFactory->create()
                    ->addFieldToFilter('entity_id', $this->getOrderId())
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

    public function getSubmitUrl()
    {
        return $this->getUrl('customertags/sales/save', ['order_id' => $this->getOrder()->getId()]);
    }
}