<div id="MemberAdminEdit">
    <?php echo $this->Form->create('Member', array('type' => 'file')); ?>
    <div class="editForm"><?php
    echo $this->Html->link(' ', array('action' => 'form', $this->Form->value('Member.id')));
    ?></div>
    <?php echo $this->Form->end('Submit'); ?>
    <script type="text/javascript">
        $(function() {
            $('#MemberAdminEdit div.editForm a').each(function() {
                $(this).parent().load(this.href);
            });
        });
    </script>
</div>