<?php

if ( ! function_exists('checkPrivilege'))
{
    /**
     *
     * @param $options (Integer Or Array regarding type of parameters)
     * @return bool|mixed
     */
    function checkPrivilege($options = 0)
    {
        $ret = false;
        $rolesSessions= session()->get('roles');
        if(is_array($options))
        {
            foreach ($options as $roles=>$test){
                if (( $rolesSessions & $roles) && !$ret){
                    $ret = $test;
                }
            }
        }elseif(is_numeric($options))
        {
            $ret= $rolesSessions & $options; 
        }
        return $ret;
    }

    /**
     *
     * @param $options (Integer Or Array regarding type of parameters)
     * @return bool|mixed
     */
    function checkLogged()
    {
        return session()->get('logged_in')??FALSE;
    }

}