<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <h3><?= h($stock->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($stock->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($stock->id) ?></td>
        </tr>
    </table>
</div>
