<?php

namespace RookieSoft\CustomerTags\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\ResourceConnection;
use RookieSoft\CustomerTags\Model\ResourceModel\GuestCustomer\CollectionFactory as GuestCustomerCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;
use RookieSoft\CustomerTags\Model\ResourceModel\EntityTag\CollectionFactory as EntityTagCollectionFactory;
use Magento\Sales\Model\OrderFactory as SalesOrderFactory;

class GuestCustomerForm extends AbstractDataProvider
{
    private $orderEntityId = null;
    protected $_loadedData;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Request\Http $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ResourceConnection $resource,
        SalesOrderFactory $salesOrderFactory,
        CollectionFactory $salesOrderCollectionFactory,
        CustomerCollectionFactory $customerCollectionFactory,
        GuestCustomerCollectionFactory $guestCustomerCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        EntityTagCollectionFactory $entityTagCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->salesOrderFactory = $salesOrderFactory;
        $this->collection = $salesOrderCollectionFactory->create();
        $this->customerCollectionFactory = $salesOrderCollectionFactory->create();
        $this->guestCustomerCollectionFactory = $guestCustomerCollectionFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->entityTagCollectionFactory = $entityTagCollectionFactory;
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    private function getOrderEntityId()
    {

        if(!$this->orderEntityId) {
            $this->orderEntityId = $this->request->getParam('id');
        }
        return $this->orderEntityId;
    }

    public function getEmailAdress()
    {
        return $this->salesOrderFactory
            ->create()
            ->load($this->getOrderEntityId())
            ->getCustomerEmail();
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
                    'email',$this->getEmailAdress()
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
                    if($tagCode->getEntityTypeId() == 1) {

                        foreach($tagIds as $id) {
                            $tagId[] = $id->getId();
                        }
                    }
                }
            }
            $email->setTag($tagId);
            $this->_loadedData[$email->getId()] = $email->getData();
        }
        //error_log(print_r($this->getEmailAdress(),true));
        return $this->_loadedData;
    }
}
