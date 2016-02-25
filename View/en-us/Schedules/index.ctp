<div id="scheduleIndexTab">
    <ul>
        <li><?php
echo $this->Html->link('Latest', '/schedules/page_new', array(
    'title' => 'new comments'
));
?></li>
        <li><?php
            echo $this->Html->link('Hot', '/schedules/page_hot', array(
                'title' => 'hot schedules'
            ));
?></li>
        <li><?php
            echo $this->Html->link('Create', '/schedules/add', array(
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