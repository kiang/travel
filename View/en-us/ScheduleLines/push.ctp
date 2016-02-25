<ul class="steps_menu">
    <li class="fields_4 pushSteps step1 current"> <strong>Step1</strong>
        <p>選擇匯入位置</p>
    </li>
    <li class="fields_4 pushSteps step2"> <strong>Step2</strong>
        <p>確認匯出資訊</p>
    </li>
    <li class="fields_4 pushSteps step3"> <strong>Step3</strong>
        <p>完成</p>
    </li>
</ul>
<?php
echo $this->Form->create('ScheduleLine', array('url' => array('action' => 'push', $foreignModel, $foreignId)));
echo $this->Form->hidden('schedule_day_id');
?>
<div id="pushStep1" class="pushBlocks">
    <h2 class="title">選擇匯入位置</h2>
    <?php echo ' ( ' . $this->Form->checkbox('is_living') . '住宿 ) '; ?>
    <div id="schedulePushList"></div>
    <p>
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="2" value="Next" />
    </p>
    <p class="clearfix"></p>
</div>
<p class="clearfix"></p>
<div id="pushStep2" class="pushBlocks">
    <h2 class="title">確認匯出資訊</h2>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="Next" />
    </p>
    <h3 class="title">資料來源</h3>
    <ul class="list2 txt_N listImportFrom">
        <li class="dTable">
            <div class="table-cell_center bg_gary1">地點</div>
        </li>
        <li><?php echo $foreignTitle; ?></li>
    </ul>
    <p class="clearfix"></p>
    <h3 class="title">匯出位置</h3>
    <ul class="list2 txt_N listImportTo">
        <li class="dTable">
            <div class="table-cell_center bg_gary1">行程名稱</div>
        </li>
    </ul>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="Next" />
    </p>
    <p class="clearfix"></p>
</div>
<div id="pushStep3" class="pushBlocks">
    <h2 class="title">完成</h2>
    <p>
    <p>立即前往編輯：<a href="#" class="mark_txt" id="pushResult"></a></p>
    <p>回到地點：<?php echo $this->Html->link($foreignTitle, '/points/view/' . $foreignId); ?></p>
    <p class="clearfix"></p>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    var scheduleId = '';
    var scheduleHtml = '';
    var scheduleDayHtml = '';
    $(function() {
        $('input.stepButtons').click(function() {
            var targetStep = $(this).attr('rel');
            if(2 == targetStep && '' == $('#ScheduleLineScheduleDayId').val()) {
                alert('請先選擇一個行程中的某一天');
                return false;
            }
            $('div.pushBlocks').hide();
            $('div#pushStep' + targetStep).show(0, function() {
                if(2 == targetStep) {
                    $('.listImportToResult').remove();
                    $('.listImportTo').append($('<li />').addClass('listImportToResult').html(scheduleHtml + scheduleDayHtml));
                }
                if(3 == targetStep) {
                    $.post('<?php echo $this->Html->url('/schedule_lines/push/' . $foreignModel . '/' . $foreignId); ?>', $(this).parents('form').serializeArray());
                }
            });
            $('li.pushSteps').removeClass('current');
            $('li.step' + targetStep).addClass('current');
            $('a#pushResult')
            .html(scheduleHtml + scheduleDayHtml)
            .attr('href', '<?php echo $this->Html->url('/schedules/view/'); ?>' + scheduleId + '/' + $('#ScheduleLineScheduleDayId').val());
        });
        $('div.pushBlocks').hide();
        $('div#pushStep1').show();
        $('div#schedulePushList').load('<?php echo $this->Html->url('/schedule_days/choose/ScheduleLineScheduleDayId/'); ?>');
    });
    -->
</script>
<?php
return;
if (!empty($foreignModel) && !empty($foreignId)) {
    echo $this->Form->create('ScheduleLineLine', array(
        'url' => array(
            'controller' => 'schedule_lines',
            'action' => 'push',
            $foreignModel, $foreignId
        )
    ));
    echo '<h3>' . $foreignTitle . '</h3>';
    echo $this->Form->hidden('schedule_day_id', array('value' => 0));
    echo ' ( ' . $this->Form->checkbox('is_living') . '住宿 ) ';
    echo ' 加入到 ... ';
    echo '<div id="selectDay"></div>';
    echo $this->Form->end('確定');
    echo $this->Html->scriptBlock('$(function() {
	$(\'#selectDay\').load(\'' . $this->Html->url('/schedule_days/choose/ScheduleLineLineScheduleLineDayId/') . '\');
})');
}