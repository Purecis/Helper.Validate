<?php
 /**
 * Helper.Validate
 *
 * @category   codeHive Extras
 * @package    Helper
 * @author     Tamer Zorba <tamer.zorba@purecis.com>
 * @copyright  Copyright (c) 2013 - 2016, Purecis, Inc.
 * @license    http://package.hive.live/Helper.Validate/license  MIT License
 * @link       http://package.hive.live/Helper.Validate
 * @version    Release: 3.0
 */

namespace App\Helper;

use \App\System\Module;
use App\Helper\Validate\Controller\ValidateController as VC;

class Validate extends Module{
    
    private $errors = [];
    private static $messages = [];

    public function __invoke()
    {
        $args = func_get_args();
        if(sizeof($args) < 2){
            return;
        }

        $request = $args[0];
        $checker = $args[1];
        
        call_user_func_array([$this, 'check'], $args);
        return $this;
    }

    public function check()
    {
        $args = func_get_args();
        $request   = is_object($args[0]) ? get_object_vars($args[0]) : $args[0];
        $checkable = $args[1];
        $errors    = [];

        // update custom messages
        if(isset($args[2])){
            self::$messages = $args[2];
        }

        foreach($checkable as $input => $cases){
            $cases = explode('|', $cases);
            if(sizeof($cases) == 0){
                continue;
            }
            foreach($cases as $case){
                $arguments = explode(':', $case);
                $type = array_shift($arguments);
                $validate = VC::is(isset($request[$input]) ? $request[$input] : null, $type, $arguments, $input);
                
                // allow empty type
                if($type == 'empty'){
                    if($validate->status){
                        break;
                    }else{
                        continue;
                    }
                }

                if(!$validate->status){
                    // check for input if exists or initialize it
                    if(!isset($errors[$input])){
                        $errors[$input] = [];
                    }

                    // check for custom message
                    $key = $input . '.' . $type;
                    $message = isset(self::$messages[$key]) ? self::$messages[$key] : $validate->error;

                    // adding errors to input object
                    array_push($errors[$input], $message);
                }
            }
        }
        $this->errors = $errors;
        return $this;
    }

    public function messages()
    {
        self::$messages = array_merge(self::$messages, func_get_arg(0));
        return $this;
    }

    public function orFail()
    {
        if(sizeof($this->errors)){
            $this->response($this->errors)->code(422)->kill();
        }
        return $this;
    }

    public function errors()
    {
        return $this->errors;
    }
}


