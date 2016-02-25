<div class="ScheduleLines form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>行程細節</legend>
        <?php
        if ($id > 0) {
            echo $this->Form->input('ScheduleLine.id');
        }
        foreach ($belongsToModels AS $key => $model) {
            echo $this->Form->input('ScheduleLine.' . $model['foreignKey'], array(
                'label' => $model['label'],
                'type' => 'select',
                'options' => $$key,
            ));
        }

        echo $this->Form->input('ScheduleLine.transport_name', array(
            'label' => '交通方式',
        ));
        echo $this->Form->input('ScheduleLine.point_name', array(
            'label' => '地點名稱',
        ));
        echo $this->Form->input('ScheduleLine.activity_name', array(
            'label' => '活動',
        ));
        echo $this->Form->input('ScheduleLine.sort', array(
            'label' => '排序',
        ));
        echo $this->Form->input('ScheduleLine.time', array(
            'label' => '時間',
        ));
        echo $this->Form->input('ScheduleLine.note', array(
            'label' => '備註',
        ));
        ?>
    </fieldset>
</div>