<div class="Schedules form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>行程</legend>
        <?php
        if ($id > 0) {
            echo $this->Form->input('Schedule.id');
        }
        foreach ($belongsToModels AS $key => $model) {
            echo $this->Form->input('Schedule.' . $model['foreignKey'], array(
                'label' => $model['label'],
                'type' => 'text',
            ));
        }

        echo $this->Form->input('Schedule.title', array(
            'label' => '行程名稱',
        ));
        echo $this->Form->input('Schedule.point_text', array(
            'label' => '出發點',
        ));
        echo $this->Form->input('Schedule.member_name', array(
            'label' => '會員名稱',
        ));
        echo $this->Form->input('Schedule.time_start', array(
            'label' => '出發時間',
        ));
        echo $this->Form->input('Schedule.count_joins', array(
            'label' => '參與人數',
        ));
        if ($id > 0) {
            echo '<div>活動天數：' . $this->request->data['Schedule']['count_days'] . '</div>';
        } else {
            echo $this->Form->input('Schedule.count_days', array(
                'label' => '活動天數',
            ));
        }
        if ($id > 0) {
            echo '<div>建立時間：' . $this->request->data['Schedule']['created'] . '</div>';
            echo '<div>更新時間：' . $this->request->data['Schedule']['modified'] . '</div>';
        }
        ?>
    </fieldset>
</div>