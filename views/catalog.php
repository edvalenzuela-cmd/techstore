<div class="crumbs">HOME &nbsp;&rsaquo;&nbsp; CATALOG<?= $category !== 'all' ? ' &nbsp;&rsaquo;&nbsp; <span>' . e(strtoupper($category)) . '</span>' : '' ?></div>
<h1 class="title"><?= $category === 'all' ? 'CATALOG' : e(strtoupper($category)) ?></h1>

<div class="filterbar">
    <div class="chips">
        <?php foreach ($chips as $chip): ?>
            <a class="chip" href="<?= e($chip['remove']) ?>"><?= e($chip['label']) ?> <i>&times;</i></a>
        <?php endforeach; ?>
        <?php if (!empty($chips)): ?>
            <a class="chip" href="index.php">DELETE ALL <i>&times;</i></a>
        <?php endif; ?>
    </div>
    <div class="sort">
        SORT BY:
        <a href="<?= e(build_query(['sort' => 'price_asc'])) ?>" class="<?= $sort === 'price_asc' ? 'on' : '' ?>">PRICE &uarr;</a>
        <a href="<?= e(build_query(['sort' => 'price_desc'])) ?>" class="<?= $sort === 'price_desc' ? 'on' : '' ?>">PRICE &darr;</a>
        <a href="<?= e(build_query(['sort' => 'name'])) ?>" class="<?= $sort === 'name' ? 'on' : '' ?>">NAME</a>
    </div>
</div>

<div class="layout">
    <aside class="sidebar">
        <h3>FILTER</h3>
        <form method="get" action="index.php">
            <input type="hidden" name="sort" value="<?= e($sort) ?>">
            <input type="hidden" name="q" value="<?= e($q) ?>">

            <div class="fgroup">
                <div class="flabel">PRICE (&#8369;)</div>
                <div class="range">
                    <input type="text" name="min" placeholder="From" value="<?= $minPrice !== false ? e((string) $minPrice) : '' ?>">
                    <input type="text" name="max" placeholder="To" value="<?= $maxPrice !== false ? e((string) $maxPrice) : '' ?>">
                </div>
            </div>

            <div class="fgroup">
                <div class="flabel">CATEGORY</div>
                <label class="check"><input type="radio" name="category" value="all" <?= $category === 'all' ? 'checked' : '' ?>> All</label>
                <?php foreach (CATEGORIES as $cat): ?>
                    <label class="check"><input type="radio" name="category" value="<?= e($cat) ?>" <?= $category === $cat ? 'checked' : '' ?>> <?= e($cat) ?></label>
                <?php endforeach; ?>
            </div>

            <div class="fgroup">
                <div class="flabel">AVAILABILITY</div>
                <label class="check"><input type="checkbox" name="instock" value="1" <?= $inStock ? 'checked' : '' ?>> In stock only</label>
            </div>

            <button type="submit" class="btn-outline">APPLY</button>
        </form>
    </aside>

    <section class="results">
        <?php if (!empty($checkoutErrors)): ?>
            <div class="alert err">
                <?php foreach ($checkoutErrors as $msg): ?>
                    <div><?= e($msg) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php">
            <input type="hidden" name="action" value="checkout">

            <?php if (empty($visible)): ?>
                <p class="empty">No products match your filters.</p>
            <?php else: ?>
                <div class="grid">
                <?php foreach ($visible as $key => $product): ?>
                    <?php $status = get_stock_status($product['stock']); ?>
                    <article class="product <?= $status === 'Out of stock' ? 'soldout' : '' ?>">
                        <h3><?= e($product['name']) ?></h3>
                        <?php if (!empty($product['new'])): ?><span class="newbadge">NEW</span><?php endif; ?>

                        <div class="pimgwrap"><img src="<?= e(product_image_src($product)) ?>" alt="<?= e($product['name']) ?>" class="pimg"></div>

                        <div class="meta">
                            <?= e($product['category']) ?> / <?= e($status) ?><?= $status === 'Out of stock' ? '' : ' (' . e((string) $product['stock']) . ')' ?> / No. <?= e((string) next_row_number()) ?>
                        </div>
                        <div class="pfoot">
                            <span class="price"><?= e(format_peso($product['price'])) ?></span>
                            <input type="number" name="qty[<?= e((string) $product['id']) ?>]" min="0"
                                   max="<?= e((string) $product['stock']) ?>" value="0" class="qty" aria-label="Quantity"
                                   <?= $product['stock'] === 0 ? 'disabled' : '' ?>>
                        </div>
                    </article>
                <?php endforeach; ?>
                </div>

                <div class="checkout" id="checkout">
                    <div>
                        <strong>CHECKOUT</strong>
                        <p>Set a quantity on the products you want, add a discount code if you have one, then place your order.</p>
                    </div>
                    <label>DISCOUNT CODE
                        <input type="text" name="code" placeholder="e.g. STUDENT" maxlength="20">
                    </label>
                    <button type="submit" class="btn">PLACE ORDER &nearr;</button>
                </div>
            <?php endif; ?>
        </form>
    </section>
</div>
