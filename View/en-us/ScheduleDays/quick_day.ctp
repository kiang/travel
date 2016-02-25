<?php
if (!empty($scheduleDayMessage)) {
    echo $scheduleDayMessage;
    exit();
}
$baseTime = strtotime($scheduleDay['Schedule']['time_start']);
$weekDays = array(
    1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'
);
$theDay = strtotime('+' . ($scheduleDay['ScheduleDay']['sort'] - 1) . ' days', $baseTime);
?>
<h3>Quickly add multiple lines to  <span><?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>) </span></h3>
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
    You could input multiple lines in this form. One line as one point in the itinerary after submiting.
</div><?php
echo $this->Form->end('Submit');
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