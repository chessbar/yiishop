<?php

return [
    'adminEmail' => 'admin@example.com',
    'pageSize'=>[
    	'managers'=>20,
    	'users'=>20,
    	'products'=>20,
    	'showProducts'=>24,
		'orders'=>20,
		'frontOrders'=>20,
    ],
    'defaultValue'=>[
    	'avatar'=>'assets/admin/img/contact-img.png',
    ],
	'express'=>[
		1=>'包邮',
		2=>'中通快递',
		3=>'顺丰快递'
	],
	'expressPrice'=>[
		1=>0,
		2=>15,
		3=>20
	]

];
