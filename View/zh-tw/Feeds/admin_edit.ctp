<div class="feeds form">
    <?php echo $this->Form->create('Feed'); ?>
    <fieldset>
        <legend><?php echo __('Admin Edit Feed'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('is_active');
        echo $this->Form->input('title');
        echo $this->Form->input('url');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>