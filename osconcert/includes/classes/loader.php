<?php
// Check to ensure this file is included in osConcert!
defined('_FEXEC') or die();
//this are to be testing
    class loader{
        function &loadClass($package,$class){
            $fileMap=DIR_WS_INCLUDES . str_replace("\.","\/",$package) . "/" . strtolower($class) .".php";
            if (!file_exists($fileMap)){
                return false;
            }
            $class="cls" . $class;
            @include_once($fileMap);
            $instance=new $class();

            return $instance;
        }
    }
?>