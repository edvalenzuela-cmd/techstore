<?php
declare(strict_types=1);

/*
 * MODULE: Data (Member 3)
 * Multidimensional associative array = the catalog.
 * Shape: [ id => [ 'id', 'name', 'category', 'price', 'stock', 'description', 'image' ] ]
 */

const CATEGORIES = ['Components', 'Peripherals', 'Displays', 'Storage', 'Networking', 'Audio'];

// Discount code => percentage off (as a decimal)
const DISCOUNT_CODES = [
    'TECH5'   => 0.05,
    'STUDENT' => 0.10,
    'BICOL15' => 0.15,
];

const VAT_RATE = 0.12; // 12% Philippine VAT

function get_seed_catalog(): array
{
    return [
        1 => [
            'id' => 1, 'name' => 'Ryzen 5 7600X Processor', 'category' => 'Components',
            'price' => 13995.00, 'stock' => 12,
            'description' => '6-core, 12-thread AM5 desktop CPU.', 'image' => null, 'art' => 'cpu',
        ],
        2 => [
            'id' => 2, 'name' => 'GeForce RTX 4060 8GB', 'category' => 'Components',
            'price' => 24500.00, 'stock' => 5,
            'description' => 'Dual-fan graphics card for 1080p gaming.', 'image' => null, 'art' => 'gpu',
        ],
        3 => [
            'id' => 3, 'name' => 'Wireless Gaming Mouse', 'category' => 'Peripherals',
            'price' => 2995.00, 'stock' => 30,
            'description' => 'Lightweight, 2.4GHz + Bluetooth.', 'image' => null, 'art' => 'mouse',
        ],
        4 => [
            'id' => 4, 'name' => 'Mechanical Keyboard 75%', 'category' => 'Peripherals',
            'price' => 5990.00, 'stock' => 3,
            'description' => 'Hot-swappable switches, RGB backlight.', 'image' => null, 'art' => 'keyboard',
        ],
        5 => [
            'id' => 5, 'name' => '27" 165Hz IPS Monitor', 'category' => 'Displays',
            'price' => 11990.00, 'stock' => 8,
            'description' => '1440p, 1ms response, adaptive sync.', 'image' => null, 'art' => 'monitor',
        ],
        6 => [
            'id' => 6, 'name' => '1TB NVMe SSD', 'category' => 'Storage',
            'price' => 4250.00, 'stock' => 0,
            'description' => 'PCIe 4.0, up to 5000MB/s read.', 'image' => null, 'art' => 'ssd',
        ],
    ];
}

// Fallback artwork (assets/img/<name>.svg) when a product has no uploaded image
const CATEGORY_ART = [
    'Components'  => 'cpu',
    'Peripherals' => 'mouse',
    'Displays'    => 'monitor',
    'Storage'     => 'ssd',
    'Networking'  => 'router',
    'Audio'       => 'headset',
];
