<?php 

namespace App\Enums;

class OnsiteType {
    const TYPES = [
        [
            'type_id' => 1,
            'name' => 'Only On-site',
        ],
        [
            'type_id' => 2,
            'name' => 'Only Off-site',
        ],
        [
            'type_id' => 3,
            'name' => 'On-site and Off-site',
        ],
    ];
}