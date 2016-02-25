<div class="ScheduleDays form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>單日行程</legend>
        <?php
        if ($id > 0) {
            echo $this->Form->input('ScheduleDay.id');
        }
        foreach ($belongsToModels AS $key => $model) {
            echo $this->Form->input('ScheduleDay.' . $model['foreignKey'], array(
                'label' => $model['label'],
                'type' => 'select',
                'options' => $$key,
            ));
        }

        echo $this->Form->input('ScheduleDay.title', array(
            'label' => '名稱',
        ));
        if ($id > 0) {
            echo '<div>行程數量：' . $this->request->data['ScheduleDay']['count_lines'] . '</div>';
        } else {
            echo $this->Form->input('ScheduleDay.count_lines', array(
                'label' => '行程數量',
            ));
        }
        echo $this->Form->input('ScheduleDay.note', array(
            'label' => '備註',
        ));
        echo $this->Form->input('ScheduleDay.point_name', array(
            'label' => '住宿地點',
        ));
        ?>
    </fieldset>
</div>