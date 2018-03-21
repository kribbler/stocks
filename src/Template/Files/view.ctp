<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <h3><?= h($file->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($file->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($file->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($file->date) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Trades') ?></h4>
        <?php if (!empty($file->trades)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Stock Id') ?></th>
                <th scope="col"><?= __('File Id') ?></th>
                <th scope="col"><?= __('Scrap Date') ?></th>
                <th scope="col"><?= __('Time') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Quantity') ?></th>
                <th scope="col"><?= __('Source') ?></th>
                <th scope="col"><?= __('Buyer') ?></th>
                <th scope="col"><?= __('Seller') ?></th>
                <th scope="col"><?= __('Initiator') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($file->trades as $trades): ?>
            <tr>
                <td><?= h($trades->id) ?></td>
                <td><?= h($trades->stock_id) ?></td>
                <td><?= h($trades->file_id) ?></td>
                <td><?= h($trades->scrap_date) ?></td>
                <td><?= h($trades->time) ?></td>
                <td><?= h($trades->price) ?></td>
                <td><?= h($trades->quantity) ?></td>
                <td><?= h($trades->source) ?></td>
                <td><?= h($trades->buyer) ?></td>
                <td><?= h($trades->seller) ?></td>
                <td><?= h($trades->initiator) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Trades', 'action' => 'view', $trades->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Trades', 'action' => 'edit', $trades->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Trades', 'action' => 'delete', $trades->id], ['confirm' => __('Are you sure you want to delete # {0}?', $trades->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
