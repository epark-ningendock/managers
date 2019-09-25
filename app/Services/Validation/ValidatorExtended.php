<?php

//@link https://laracasts.com/discuss/channels/laravel/how-would-i-create-a-new-custom-validation-rule

namespace App\Services\Validation;

use Illuminate\Validation\Validator as IlluminateValidator;

class ValidatorExtended extends IlluminateValidator
{
    private $_custom_messages = array(
        "number_dash" => 'Numbers and dash is allowed',
        "longitude_latitude" => 'Value is invalid',
    );

    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);

        // $this->_set_custom_stuff();
    }

    /**
     * Setup any customizations etc
     *
     * @return void
     */
    protected function _set_custom_stuff()
    {
        //setup our custom error messages
        $this->setCustomMessages($this->_custom_messages);
    }


    /**
     * Input only allows number and dash
     * @param  string $value input attribute value
     * @return bool
     */
    protected function validateNumberDash($attribute, $value='')
    {
        return preg_match("/(^[0-9- ]+$)+/", $value) ? true : false;
    }

    /**
     * Input only allows number and dash
     * @param  string $value input attribute value
     * @return bool
     */
    protected function validateStrongPassword($attribute, $value='')
    {
        return preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/", $value) ? true : false;
    }

    /**
     * Start letter must be K. this code need to be improved to work any start letter
     *
     * @param $attribute
     * @param string $value
     *
     * @return bool
     */
    protected function validateStartLetterK($attribute, $value='')
    {
        return preg_match("/^[K]/", $value) ? true : false;
    }


    /**
     * Need to start with alphabet and follow numbers
     * @param $attribute
     * @param string $value
     *
     * @return bool
     */
    protected function validateStartAlphabetAndNumber($attribute, $value='')
    {
        return preg_match("/^[A-Za-z][A-Za-z0-9]*$/", $value) ? true : false;
    }


    /**
     * float number untill 7 places longitude_latitude
     *
     * @param $attribute
     * @param string $value
     * @return bool
     */
    protected function validateLongitudeLatitude($attribute, $value='')
    {
        return preg_match("/^\d*(\.\d{7})?$/", $value) ? true : false;
    }

    protected function validateLongitude($attribute, $value='')
    {
        if(preg_match("/^\d*(\.\d{7})?$/", $value))
        $value = sprintf('%.7f',$value);
        return preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,7}$/", $value) ? true : false;
    }

    protected function validateLatitude($attribute, $value='')
    {
        $value = sprintf('%.7f',$value);
        return preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,7}$/", $value) ? true : false;
    }
}
