<?php

namespace RookieSoft\CustomerTags\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;
use RookieSoft\CustomerTags\Model\ResourceModel\GuestCustomer\CollectionFactory as GuestCustomerCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;

class CustomerForm extends AbstractDataProvider
{
    protected $_loadedData;


    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ResourceConnection $resource,
        CollectionFactory $salesOrderCollectionFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        GuestCustomerCollectionFactory $guestCustomerCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $salesOrderCollectionFactory->create();
        $this->customerCollectionFactory = $salesOrderCollectionFactory->create();
        $this->guestCustomerCollectionFactory = $guestCustomerCollectionFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    /**
     * getData
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $customeritems = $this->customerCollectionFactory->getItems();
        $tagId = [];

        foreach ($customeritems as $email) {
            //get all tags for mail address, typecast to STRING into Array
            $customer = $this->guestCustomerCollectionFactory
                ->create()
                ->addFieldToFilter(
                    'email',$email->getCustomerEmail()
                );

            foreach($customer as $data){
                $tagCodes = $this->entityTagCollectionFactory
                ->create()
                ->addFieldToFilter(
                    'entity_id' , $data->getId()
                );

                foreach($tagCodes as $tagCode){

                    $tagIds = $this->tagCollectionFactory
                        ->create()
                        ->addFieldToFilter(
                            'code',$tagCode->getTagCode()
                        );
                    if($tagCode->getEntityTypeId() == 2) {

                        foreach($tagIds->getItems() as $id) {
                            $tagId[] = $id->getId();
                        }
                    }
                }
            }
            $email->setTag($tagId);
            $this->_loadedData[$email->getId()] = $email->getData();
        }
        return $this->_loadedData;
    }
}
