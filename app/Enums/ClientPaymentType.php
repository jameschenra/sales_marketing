<?php 

namespace App\Enums;

class ClientPaymentType {
    const TYPES = [
        [
            'type_id' => 1,
            'name' => 'Only online',
        ],
        [
            'type_id' => 2,
            'name' => 'Only on-site',
        ],
        [
            'type_id' => 3,
            'name' => 'Online or on-site',
        ],
        [
            'type_id' => 4,
            'name' => 'Is a Free Service',
        ]
    ];
}