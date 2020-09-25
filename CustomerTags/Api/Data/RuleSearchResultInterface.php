<?php
namespace RookieSoft\CustomerTags\Api\Data;

/**
 * @api
 * @since 100.0.2
 */
interface TagRuleSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get tag rules.
     *
     * @return \RookieSoft\CustomerTags\Api\Data\TagRuleInterface[]
     */
    public function getItems();

    /**
     * Set tag rules .
     *
     * @param \RookieSoft\CustomerTags\Api\Data\TagRuleInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
