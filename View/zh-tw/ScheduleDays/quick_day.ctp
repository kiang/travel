<?php
if (!empty($scheduleDayMessage)) {
    echo $scheduleDayMessage;
    exit();
}
$baseTime = strtotime($scheduleDay['Schedule']['time_start']);
$weekDays = array(
    1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
);
$theDay = strtotime('+' . ($scheduleDay['ScheduleDay']['sort'] - 1) . ' days', $baseTime);
?>
<h3>快速新增單日行程到 <span><?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>) </span></h3>
<?php
echo $this->Form->create('ScheduleDay', array(
    'url' => array('action' => 'quick_day', $scheduleDayId),
    'id' => 'ScheduleDay' . $scheduleDayId . 'QuickForm'
));
echo $this->Form->input('ScheduleDay.lines', array(
    'type' => 'textarea',
    'label' => false,
    'class' => 'textBox_XL',
    'rows' => 10,
));
?><div class="clear">
    在這個表單裡，可以一次輸入多個行程經過的地點，只要將它們整理為一行一個，送出後就會在這一天產生對應的行程
</div><?php
echo $this->Form->end('送出');
?>
<script type="text/javascript">
    <!--
    $(function() {
        var submitted = false;
        $('form#ScheduleDay<?php echo $scheduleDayId; ?>QuickForm').submit(function() {
            if(false === submitted) {
                submitted = true;
                $.post('<?php echo $this->Html->url(array('action' => 'quick_day', $scheduleDayId)); ?>', $(this).serializeArray(), function(pageData) {
                    $('select#ScheduleDayId').val('<?php echo $scheduleDayId; ?>').trigger('change');
                    $('#dialogFull').dialog('close');
                    submitted = false;
                });
            }
            return false;
        });
    })
    // -->
</script>