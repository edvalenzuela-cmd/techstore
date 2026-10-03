<?php
declare(strict_types=1);

/*
 * MODULE: Form Validation (Member 1)
 * Server-side validation with empty(), trim(), filter_var().
 * Returns an array of error messages keyed by field name.
 */

function validate_product(array $input): array
{
    $errors = [];

    $name  = trim($input['name'] ?? '');
    $price = trim($input['price'] ?? '');
    $stock = trim($input['stock'] ?? '');
    $cat   = trim($input['category'] ?? '');
    $desc  = trim($input['description'] ?? '');

    // Name
    if (empty($name)) {
        $errors['name'] = 'Product name is required.';
    } elseif (strlen($name) < 3 || strlen($name) > 60) {
        $errors['name'] = 'Name must be 3-60 characters.';
    }

    // Price (float > 0)
    if ($price === '') {
        $errors['price'] = 'Price is required.';
    } else {
        $p = filter_var($price, FILTER_VALIDATE_FLOAT);
        if ($p === false || $p <= 0 || $p > 1000000) {
            $errors['price'] = 'Enter a valid price between 0.01 and 1,000,000.';
        }
    }

    // Stock (int >= 0)
    if ($stock === '') {
        $errors['stock'] = 'Stock is required.';
    } elseif (filter_var($stock, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 9999]]) === false) {
        $errors['stock'] = 'Stock must be a whole number from 0 to 9999.';
    }

    // Category must be one of the allowed values
    if (!in_array($cat, CATEGORIES, true)) {
        $errors['category'] = 'Please choose a valid category.';
    }

    // Description (optional)
    if (strlen($desc) > 200) {
        $errors['description'] = 'Description must be 200 characters or fewer.';
    }

    return $errors;
}

// Validates the checkout form. Returns [errors, cart, code]
function validate_checkout(array $input, array $products): array
{
    $errors = [];
    $cart = [];

    foreach ($input['qty'] ?? [] as $id => $qty) {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false || !isset($products[$id])) {
            continue;
        }
        $qty = trim((string) $qty);
        if ($qty === '' || $qty === '0') {
            continue;
        }
        $max = $products[$id]['stock'];
        $q = filter_var($qty, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => max($max, 1)]]);
        if ($q === false || $max === 0) {
            $errors[] = 'Invalid quantity for "' . $products[$id]['name'] . '" (available: ' . $max . ').';
        } else {
            $cart[$id] = $q;
        }
    }

    if (empty($cart) && empty($errors)) {
        $errors[] = 'Add at least one item to your cart.';
    }

    $code = strtoupper(trim($input['code'] ?? ''));
    if ($code !== '' && !array_key_exists($code, DISCOUNT_CODES)) {
        $errors[] = 'Discount code "' . $code . '" is not valid.';
    }

    return [$errors, $cart, $code];
}
