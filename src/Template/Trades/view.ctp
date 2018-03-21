<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <h3><?= h($trade->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Stock') ?></th>
            <td><?= $trade->has('stock') ? $this->Html->link($trade->stock->name, ['controller' => 'Stocks', 'action' => 'view', $trade->stock->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Source') ?></th>
            <td><?= h($trade->source) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Buyer') ?></th>
            <td><?= h($trade->buyer) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Seller') ?></th>
            <td><?= h($trade->seller) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Initiator') ?></th>
            <td><?= h($trade->initiator) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($trade->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($trade->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Quantity') ?></th>
            <td><?= $this->Number->format($trade->quantity) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Scrap Date') ?></th>
            <td><?= h($trade->scrap_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time') ?></th>
            <td><?= h($trade->time) ?></td>
        </tr>
    </table>
</div>
