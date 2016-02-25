<div class="ScheduleTasks form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>待轉行程</legend>
        <?php
        if ($id > 0) {
            echo $this->Form->input('ScheduleTask.id');
        }
        echo '<div class="span-3">網址</div>' .
        $this->Form->input('ScheduleTask.url', array(
            'label' => false,
            'div' => 'span-18',
            'class' => 'span-18',
        )) . '<hr />';
        echo '<div class="span-3">標題</div>' .
        $this->Form->input('ScheduleTask.title', array(
            'label' => false,
            'div' => 'span-18',
            'class' => 'span-18',
        )) . '<hr />';
        echo '<div class="span-3">行程</div>' .
        $this->Form->input('ScheduleTask.schedule_id', array(
            'label' => false,
            'div' => 'span-18',
            'class' => 'span-18',
        )) . '<hr />';
        ?>
    </fieldset>
</div>