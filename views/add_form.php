<section class="card">
    <h2>Add a Product</h2>

    <?php if (!empty($uploadErrors)): ?>
        <div class="alert err">
            <?php foreach ($uploadErrors as $msg): ?>
                <div><?= e($msg) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="index.php" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="add_product">

        <div class="field">
            <label for="name">Product name</label>
            <input type="text" id="name" name="name" value="<?= e($old['name']) ?>">
            <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
        </div>

        <div class="field">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">-- choose --</option>
                <?php foreach (CATEGORIES as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= $old['category'] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['category'])): ?><span class="err"><?= e($errors['category']) ?></span><?php endif; ?>
        </div>

        <div class="row">
            <div class="field">
                <label for="price">Price (₱)</label>
                <input type="text" id="price" name="price" value="<?= e($old['price']) ?>">
                <?php if (isset($errors['price'])): ?><span class="err"><?= e($errors['price']) ?></span><?php endif; ?>
            </div>
            <div class="field">
                <label for="stock">Stock</label>
                <input type="text" id="stock" name="stock" value="<?= e($old['stock']) ?>">
                <?php if (isset($errors['stock'])): ?><span class="err"><?= e($errors['stock']) ?></span><?php endif; ?>
            </div>
        </div>

        <div class="field">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="3"><?= e($old['description']) ?></textarea>
            <?php if (isset($errors['description'])): ?><span class="err"><?= e($errors['description']) ?></span><?php endif; ?>
        </div>

        <div class="field">
            <label for="image">Product image (JPG/PNG/WEBP, max 2 MB)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <button type="submit">Add product</button>
    </form>

    <form method="post" action="index.php" class="reset">
        <input type="hidden" name="action" value="reset">
        <button type="submit" class="secondary">Reset catalog</button>
    </form>
</section>
