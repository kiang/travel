<?php echo $this->Form->create('Area', array('url' => array('action' => 'add', $parentId))); ?>
<div class="addForm"><?php echo $this->Html->link(' ', array('action' => 'form')); ?></div>
<?php echo $this->Form->end('送出'); ?>
<script type="text/javascript">
    $(function() {
        $('div.addForm a').each(function() {
            $(this).parent().load(this.href);
        });
    });
</script>