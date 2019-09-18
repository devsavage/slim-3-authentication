<?php

namespace App\Validation\Violin;

use Closure;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

use App\Validation\Violin\Support\MessageBag;
use App\Validation\Violin\Contracts\ValidatorContract;

class Violin implements ValidatorContract
{
    /**
     * Rule objects that have already been instantiated.
     *
     * @var array
     */
    protected $usedRules = [];

    /**
     * Custom user-defined rules
     *
     * @var array
     */
    protected $customRules = [];

    /**
     * Collection of errors.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Inputted fields and values.
     *
     * @var array
     */
    protected $input = [];

    /**
     * Rule messages.
     *
     * @var array
     */
    protected $ruleMessages = [];

    /**
     * Field messages.
     *
     * @var array
     */
    protected $fieldMessages = [];

    /**
     * Field Aliases.
     *
     * @var array
     */
    protected $fieldAliases = [];

    /**
     * Kick off the validation using input and rules.
     *
     * @param  array  $input
     * @param  array  $rules
     *
     * @return this
     */
    public function validate(array $data, $rules = [])
    {
        $this->clearErrors();
        $this->clearFieldAliases();

        $data = $this->extractFieldAliases($data);

        // If the rules array is empty, then it means we are
        // receiving the rules directly with the input, so we need
        // to extract the information.
        if (empty($rules)) {
            $rules = $this->extractRules($data);
            $data  = $this->extractInput($data);
        }

        $this->input = $data;

        foreach ($data as $field => $value) {
            $fieldRules = explode('|', $rules[$field]);

            foreach ($fieldRules as $rule) {
                $this->validateAgainstRule(
                    $field,
                    $value,
                    $this->getRuleName($rule),
                    $this->getRuleArgs($rule)
                );
            }
        }

        return $this;
    }

    /**
     * Checks if validation has passed.
     *
     * @return bool
     */
    public function passes()
    {
        return empty($this->errors);
    }

    /**
     * Checks if validation has failed.
     *
     * @return bool
     */
    public function fails()
    {
        return ! $this->passes();
    }

    /**
     * Gather errors, format them and return them.
     *
     * @return array
     */
    public function errors()
    {
        $messages = [];

        foreach ($this->errors as $rule => $items) {
            foreach ($items as $item) {
                $field = $item['field'];

                $message = $this->fetchMessage($field, $rule);

                // If there is any alias for the current field, swap it.
                if (isset($this->fieldAliases[$field])) {
                    $item['field'] = $this->fieldAliases[$field];
                }

                $messages[$field][] = $this->replaceMessageFormat($message, $item);
            }
        }

        return new MessageBag($messages);
    }

    /**
     * Adds a custom rule message.
     *
     * @param string $rule
     * @param string $message
    */
    public function addRuleMessage($rule, $message)
    {
        $this->ruleMessages[$rule] = $message;
    }

    /**
     * Adds custom rule messages.
     *
     * @param array $messages
    */
    public function addRuleMessages(array $messages)
    {
        $this->ruleMessages = array_merge($this->ruleMessages, $messages);
    }

    /**
     * Adds a custom field message.
     *
     * @param string $field
     * @param string $rule
     * @param string $message
    */
    public function addFieldMessage($field, $rule, $message)
    {
        $this->fieldMessages[$field][$rule] = $message;
    }

    /**
     * Adds custom field messages
     *
     * @param array $messages
    */
    public function addFieldMessages(array $messages)
    {
        $this->fieldMessages = $messages;
    }

    /**
     * Add a custom rule
     *
     * @param string $name
     * @param Closure $callback
     */
    public function addRule($name, Closure $callback)
    {
        $this->customRules[$name] = $callback;
    }

    /**
     * Fetch the message for an error by field or rule type.
     *
     * @param  string $field
     * @param  string $rule
     *
     * @return string
     */
    protected function fetchMessage($field, $rule)
    {
        if (isset($this->fieldMessages[$field][$rule])) {
            return $this->fieldMessages[$field][$rule];
        }

        if (isset($this->ruleMessages[$rule])) {
            return $this->ruleMessages[$rule];
        }

        return $this->usedRules[$rule]->error();
    }

    /**
     * Replaces message variables.
     *
     * @param  string $message
     * @param  array $item
     *
     * @return string
     */
    protected function replaceMessageFormat($message, array $item)
    {
        $keys = array_keys($item);

        if (!empty($item['args'])) {
            $args = $item['args'];

            $argReplace = array_map(function($i) {
                return "{\${$i}}";
            }, array_keys($args));

            // Number of arguments
            $args[] = count($item['args']);
            $argReplace[] = '{$#}';

            // All arguments
            $args[] = implode(', ', $item['args']);
            $argReplace[] = '{$*}';

            // Replace arguments
            $message = str_replace($argReplace, $args, $message);
        }

        // Replace field and value
        $message = str_replace(
            ['{field}', '{value}'],
            [$item['field'], $item['value']],
            $message
        );

        return $message;
    }

    /**
     * Validates value against a specific rule and handles
     * errors if the rule validation fails.
     *
     * @param  string $field
     * @param  string $value
     * @param  string $rule
     * @param  array $args
     *
     * @return void
     */
    protected function validateAgainstRule($field, $value, $rule, array $args)
    {
        $ruleToCall = $this->getRuleToCall($rule);

        if ($this->canSkipRule($ruleToCall, $value)) {
            return;
        }
        
        $passed = call_user_func_array($ruleToCall, [
            $value,
            $this->input,
            $args
        ]);

        if (!$passed) {
            $this->handleError($field, $value, $rule, $args);
        }
    }

    /**
     * Method to help skip a rule if a value is empty, since we
     * don't need to validate an empty value. If the rule to
     * call specifically doesn't allowing skipping, then
     * we don't want skip the rule.
     *
     * @param  array $ruleToCall
     * @param  mixed $value
     *
     * @return null
     */
    protected function canSkipRule($ruleToCall, $value)
    {
        return (
            (is_array($ruleToCall) &&
            method_exists($ruleToCall[0], 'canSkip') &&
            $ruleToCall[0]->canSkip()) &&
            empty($value) &&
            !is_array($value)
        );
    }

    /**
     * Clears all previously stored errors.
     *
     * @return void
     */
    protected function clearErrors()
    {
        $this->errors = [];
    }

    /**
     * Stores an error.
     *
     * @param  string $field
     * @param  string $value
     * @param  string $rule
     * @param  array $args
     *
     * @return void
     */
    protected function handleError($field, $value, $rule, array $args)
    {
        $this->errors[$rule][] = [
            'field' => $field,
            'value' => $value,
            'args' => $args,
        ];
    }

    /**
     * Gets and instantiates a rule object, e.g. IntRule. If it has
     * already been used, it pulls from the stored rule objects.
     *
     * @param  string $rule
     *
     * @return mixed
     */
    protected function getRuleToCall($rule)
    {
        if (isset($this->customRules[$rule])) {
            return $this->customRules[$rule];
        }

        if (method_exists($this, 'validate_' . $rule)) {
            return [$this, 'validate_' . $rule];
        }

        if (isset($this->usedRules[$rule])) {
            return [$this->usedRules[$rule], 'run'];
        }

        $ruleClass = 'App\\Validation\\Violin\\Rules\\' . ucfirst($rule) . 'Rule';
        $ruleObject = new $ruleClass();

        $this->usedRules[$rule] = $ruleObject;

        return [$ruleObject, 'run'];
    }

    /**
     * Determine whether a rule has arguments.
     *
     * @param  string $rule
     *
     * @return bool
     */
    protected function ruleHasArgs($rule)
    {
        return isset(explode('(', $rule)[1]);
    }

    /**
     * Get rule arguments.
     *
     * @param  string $rule
     *
     * @return array
     */
    protected function getRuleArgs($rule)
    {
        if (!$this->ruleHasArgs($rule)) {
            return [];
        }

        list($ruleName, $argsWithBracketAtTheEnd) = explode('(', $rule);

        $args = rtrim($argsWithBracketAtTheEnd, ')');
        $args = preg_replace('/\s+/', '', $args);
        $args = explode(',', $args);

        return $args;
    }

    /**
     * Gets a rule name.
     *
     * @param  string $rule
     *
     * @return string
     */
    protected function getRuleName($rule)
    {
        return explode('(', $rule)[0];
    }

    /**
     * Flatten an array.
     *
     * @param  array  $args
     *
     * @return array
     */
    protected function flattenArray(array $args)
    {
        return iterator_to_array(new RecursiveIteratorIterator(
            new RecursiveArrayIterator($args)
        ), false);
    }

    /**
     * Extracts field aliases from an input.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function extractFieldAliases(array $data)
    {
        foreach ($data as $field => $fieldRules) {
            $extraction = explode('|', $field);

            if (isset($extraction[1])) {
                $updatedField = $extraction[0];
                $alias        = $extraction[1];

                $this->fieldAliases[$updatedField] = $alias;
                $data[$updatedField] = $data[$field];
                unset($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Clears all field aliases.
     *
     * @return void
     */
    protected function clearFieldAliases()
    {
        $this->fieldAliases = [];
    }

    /**
     * Extract the field input from the data array.
     * @param  array  $data
     * @return array
     */
    protected function extractInput(array $data)
    {
        $input = [];

        foreach ($data as $field => $fieldData) {
            $input[$field] = $fieldData[0];
        }

        return $input;
    }

    /**
     * Extract the field ruleset from the data array.
     *
     * @param  array  $data
     * @return array
     */
    protected function extractRules(array $data)
    {
        $rules = [];

        foreach ($data as $field => $fieldData) {
            $rules[$field] = $fieldData[1];
        }

        return $rules;
    }
}
