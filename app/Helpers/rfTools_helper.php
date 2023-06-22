<?php

/**
 * Apply array to global config array
 * @param array $parameters
 * @return bool
 */
function applyToConfigArray(array $parameters): bool
{
    $ret=FALSE;

    // check if $parameters is an array
    if(is_array($parameters))
    {
        // apply $parameters to an array
        foreach($parameters as $key => $value)
        {
            $config[$key] = $value;
        }

        $ret=TRUE;
    }

    return $ret;
}
