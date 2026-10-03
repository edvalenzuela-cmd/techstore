<section class="card receipt">
    <h2>Order Receipt <small>#<?= e($receipt['ref']) ?></small></h2>
    <table>
        <thead><tr><th>Item</th><th>Qty</th><th>Unit price</th><th>Line total</th></tr></thead>
        <tbody>
        <?php foreach ($receipt['lines'] as $line): ?>
            <tr>
                <td><?= e($line['name']) ?></td>
                <td><?= e((string) $line['qty']) ?></td>
                <td><?= e(format_peso($line['price'])) ?></td>
                <td><?= e(format_peso($line['total'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <dl class="totals">
        <dt>Subtotal</dt><dd><?= e(format_peso($receipt['subtotal'])) ?></dd>
        <dt>
            Discount (<?= e(number_format($receipt['total_rate'] * 100, 0)) ?>%)
            <?php if ($receipt['code'] !== ''): ?>
                <small>code <?= e($receipt['code']) ?></small>
            <?php endif; ?>
        </dt>
        <dd>-<?= e(format_peso($receipt['discount'])) ?></dd>
        <dt>VAT (12%)</dt><dd><?= e(format_peso($receipt['vat'])) ?></dd>
        <dt class="grand">Total</dt><dd class="grand"><?= e(format_peso($receipt['total'])) ?></dd>
    </dl>
</section>
