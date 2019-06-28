<?php

//NOTE: helper functions should be in "app", but not it's namespace

if(!function_exists('factoryWithSetup')) //incase of override
{
    /**
     * create a factory with all dependencies automatically fulfilled
     *
     * @param  String   $model_class_name   the models class name
     * @return Factory                      the setup factory
     */
    function factoryWithSetup($model_class_name, $count = null)
    {
        return factory($model_class_name, $count)->states('with_setup');
    }
}
