
<?php echo form_open('#'); ?>
<div class="table-responsive">
        <table class="cart_table table table-striped table-hover">

            <tr>
                <th>Наименование товара</th>
                <th>Цена за шт.</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th></th>
            </tr>

            <?php $i = 1; ?>

            <?php foreach($this->cart->contents() as $items): ?>

                <?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>

                <tr>
                    <td>
                        <a href="http://hookan.local/product/getProd/<?php echo $items['id'] ?>" target="_blank">
                            <?php echo $items['name']; ?>
                        </a>
                    </td>
                    <td><?php echo $this->cart->format_number($items['price']); ?>руб.</td>
                    <td><?php echo $items['qty'] ?></td>
                    <td><?php echo $this->cart->format_number($items['subtotal']); ?>руб.</td>
                    <td>
                        <a href="http://hookan.local/cart/delete/<?php echo $items['rowid'] ?>">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                </tr>

                <?php $i++; ?>

            <?php endforeach; ?>

            <tr>
                <td><strong>Итого</strong></td>
                <td colspan="2"> </td>
                <td><?php echo $this->cart->format_number($this->cart->total()); ?>руб.</td>
                <td> </td>
            </tr>

        </table>
</div>
<?php
echo form_close();
?>