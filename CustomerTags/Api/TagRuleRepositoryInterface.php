<?php

namespace RookieSoft\CustomerTags\Api;

interface TagRuleRepositoryInterface
{
/**
     * Save Tag rule.
     *
     * @param \Magento\SalesRule\Api\Data\TagRuleInterface $rule
     * @return \Magento\SalesRule\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\InputException If there is a problem with the input
     * @throws \Magento\Framework\Exception\NoSuchEntityException If a rule ID is sent but the rule does not exist
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\RookieSoft\CustomerTags\Api\Data\TagRuleInterface $rule);

    /**
     * Get rule by ID.
     *
     * @param int $ruleId
     * @return \RookieSoft\CustomerTags\Api\Data\TagRuleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If $id is not found
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($ruleId);

    /**
     * Retrieve tag rules.
     *
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete rule by ID.
     *
     * @param int $ruleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ruleId);
}
