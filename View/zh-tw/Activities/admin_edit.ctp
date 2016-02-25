<?php echo $this->Form->create('Activity', array('type' => 'file')); ?>
<div class="editForm"><?php echo $this->Html->link(' ', array('action' => 'form', $id)); ?></div>
<?php echo $this->Form->end('送出'); ?>
<script type="text/javascript">
    $(function() {
        $('div.editForm a').each(function() {
            $(this).parent().load(this.href);
        });
    });
</script>