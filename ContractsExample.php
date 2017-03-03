<?php

/**
 * Created by PhpStorm.
 * User: clark
 * Date: 2017-02-04
 * Time: 下午9:05
 */
use ContractsPerformer\ContractsPerformer as ContractsPerformer;

require_once __DIR__ . '/src/ContractsPerformer/ContractsPerformer.php';
require_once 'StaticClassExample.php';

$data_base = [
    'key0' => 'value0',
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => [
        'key3.1' =>[
            0 => [
                'key3_item_key1' => 'item_value0.1',
                'key3_item_key2' => 'item_value0.2',
                'key3_item_key3' => 'item_value0.3',
                'key3_item_key4' => 'item_value0.4',
                'key3_item_key5' => 'item_value0.5',
                'key3_item_key6' => 'item_value0.6',
            ],
            1 => [
                'key3_item_key1' => 'item_value1.1',
                'key3_item_key2' => 'item_value1.2',
                'key3_item_key3' => 'item_value1.3',
                'key3_item_key4' => 'item_value1.4',
                'key3_item_key5' => 'item_value1.5',
                'key3_item_key6' => 'item_value1.6',
            ]
        ]
    ],
    'key4' => [
        'key4.1' => 'value4.1',
        'key4.2' => 'value4.2',
        'key4.3' => 'value4.3'
    ]
];
$data = $data_base;
$data['key5'] = $data_base;
$data['key5']['key5.5'] = $data_base;


$post_function = function($args){
    var_dump($args);
    return $args['contract_value'] . ' post_closure_function ' . $args[0];
};


$contract_base = [
    'string' => 'key0',
    'contract_key_1' => [
        'key' => 'key1',
        'post_closure_function' =>[
            'args' => ['arg1' , 'arg2'],
            'func' => $post_function
        ]
    ],
    'contract_key_2' => [
        'type' => ContractsPerformer::CONTRACT_DATA_TYPE_KEY,
        'key' => 'key2',
        'post_static_function' => [
            'args' => ['arg1' , 'arg2'],
            'class' => 'StaticClassExample',
            'method' => 'testPostPerform'
        ]
    ],
    'contract_key3' => [
        'type' => ContractsPerformer::CONTRACT_DATA_TYPE_ITEM,
        'key' => 'key3][key3.1',
        'contracts' => [
            'contract_item_key4' => [
                'type' => ContractsPerformer::CONTRACT_DATA_TYPE_KEY,
                'key' => 'key3_item_key4'
            ],
            'contract_item_key1' => [
                'type' => ContractsPerformer::CONTRACT_DATA_TYPE_KEY,
                'key' => 'key3_item_key1',
            ]
        ]
    ],
    'contract_key4' => [
        'type' => ContractsPerformer::CONTRACT_DATA_TYPE_RECURSIVE,
        'key' => 'key4',
        'contracts' => [
            'contract_key4.1' => [
                'key' => 'key4.1'
            ],
            'contract_key4.3' => 'key4.3'
        ]
    ],
];
$contract = $contract_base;

$contract['contract_key5'] = [
    'type' => ContractsPerformer::CONTRACT_DATA_TYPE_RECURSIVE,
    'key' => 'key5',
    'contracts' => $contract_base
];

$contract['contract_key5']['contracts']['contract_key5.5'] = [
    'type' => ContractsPerformer::CONTRACT_DATA_TYPE_RECURSIVE,
    'key' => 'key5.5',
    'contracts' => $contract_base
];

$t = ContractsPerformer::perform($contract, $data);
print_r($t);