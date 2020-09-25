<?php

namespace RookieSoft\CustomerTags\Helper\UiComponent\DataProvider;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;

class GuestOrders extends AbstractDataProvider
{
    protected $_resource;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ResourceConnection $resource,
        CollectionFactory $salesOrderCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->_resource        = $resource;
        $this->name = $name;
        $this->primaryFieldName = $primaryFieldName;
        $this->requestFieldName = $requestFieldName;
        $this->collection = $salesOrderCollectionFactory->create();
        $this->collection->getSelect()
        ->joinLeft([
            'guest_customer' => 'rookiesoft_customertags_guest_customer'],
            'main_table.customer_email = guest_customer.email',
            ['guest_customer_id' => 'guest_customer.id']
        )
        ->joinLeft([
            'entity_tag' => 'rookiesoft_customertags_entity_tag'],
            'guest_customer.id = entity_tag.entity_id',
            ['tag_code' => 'entity_tag.tag_code','entity_type_id' => 'entity_tag.entity_type_id']
        )
        ->joinLeft([
            'tag' => 'rookiesoft_customertags_tag'],
            'entity_tag.tag_code = tag.code',
            ['tags' => 'tag.label']
        )->where('entity_tag.entity_type_id IS NULL OR entity_type_id = 1');
        $this->collection->addFilterToMap('tags', 'tag.label');
        $this->collection
            ->addFieldToFilter(
                'customer_is_guest',
                ['eq' => 1]
            )
            ->addExpressionFieldToSelect(
                'count_orders',
                'COUNT({{increment_id}})',
                'increment_id'
            );
        $this->collection->getSelect()->group('customer_email');
        $this->collection->addAddressFields();

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return void
     */
    protected function prepareUpdateUrl()
    {

        if (!isset($this->data['config']['filter_url_params'])) {
            return;
        }
        foreach ($this->data['config']['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->request->getParam($paramName);
            }
            if ($paramValue) {
                $this->data['config']['update_url'] = sprintf(
                    '%s%s/%s/',
                    $this->data['config']['update_url'],
                    $paramName,
                    $paramValue
                );
            }
        }
    }

}
