<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/validation.php';
require __DIR__ . '/includes/upload.php';

// Catalog lives in the session (no database needed)
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = get_seed_catalog();
}

// Flash messages survive exactly one redirect (PRG)
$flash   = $_SESSION['flash'] ?? null;
$receipt = $_SESSION['receipt'] ?? null;
unset($_SESSION['flash'], $_SESSION['receipt']);

$errors = [];          // add-product errors (keyed by field)
$uploadErrors = [];
$checkoutErrors = [];
$old = ['name' => '', 'price' => '', 'stock' => '', 'category' => '', 'description' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_product') {
        // Sticky values: keep what the user typed
        foreach ($old as $field => $default) {
            $old[$field] = trim($_POST[$field] ?? '');
        }

        $errors = validate_product($_POST);
        $uploadErrors = validate_upload($_FILES['image'] ?? []);

        if (empty($errors) && empty($uploadErrors)) {
            $image = save_upload($_FILES['image'] ?? []);
            $newId = max(array_keys($_SESSION['products'])) + 1;

            $_SESSION['products'][$newId] = [
                'id'          => $newId,
                'name'        => $old['name'],
                'category'    => $old['category'],
                'price'       => (float) $old['price'],
                'stock'       => (int) $old['stock'],
                'description' => $old['description'],
                'image'       => $image,
                'new'         => true,
            ];

            $_SESSION['flash'] = 'Product "' . $old['name'] . '" was added to the catalog.';
            header('Location: index.php');   // PRG
            exit;
        }
    } elseif ($action === 'checkout') {
        [$checkoutErrors, $cart, $code] = validate_checkout($_POST, $_SESSION['products']);

        if (empty($checkoutErrors)) {
            $_SESSION['receipt'] = build_receipt($_SESSION['products'], $cart, $code);
            deduct_stock($_SESSION['products'], $cart);   // pass-by-reference
            header('Location: index.php');   // PRG
            exit;
        }
    } elseif ($action === 'reset') {
        unset($_SESSION['products']);
        $_SESSION['flash'] = 'Catalog reset to default products.';
        header('Location: index.php');
        exit;
    }
}

// Filtering / sorting through GET (not sensitive data)
$sort     = $_GET['sort'] ?? 'price_asc';
$category = $_GET['category'] ?? 'all';
$q        = trim($_GET['q'] ?? '');
$minPrice = filter_var($_GET['min'] ?? '', FILTER_VALIDATE_FLOAT);
$maxPrice = filter_var($_GET['max'] ?? '', FILTER_VALIDATE_FLOAT);
$inStock  = ($_GET['instock'] ?? '') === '1';

if ($category !== 'all' && !in_array($category, CATEGORIES, true)) {
    $category = 'all';
}

$visible = array_filter($_SESSION['products'], function (array $p) use ($category, $q, $minPrice, $maxPrice, $inStock): bool {
    if ($category !== 'all' && $p['category'] !== $category) return false;
    if ($q !== '' && stripos($p['name'], $q) === false) return false;
    if ($minPrice !== false && $p['price'] < $minPrice) return false;
    if ($maxPrice !== false && $p['price'] > $maxPrice) return false;
    if ($inStock && $p['stock'] === 0) return false;
    return true;
});
$visible = sort_products($visible, $sort);

// Active-filter chips shown above the grid
$chips = [];
if ($category !== 'all')  $chips[] = ['label' => $category, 'remove' => build_query(['category' => null])];
if ($q !== '')            $chips[] = ['label' => 'Search: ' . $q, 'remove' => build_query(['q' => null])];
if ($minPrice !== false)  $chips[] = ['label' => 'Min ' . format_peso($minPrice), 'remove' => build_query(['min' => null])];
if ($maxPrice !== false)  $chips[] = ['label' => 'Max ' . format_peso($maxPrice), 'remove' => build_query(['max' => null])];
if ($inStock)             $chips[] = ['label' => 'In stock', 'remove' => build_query(['instock' => null])];

$catalogCount = count($_SESSION['products']);

require __DIR__ . '/views/layout.php';
