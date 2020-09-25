<?php

namespace RookieSoft\CustomerTags\Api\Data;

interface TagRuleInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const RULE_ID                     = 'rule_id';
    const RULE_NAME              = 'rule_name';
    // const TAG_ID                 = 'tag_id';
    const CONDITIONS_SERIALIZED  = 'conditions_serialized';
    const STATE                  = 'state';
    const EVENT_NAME             = 'event_name';

    /**
     * Get Rule Id
     *
     * @return int|null
     */
    public function getRuleId();

    /**
     * Get Rule Name
     *
     * @return string|null
     */
    public function getRuleName();

    // *
    //  * Get Tag Id
    //  *
    //  * @return string|null

    // public function getTagId();

    /**
     * Get Conditions Serialized
     *
     * @return string|null
     */
    public function getConditionsSerialized();

    /**
     * Get State
     *
     * @return int|null
     */
    public function getState();

    /**
     * Get Event Name
     *
     * @return int|null
     */
    public function getEventName();

    /**
     * Set Rule Id
     *
     * @param int $ruleId
     * @return $this
     */
    public function setRuleId($ruleId);

    /**
     * Set Rule Name
     *
     * @param string $ruleName
     * @return $this
     */
    public function setRuleName($ruleName);

    // *
    //  * Set Tag Id
    //  *
    //  * @param int $tagId
    //  * @return $this

    // public function setTagId($tagId);

    /**
     * Set Conditions Serialized
     *
     * @param string $conditionsSerialized
     * @return $this
     */
    public function setConditionsSerialized($conditionsSerialized);

    /**
     * Set State
     *
     * @param int $State
     * @return $this
     */
    public function setState($state);

    /**
     * Set Event Name
     *
     * @param string $eventName
     * @return $this
     */
    public function setEventName($eventName);
}
