<?php

use Illuminate\Support\Str;

if (!function_exists('remove_rule')) {

    /**
     * Remove/unset a rule(s) from an attribute
     *
     * @param string|array $attributeRules The validation rules (string format: 'required|email' or array format: ['required', 'email'])
     * @param array $rulesToRemove Array of rule names to remove (e.g., ['unique', 'email'])
     * @return string|array Returns the same format as input (string or array) with specified rules removed
     */
    function remove_rule(string|array $attributeRules, array $rulesToRemove): string|array
    {
        // Convert string rules to array format for easier processing
        $attributeRulesInArray = is_string($attributeRules) ? explode('|', $attributeRules) : $attributeRules;

        // Loop through each rule to check if it should be removed
        foreach ($attributeRulesInArray as $index => $rule) {

            // Convert object rules (e.g., new Unique()) to their string representation
            $ruleString = is_object($rule) ? Str::snake(class_basename($rule)) : $rule;

            // Check against each rule that should be removed
            foreach ($rulesToRemove as $ruleToRemove) {
                if (Str::startsWith($ruleString, $ruleToRemove)) {
                    unset($attributeRulesInArray[$index]);
                    // No need to check remaining elements of '$rulesToRemove' as required rule already removed from it
                    break;
                }
            }
        }

        // Re-index array to remove gaps in keys
        $attributeRulesInArray = array_values($attributeRulesInArray);

        // Return in the same format as input (string or array)
        return is_string($attributeRules) ?
            implode('|', $attributeRulesInArray) :
            $attributeRulesInArray;
    }

}