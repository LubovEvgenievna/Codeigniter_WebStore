
<?php echo form_open('#'); ?>
<div class="table-responsive">
    <table class="cart_table table table-striped table-hover">

        <tr>
            <th>Наименование товара</th>
            <th>Цена за шт.</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>

        <?php $i = 1; ?>

        <?php foreach($this->cart->contents() as $items): ?>

            <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>

            <tr>
                <td><?php echo $items['name']; ?></td>
                <td><?php echo $this->cart->format_number($items['price']); ?>руб.</td>
                <td><?php echo $items['qty'] ?></td>
                <td><?php echo $this->cart->format_number($items['subtotal']); ?>руб.</td>
            </tr>

            <?php $i++; ?>

        <?php endforeach; ?>

        <tr>
            <td><strong>Итого</strong></td>
            <td colspan="2"> </td>
            <td><?php echo $this->cart->format_number($this->cart->total()); ?>руб.</td>
        </tr>

    </table>
</div>
<?php
echo form_close();
?>