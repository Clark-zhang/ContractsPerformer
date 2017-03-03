<?php

/**
 * Created by PhpStorm.
 * User: clark
 * Date: 2017-02-04
 * Time: 下午9:00
 */

namespace ContractsPerformer;

class ContractsPerformer
{
    CONST CONTRACT_DATA_TYPE_KEY = 1;
    CONST CONTRACT_DATA_TYPE_ITEM = 2;
    CONST CONTRACT_DATA_TYPE_RECURSIVE = 3;

    public static function perform($contracts, $data)
    {

        $r = [];
        foreach ($contracts as $contract_key => $contract) {
            //compatible
            if(!is_array($contract)){
                $tmp = $contract;
                $contract = [];
                $contract['key'] = $tmp;
            }
            //default type
            $contract['type'] = isset($contract['type']) ? $contract['type'] : self::CONTRACT_DATA_TYPE_KEY;

            switch ($contract['type']){
                case self::CONTRACT_DATA_TYPE_KEY:
                    $r[$contract_key] = $data[$contract['key']];
                    break;

                case self::CONTRACT_DATA_TYPE_ITEM:
                    $tmp = self::getValueByMultiKey($data, $contract['key']);
                    foreach ($tmp as $data_key => $data_item){
                        $r[$contract_key][$data_key] = ContractsPerformer::perform($contract['contracts'], $data_item);
                    }
                    break;

                case self::CONTRACT_DATA_TYPE_RECURSIVE:
                    $r[$contract_key] = ContractsPerformer::perform($contract['contracts'], $data[$contract['key']]);
                    break;

                default:
                    throw new \Exception('Unknown contract data type');
                    break;
            }

            if(isset($contract['post_closure_function'])){
                $closure_func = $contract['post_closure_function'];
                $closure_func['args']['contract_value'] = $r[$contract_key];
                $r[$contract_key] = $closure_func['func']($closure_func['args']);
            }

            if(isset($contract['post_static_function'])){
                $static_func = $contract['post_static_function'];
                $static_func['args']['contract_value'] = $r[$contract_key];
                $r[$contract_key] = $static_func['class']::$static_func['method']($static_func['args']);
            }
        }

        return $r;
    }


    public static function getValueByMultiKey($array, $key)
    {
        $keys_exploded = explode('][', $key);
        $tmp = $array;
        $count = count($keys_exploded);
        for ($i = 0; $i < $count; $i++){
            $tmp = $tmp[array_shift($keys_exploded)];
        }

        return $tmp;
    }
}