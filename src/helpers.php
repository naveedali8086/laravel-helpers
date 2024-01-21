<?php

use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

if (!function_exists('get_all_rules_except_unique_rule')) {
    /**
     * Get all rules of a single attribute except unique rule<br>so that it may be overridden
     *
     * @param string|array $attributeRules
     * @return string|array
     */
    function get_all_rules_except_unique_rule(string|array $attributeRules): string|array
    {
        $attributeRulesInArray = is_string($attributeRules) ? explode('|', $attributeRules) : $attributeRules;

        foreach ($attributeRulesInArray as $index => $rule) {
            if (
                (is_string($rule) && Str::startsWith($rule, 'unique:')) ||
                $rule instanceof Unique
            ) {
                unset($attributeRulesInArray[$index]);
                break;
            }
        }

        $attributeRulesInArray = array_values($attributeRulesInArray);

        return is_string($attributeRules) ?
            implode('|', $attributeRulesInArray) :
            $attributeRulesInArray;
    }

}

if (!function_exists('remove_rule')) {
    /**
     * Remove/unset a rule(s) from an attribute
     *
     * @param string|array $attributeRules
     * @param array $rulesToRemove
     * @return string|array
     */
    function remove_rule(string|array $attributeRules, array $rulesToRemove): string|array
    {
        $attributeRulesInArray = is_string($attributeRules) ? explode('|', $attributeRules) : $attributeRules;

        foreach ($attributeRulesInArray as $index => $rule) {

            $rule = is_object($rule) ? Str::snake(class_basename($rule)) : $rule;

            foreach ($rulesToRemove as $ruleToRemove) {
                if (Str::startsWith($rule, $ruleToRemove)) {
                    unset($attributeRules[$index]);
                }
                return array_values($attributeRules);
            }
        }
        return [];
    }

}
