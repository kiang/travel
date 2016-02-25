<div id="scheduleDayChoose">
    <?php
    if (!empty($schedules)) {
        echo '<ul>';
        foreach ($schedules AS $schedule) {
            echo '<li>' . $this->Html->link($schedule['Schedule']['title'], array($formField, $schedule['Schedule']['id']), array(
                'class' => 'schedule'
            )) . '</li>';
        }
        echo '</ul>';
        echo '<div class="paging">' . $this->element('paginator') . '</div>';
    } elseif (!empty($scheduleDays)) {
        echo '<ul>';
        $count = 1;
        foreach ($scheduleDays AS $key => $val) {
            $val = '第' . $count . '天 - ' . $val;
            echo '<li>' . $this->Html->link($val, '#', array(
                'rel' => $key,
                'class' => 'scheduleDay'
            )) . '</li>';
            ++$count;
        }
        echo '<li>' . $this->Html->link('其他行程', array($formField, 'schedules'), array(
            'class' => 'schedule'
        )) . '</li>';
        echo '</ul>';
    } else {
        echo '...沒有可以選擇的項目';
    }
    ?>
    <script type="text/javascript">
        <!--
        $(function() {
            $('#scheduleDayChoose .paging a').click(function() {
                $('#scheduleDayChoose').load(this.href);
                return false;
            });
            $('#scheduleDayChoose a.schedule').click(function() {
                $('#scheduleDayChoose').load(this.href);
                scheduleHtml = $(this).html();
                return false;
            });
            $('#scheduleDayChoose a.scheduleDay').click(function() {
                scheduleDayHtml = $(this).html();
                selectedScheduleDay = $(this).attr('rel');
                $('#<?php echo $formField; ?>').val(selectedScheduleDay);
                var copySelected = $(this).html() + '<a href="<?php echo $this->Html->url('/schedule_days/choose/' . $formField); ?>" id="scheduleDayChange">(修改)</a>';
                $('#scheduleDayChoose').html(copySelected);
                $('#scheduleDayChange').click(function() {
                    $('#scheduleDayChoose').load(this.href);
                    return false;
                });
                return false;
            });
            scheduleId = '<?php echo isset($scheduleId) ? $scheduleId : '0'; ?>';
            scheduleHtml = '<?php echo isset($scheduleTitle) ? $scheduleTitle : ''; ?>';
        });
        -->
    </script>
</div>