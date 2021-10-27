<?php

namespace Core;

use Core\ErrorHandler;
use Core\Model;

class Validator
{
    protected $errorHandler;

    protected $items;

    protected $rules = ['required', 'minLength', 'maxLength', 'email', 'unique', 'match'];

    public $messages = [
        'required' => 'The :field field is required',
        'minLength' => 'The :field field must be a minimum of :satisifer length',
        'maxLength' => 'The :field field must be a maximum of :satisifer length',
        'email' => 'That is not a valid email address',
        'unique' => 'That :field is already taken',
        'match' => 'The :field field must match the :satisifer field',
    ];

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    // $items $_POST fields, $rules
    // for example
    //  'name' => [
    //      'required' => true
    //   ]
    public function check($items, $rules)
    {
        $this->items = $items;

        foreach ($items as $item => $value) {
            // for example if in $rules array there is a key that matches to ($_POST name field($item)) do validation
            if (in_array($item, array_keys($rules))) {
                Session::set('postData', $item, $value);
                $this->validate([
                    'field' => $item, //for example name
                    'value' => $value, // Sergey
                    'rules' => $rules[$item] //array of name ['required' => true]
                ]);
            }
        }

        return $this;
    }

    public function fails()
    {
        return $this->errorHandler->hasErrors();
    }

    public function errors()
    {
        return $this->errorHandler;
    }

    protected function validate($item)
    {
        $field = $item['field']; // $_POST['name']

        foreach ($item['rules'] as $rule => $satisifer) {
            // ['required' => true]
            if (in_array($rule, $this->rules)) {
                // if $rule matches $this->rules standards
                if (!call_user_func_array([$this, $rule], [$field, $item['value'], $satisifer])) {
                    $this->errorHandler->addError(
                        str_ireplace([':field', ':satisifer'], [$field, $satisifer], $this->messages[$rule]),
                        $field);
                    break;
                }
            }
        }
    }

    protected function required($field, $value, $satisifer)
    {
        return !empty(trim($value));
    }

    protected function minLength($field, $value, $satisifer)
    {
        return mb_strlen($value) >= $satisifer;
    }

    protected function maxLength($field, $value, $satisifer)
    {
        return mb_strlen($value) <= $satisifer;
    }

    protected function email($field, $value, $satisifer)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected function unique($field, $value, $satisifer)
    {
        $db = new Model();
        return !$db->table($satisifer)->exists([
            $field => $value
        ]);
    }

    protected function match($field, $value, $satisifer)
    {
        return $value === $this->items['password'];
     }
}
