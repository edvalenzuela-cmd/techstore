<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechHub - Catalog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t !== 'dark' && t !== 'light') { t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) { document.documentElement.setAttribute('data-theme', 'light'); }
        })();
    </script>
    <link rel="stylesheet" href="assets/style.css?v=<?= e((string) filemtime(__DIR__ . '/../assets/style.css')) ?>">
</head>
<body>
<div class="page">
    <header>
        <div class="topbar">
            <a href="index.php" class="logo">tech<b>hub</b></a>
            <form method="get" action="index.php" class="search">
                <input type="text" name="q" value="<?= e($q) ?>" placeholder="SEARCH" aria-label="Search">
                <button type="submit" aria-label="Search">&nearr;</button>
            </form>
            <span class="cell">PHP &#8369;</span>
            <a href="#checkout" class="cell">CART</a>
            <button type="button" class="toggle" id="themeToggle" aria-label="Toggle dark mode">DARK</button>
        </div>
        <nav class="catrow">
            <a href="<?= e(build_query(['category' => null])) ?>" class="<?= $category === 'all' ? 'active' : '' ?>">ALL</a>
            <?php foreach (CATEGORIES as $cat): ?>
                <a href="<?= e(build_query(['category' => $cat])) ?>" class="<?= $category === $cat ? 'active' : '' ?>"><?= e(strtoupper($cat)) ?></a>
            <?php endforeach; ?>
        </nav>
    </header>

    <?php if ($flash !== null): ?>
        <div class="alert ok"><?= e($flash) ?></div>
    <?php endif; ?>

    <?php if ($receipt !== null): ?>
        <?php require __DIR__ . '/receipt.php'; ?>
    <?php endif; ?>

    <?php require __DIR__ . '/catalog.php'; ?>
    <?php require __DIR__ . '/add_form.php'; ?>

    <footer>
        <div class="foot-cols">
            <div>
                <h4>CATALOG</h4>
                <?php foreach (CATEGORIES as $cat): ?>
                    <a href="<?= e(build_query(['category' => $cat])) ?>"><?= e($cat) ?></a>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>DISCOUNT CODES</h4>
                <?php foreach (DISCOUNT_CODES as $code => $rate): ?>
                    <span><?= e($code) ?> &mdash; <?= e(number_format($rate * 100, 0)) ?>% off</span>
                <?php endforeach; ?>
            </div>
            <div>
                <h4>PRICING</h4>
                <span>5% off orders of &#8369;20,000+</span>
                <span>10% off orders of &#8369;50,000+</span>
                <span>12% VAT added at checkout</span>
            </div>
            <div>
                <h4>ABOUT</h4>
                <span>TechHub online store</span>
                <span>311 Midterm Project</span>
                <span><?= e((string) $catalogCount) ?> products in catalog</span>
            </div>
        </div>
        <div class="foot-bottom">&copy; TechHub. All prices in Philippine peso.</div>
    </footer>
</div>
<script src="assets/theme.js?v=<?= e((string) filemtime(__DIR__ . '/../assets/theme.js')) ?>"></script>
</body>
</html>
