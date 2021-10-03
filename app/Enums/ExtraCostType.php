<?php 

namespace App\Enums;

class ExtraCostType {
    const TYPES = [
        [
            'type_id' => 1,
            'name' => 'No, I will not charge any cost',
        ],
        [
            'type_id' => 2,
            'name' => 'Yes, I will charge a fix price',
        ],
        [
            'type_id' => 3,
            'name' => 'Yes, I will charge a cost per kilometer',
        ],
    ];
}