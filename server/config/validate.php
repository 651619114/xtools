<?php
return [
    'admin' => [
        'useradd' => [
            'name' => 'required|max:50',
            'phone' => 'required|numeric',
            'email' => 'required|email:rfc,dns',
            'role_id' => 'required',
            'password' => 'required',
        ],
        'userchange' => [
            'id' => 'required',
        ],
        'userdelete' => [
            'id' => 'required',
        ],
        'usermodify' => [
            'id' => 'required',
            'name' => 'required|max:50',
            'phone' => 'required|numeric',
            'email' => 'required|email:rfc,dns',
            'role_id' => 'required',
        ],

        'menuadd' => [
            'name' => 'required',
            'display' => 'required',
        ],
        'menudelete' => [
            'menu_id' => 'required',
        ],
        'menumodify' => [
            'name' => 'required',
            'display' => 'required',
        ],
        'ocradd' => [
            'source_path' => 'required',
        ],
        'ocrdelete' => [
            'id' => 'required',
        ],
    ],
    'api' => [
        'goodslist' => [
            'class_id' => 'required'
        ],
        'goodsroles' => [
            'item_id' => 'required'
        ],
        'addshopshop' => [
            'item_id' => 'required',
            'role_id' => 'required',
            'num' => 'required'
        ],
        'shopdetail' => [
            'item_id' => 'required',
        ],
        'modifyadress' => [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ],
        'addressdetail' => [
            'id' => 'required',
        ],
        'deleteaddress' => [
            'id' => 'required',
        ],
        'updateaddress' => [
            'id' => 'required',
        ]
    ]
];
