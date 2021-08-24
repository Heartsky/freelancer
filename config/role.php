<?php

return [
    "list_role" => [
        'summary_work',
        'customer_invoice',
        'revenue_summary',
        'finance_summary',
        'expense_summary',
        'labor_union',
        'import_resource',

    ],
    'summary_type' => [
        'job_category' => 'Category',
        'customer' => "Customer",
        'code' => "Rank"
    ],
    'summary_group' => [
        '1' => "棟数",// can
        '2' => "坪数",//dien tich
        '3' => "坪数 & 棟数"
    ],
    'sub_category' => [
        "新規(壁パネル)" => '新規',
        "訂正2" => "訂正",
        "訂正1" => "訂正"
    ],
    'expense_group' => [
        1 =>  'bank' ,
        2 =>  'cash',
        3 => 'epense not real paid',
        4 =>  'visa'
    ],
    'transaction_type' => [
        'debited', //(chi)/
        'credited' // (thu)
    ],
    'currency_list' => [
        'VND' => 'VND',
        'USD' => 'USD',
        'JPY' => 'JPY'

    ]




];
