<?php
namespace RookieSoft\CustomerTags\Model\ResourceModel\Order\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OriginalCollection;
use Psr\Log\LoggerInterface as Logger;

class Collection extends OriginalCollection
{
    /**
     * Constructor
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     */
    public function __construct(
       EntityFactory $entityFactory,
       Logger $logger,
       FetchStrategy $fetchStrategy,
       EventManager $eventManager
      ) {
       parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager);
    }

    /**
     * function to show the Tags in sales_order grid
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->getSelect()->joinLeft([
                    'entity_tag' => 'rookiesoft_customertags_entity_tag'],
                    'main_table.entity_id = entity_tag.entity_id',
                    ['tag_code' => 'entity_tag.tag_code','entity_type_id' => 'entity_tag.entity_type_id'])
                ->joinLeft([
                    'tag' => 'rookiesoft_customertags_tag'],
                    'entity_tag.tag_code = tag.code',
                    ['label'  => 'tag.label']
                )->where('entity_type_id IS NULL OR entity_type_id = 3');
                $this->getSelect()->group('main_table.entity_id');

        parent::_renderFiltersBefore();
    }

    /**
     * function to make filters work
     *
     * @return void
     */
    protected function _initSelect()
    {

        $this->addFilterToMap(
            'main_table.label',
            'tag.label'
        );

        parent::_initSelect();
    }
}