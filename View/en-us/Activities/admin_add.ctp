<?php echo $this->Form->create('Activity', array('type' => 'file')); ?>
<div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'form')); ?></div>
<?php echo $this->Form->end('Submit'); ?>
<script type="text/javascript">
    $(function() {
        $('div.addForm a').each(function() {
            $(this).parent().load(this.href);
        });
    });
</script>