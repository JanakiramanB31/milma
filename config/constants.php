<?php
return [
    'STOCK_TYPES' => [
        'WS' => array('name'=>'Warehouse Stock', 'short_name'=>'WS'),
        'ST' => array('name'=>'Stock in Transit', 'short_name'=>'ST'),
        'SS' => array('name'=>'Sold Stock', 'short_name'=>'SS'),
        'DS' => array('name'=>'Damage Stock', 'short_name'=>'DS'),
        'ES' => array('name'=>'Expired Stock', 'short_name'=>'ES'),
        'RS' => array('name'=>'Returned Stock', 'short_name'=>'RS'),
    ],
    'CURRENCY_SYMBOL' => '£',
    'DECIMAL_LENGTH' => '2',
    'RETURN_REASON' => [
        'D' => array('name'=>'Damage', 'short_name'=>'D'),
        'EXP' => array('name'=>'Expired', 'short_name'=>'EXP'),
        'RS' => array('name'=>'Reduce Stock', 'short_name'=>'RS'),
    ],
];