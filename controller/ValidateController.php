<?php
namespace App\Helper\Validate\Controller;

use App\System\Controller;

class ValidateController extends Controller{
    
    public static function is()
    {
        $args = func_get_args();
        $value = $args[0];
        $validation = $args[1];
        $arguments = isset($args[2]) && is_array($args[2]) && sizeof($args[2]) == 1 ? $args[2][0] : $args[2];
        $input = $args[3];
        
        $obj = new \stdClass();

        switch ($validation) {
            case 'required':
                $obj->status = self::required($value);
                $obj->error = 'required';
                break;

            case 'isset':
                $obj->status = self::set($value);
                $obj->error = 'not set';
                break;

            case 'min':
                $obj->status = self::min($value, $arguments);
                $obj->error = "length should be larger than {$arguments}";
                break;

            case 'max':
                $obj->status = self::max($value, $arguments);
                $obj->error = "length should be less then {$arguments}";
                break;

            case 'between':
                $obj->status = self::between($value, $arguments);
                $obj->error = "length should be between " . implode(', ', $arguments);
                break;

            case 'exact-length':
                $obj->status = self::exact_length($value, $arguments);
                $obj->error = "length sould be exact as {$arguments}";
                break;

            case 'equals':
                $obj->status = self::equals($value, $arguments);
                $obj->error = "must equals to {$arguments}";
                break;

            case 'equal-one':
                $obj->status = self::equal_one($value, $arguments);
                $obj->error = "must equal one of " . implode(' or ', $arguments);
                break;

            case 'length-one':
                $obj->status = self::length_one($value, $arguments);
                $obj->error = "length should be one of " . implode(' or ', $arguments);
                break;
                
            case 'gt':
                $obj->status = self::gt($value, $arguments);
                $obj->error = "must be greater than $arguments";
                break;

            case 'gte':
                $obj->status = self::gte($value, $arguments);
                $obj->error = "must be greater or equal to $arguments";
                break;

            case 'lt':
                $obj->status = self::lt($value, $arguments);
                $obj->error = "must be less then $arguments";
                break;

            case 'lte':
                $obj->status = self::lte($value, $arguments);
                $obj->error = "must be less than or equal to $arguments";
                break;

            case 'range':
                $obj->status = self::range($value, $arguments);
                $obj->error = "must be within the range " . implode(' and ', $arguments);
                break;

            case 'numeric':
                $obj->status = self::numeric($value);
                $obj->error = 'should be a number';
                break;

            case 'integer':
                $obj->status = self::integer($value);
                $obj->error = 'not valid integer';
                break;

            case 'string':
                $obj->status = self::string($value);
                $obj->error = 'not valid string';
                break;

            case 'float':
                $obj->status = self::float($value);
                $obj->error = 'not valid float';
                break;

            case 'alpha':
                $obj->status = self::alpha($value);
                $obj->error = 'must be alphabet characters only';
                break;

            case 'alpha-numeric':
                $obj->status = self::alpha_numeric($value);
                $obj->error = 'alphabet and numbers allowed only';
                break;

            case 'email':
                $obj->status = self::email($value);
                $obj->error = 'not valid email address';
                break;

            case 'ip':
                $obj->status = self::ip($value);
                $obj->error = 'not valid IP address';
                break;

            case 'url':
                $obj->status = self::url($value);
                $obj->error = 'not valid URL';
                break;

            case 'unique':
            case 'exist':
                $obj->status = self::exist($value, $arguments, $input);
                $obj->error = 'already exists in database';
                break;

            case 'notunique':
            case 'notexist':
                $obj->status = self::notexist($value, $arguments, $input);
                $obj->error = 'not exists in database';
                break;

            default:
                $obj->status = true;
                $obj->error = '';
                break;
        }

        return $obj;
    }

    protected static function set($value = null)
    {
        return isset($value);
    }

    protected static function required($value = null)
    {
        return !is_null($value) && (trim($value) != '');
    }

    protected static function numeric($value)
    {
        return is_numeric($value);
    }

    protected static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected static function integer($value)
    {
        return is_int($value) || ($value == (string) (int) $value);
    }

    protected static function string($value)
    {
        return is_string($value) || ($value == (string) $value);
    }

    protected static function float($value)
    {
        return is_float($value) || ($value == (string) (float) $value);
    }

    protected static function alpha($value)
    {
        return preg_match('#^[a-zA-Z]+$#', $value) == 1;
    }

    protected static function alpha_numeric($value)
    {
        return preg_match('#^[a-zA-Z0-9]+$#', $value) == 1;
    }

    protected static function ip($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP);
    }

    protected static function url($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }

    protected static function max($value, $length)
    {
        return strlen($value) <= $length;
    }

    protected static function min($value, $length)
    {
        return strlen($value) >= $length;
    }

    protected static function range($value, $length)
    {
        if(!is_array($param)) {
            $param = explode(',', $param);
        }

        return $value >= $param[0] && $value <= $param[1];
    }
    protected static function lt($value, $length)
    {
        return $value < $length;
    }
    protected static function lte($value, $length)
    {
        return $value <= $length;
    }
    protected static function gt($value, $length)
    {
        return $value > $length;
    }    protected static function gte($value, $length)
    {
        return $value >= $length;
    }

    protected static function between($value, $param)
    {
        if(!is_array($param)) {
            $param = explode(',', $param);
        }

        return strlen($value) >= $param[0] && strlen($value) <= $param[1];
    }

    protected static function exact_length($value, $length)
    {
        return strlen($value) == $length;
    }

    protected static function equals($value, $param)
    {
        return $value == $param;
    }

    protected static function equal_one($value, $param)
    {
        if(!is_array($param)) {
            $param = explode(',', $param);
        }

        return $value == $param[0] || $value == $param[1];
    }

    protected static function length_one($value, $param)
    {
        if(!is_array($param)) {
            $param = explode(',', $param);
        }

        return strlen($value) == $param[0] || strlen($value) == $param[1];
    }

    protected static function exist($value, $arguments, $input)
    {
        // extract table name from arguments and check if has multiple
        if(is_array($arguments)){
            $table = array_shift($arguments);
        }else{
            $table = $arguments;
        }

        // starting query
        $query = Query::table($table);

        // checking for argument to have a custom column name or multiple search
        if(is_array($arguments) && sizeof($arguments) > 0){

            $query->where(array_shift($arguments), $value);
            
            // checking if arguments has additional search columns
            foreach($arguments as $extraColumn){
                $query->orWhere($extraColumn, $value);
            }

        }else{
            // default column will b the input name
            $query->where($input, $value);
        }
        
        $count = $query->count();

        return $count > 0 ? true : false;
    }

    protected static function notexist($value, $arguments, $input)
    {
        return !self::exist($value, $arguments, $input);
    }
    // TODO : date check, dateISO, digits, creditcard, regix, phone, in, in_array, beforeData, afterDate
}