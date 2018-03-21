<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php echo $this->element('left-nav'); ?>
<div class="trades index large-10 medium-9 columns content">
    <?= $this->Form->create($stock) ?>
    <fieldset>
        <legend><?= __('Add Stock') ?></legend>
        <?php
            echo $this->Form->control('name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
