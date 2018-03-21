<?php
/**
  * @var \App\View\AppView $this
  */
?>

<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <h3><?= __('Trades') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('stock_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('file_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('scrap_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('quantity') ?></th>
                <th scope="col"><?= $this->Paginator->sort('source') ?></th>
                <th scope="col"><?= $this->Paginator->sort('buyer') ?></th>
                <th scope="col"><?= $this->Paginator->sort('seller') ?></th>
                <th scope="col"><?= $this->Paginator->sort('initiator') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trades as $trade): ?>
            <tr>
                <td><?= $this->Number->format($trade->id) ?></td>
                <td><?= $trade->has('stock') ? $this->Html->link($trade->stock->name, ['controller' => 'Stocks', 'action' => 'view', $trade->stock->id]) : '' ?></td>
                <td><?= $trade->has('file') ? $this->Html->link($trade->file->name, ['controller' => 'Files', 'action' => 'view', $trade->file->id]) : '' ?></td>
                <td><?= h($trade->scrap_date) ?></td>
                <td><?= h($trade->time) ?></td>
                <td><?= $this->Number->format($trade->price) ?></td>
                <td><?= $this->Number->format($trade->quantity) ?></td>
                <td><?= h($trade->source) ?></td>
                <td><?= h($trade->buyer) ?></td>
                <td><?= h($trade->seller) ?></td>
                <td><?= h($trade->initiator) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $trade->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $trade->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $trade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $trade->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
