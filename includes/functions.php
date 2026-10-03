<?php
declare(strict_types=1);

/*
 * MODULE: Functions & Math (Member 4)
 * User-defined functions with type hints, pass-by-reference, static variable,
 * and the discount / VAT formulas.
 */

// Escape output (XSS defense) - used on EVERY rendered value.
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function format_peso(float $amount): string
{
    return '₱' . number_format($amount, 2);
}

// Multi-way branching with match(true)
function get_stock_status(int $stock): string
{
    return match (true) {
        $stock === 0 => 'Out of stock',
        $stock <= 5  => 'Low stock',
        default      => 'In stock',
    };
}

// Volume discount tier based on subtotal
function get_volume_discount_rate(float $subtotal): float
{
    if ($subtotal >= 50000) {
        return 0.10;
    } elseif ($subtotal >= 20000) {
        return 0.05;
    }
    return 0.0;
}

// $cart = [ product_id => quantity ]
function calculate_subtotal(array $products, array $cart): float
{
    $subtotal = 0.0;
    foreach ($cart as $id => $qty) {
        $price = $products[$id]['price'] ?? 0.0;
        $subtotal += $price * $qty;
    }
    return $subtotal;
}

/*
 * Formula:
 *   discount_rate = min(volume_rate + code_rate, 0.20)   (capped at 20%)
 *   discount      = subtotal * discount_rate
 *   vat           = (subtotal - discount) * 0.12
 *   total         = (subtotal - discount) + vat
 */
function build_receipt(array $products, array $cart, string $code): array
{
    $subtotal    = calculate_subtotal($products, $cart);
    $volumeRate  = get_volume_discount_rate($subtotal);
    $codeRate    = DISCOUNT_CODES[$code] ?? 0.0;
    $totalRate   = min($volumeRate + $codeRate, 0.20);
    $discount    = $subtotal * $totalRate;
    $taxable     = $subtotal - $discount;
    $vat         = $taxable * VAT_RATE;

    $lines = [];
    $summary = '';
    foreach ($cart as $id => $qty) {
        $lines[] = [
            'name'  => $products[$id]['name'],
            'qty'   => $qty,
            'price' => $products[$id]['price'],
            'total' => $products[$id]['price'] * $qty,
        ];
        $summary .= $qty . 'x ' . $products[$id]['name'] . '; ';
    }

    return [
        'ref'         => 'TS-' . strtoupper(substr(uniqid(), -6)),
        'lines'       => $lines,
        'summary'     => rtrim($summary, '; '),
        'subtotal'    => $subtotal,
        'volume_rate' => $volumeRate,
        'code'        => $code,
        'code_rate'   => $codeRate,
        'total_rate'  => $totalRate,
        'discount'    => $discount,
        'vat'         => $vat,
        'total'       => $taxable + $vat,
    ];
}

// PASS-BY-REFERENCE: modifies the original products array (no return needed)
function deduct_stock(array &$products, array $cart): void
{
    foreach ($cart as $id => $qty) {
        $products[$id]['stock'] -= $qty;
    }
}

// STATIC VARIABLE: keeps its value between calls (row numbering in the table)
function next_row_number(): int
{
    static $count = 0;
    return ++$count;
}

// Sort products by field using the spaceship operator (<=>)
function sort_products(array $products, string $sort): array
{
    usort($products, function (array $a, array $b) use ($sort): int {
        return match ($sort) {
            'price_desc' => $b['price'] <=> $a['price'],
            'name'       => strcasecmp($a['name'], $b['name']),
            default      => $a['price'] <=> $b['price'], // price_asc
        };
    });
    return $products;
}

// Guess artwork from the product name (works for products added by the user too)
function guess_art(string $name): ?string
{
    $keywords = [
        'cpu' => ['ryzen', 'intel', 'core i', 'cpu', 'processor'],
        'gpu' => ['gpu', 'rtx', 'geforce', 'radeon', 'graphics'],
        'mouse' => ['mouse'],
        'keyboard' => ['keyboard'],
        'monitor' => ['monitor', 'display'],
        'ssd' => ['ssd', 'nvme', 'hard drive', 'hdd'],
        'router' => ['router', 'wifi', 'modem'],
        'headset' => ['headset', 'headphone', 'earphone'],
    ];
    $name = strtolower($name);
    foreach ($keywords as $art => $words) {
        foreach ($words as $word) {
            if (str_contains($name, $word)) {
                return $art;
            }
        }
    }
    return null;
}

// Image shown on a card: uploaded file > real photo in assets/img > SVG artwork > generic box
function product_image_src(array $product): string
{
    if ($product['image'] !== null && is_file(UPLOAD_DIR . $product['image'])) {
        return 'uploads/' . $product['image'];
    }

    $art = $product['art'] ?? guess_art($product['name']) ?? CATEGORY_ART[$product['category']] ?? 'box';

    // Real photos first, then the SVG drawing
    foreach (['png', 'webp', 'jpg', 'jpeg', 'svg'] as $ext) {
        if (is_file(__DIR__ . '/../assets/img/' . $art . '.' . $ext)) {
            return 'assets/img/' . $art . '.' . $ext;
        }
    }
    return 'assets/img/box.svg';
}

// Rebuilds the current URL's query string with changes (null removes a key)
function build_query(array $changes): string
{
    $params = array_merge($_GET, $changes);
    $params = array_filter($params, fn($v): bool => $v !== null && $v !== '' && $v !== 'all');
    return 'index.php' . ($params ? '?' . http_build_query($params) : '');
}