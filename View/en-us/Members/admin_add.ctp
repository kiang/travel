<div id="MemberAdminAdd">
    <?php echo $this->Form->create('Member', array('type' => 'file')); ?>
    <div class="editForm"><?php
    echo $this->Html->link(' ', array('action' => 'form'));
    ?></div>
    <?php echo $this->Form->end('Submit'); ?>
    <div class="actions">
        <ul class="list1">
            <li><?php echo $this->Html->link('列表', array('action' => 'index')); ?></li>
        </ul>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#MemberAdminAdd div.editForm a').each(function() {
                $(this).parent().load(this.href);
            });
        });
    </script>
</div>