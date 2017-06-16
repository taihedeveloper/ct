<?php
class Service_Lib_Factory
{
    private static $_instance = array();


    public static function getInstance($classKey)
    {
        if(empty($classKey))
        {
            return null;
        }

        $_className = $classKey;
        if(strpos($_className, 'Service_Lib_') === false)
        {
            $_className = 'Service_Lib_' . $classKey;
        }

        if(!isset(self::$_instance[$_className]))
        {
            $dbObj = new $_className();
            self::$_instance[$_className] = $dbObj;
        }

        return self::$_instance[$_className];
    }
}