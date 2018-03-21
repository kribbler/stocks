<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <?= $this->Form->create($trade) ?>
    <fieldset>
        <legend><?= __('Edit Trade') ?></legend>
        <?php
            echo $this->Form->control('stock_id', ['options' => $stocks]);
            echo $this->Form->control('scrap_date');
            echo $this->Form->control('time', ['empty' => true]);
            echo $this->Form->control('price');
            echo $this->Form->control('quantity');
            echo $this->Form->control('source');
            echo $this->Form->control('buyer');
            echo $this->Form->control('seller');
            echo $this->Form->control('initiator');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
