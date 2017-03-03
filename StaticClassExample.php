<?php

/**
 * Created by PhpStorm.
 * User: clark
 * Date: 2017-02-04
 * Time: 下午9:30
 */
class StaticClassExample
{
    public static function testPostPerform($args){
        return $args['contract_value'] . ' ServiceContracts::postFunc ' . $args[1];
    }
}