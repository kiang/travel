<div id="scheduleIndexTab">
    <ul>
        <li><?php
echo $this->Html->link('最新行程', '/schedules/page_new', array(
    'title' => 'new comments'
));
?></li>
        <li><?php
            echo $this->Html->link('熱門行程', '/schedules/page_hot', array(
                'title' => 'hot schedules'
            ));
?></li>
        <li><?php
            echo $this->Html->link('建立行程', '/schedules/add', array(
                'title' => 'create a schedule'
            ));
?></li>
    </ul>        
</div>
<script type="text/javascript">
    <!--
    $(schedulesIndex);
    // -->
</script>
<?php
$this->Html->script(array('co/schedules/index'), array('inline' => false));
$this->Html->script(array('co/schedules/add'), array('inline' => false));