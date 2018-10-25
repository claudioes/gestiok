<?php

namespace App\Policies;

class PolicyResolver
{
    /**
     * Get a policy instance for the given class
     * 
     * @param  mixed $model
     * @return \App\Policies\Policy
     */
    public static function for($model): Policy
    {
        $className = (new \ReflectionClass($model))->getShortName();
        $policy = "App\Policies\\{$className}Policy";

        if ( ! is_object($model)) {
            $model = null;
        }
        
        return new $policy($model);
    }
}
