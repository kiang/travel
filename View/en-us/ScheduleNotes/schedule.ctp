<div class="scheduleNotesSchedule">
    <?php
    if ($schedule['Schedule']['member_id'] == $loginMember['id']) {
        echo $this->Html->link('<i class="icon-leaf"></i> Add note', "/schedule_notes/add/{$schedule['Schedule']['id']}", array(
            'title' => 'Add extra notes to this schedule',
            'class' => 'btn dialogScheduleNote',
            'escape' => false,
        )) . '<hr />';
    }
    foreach ($notes AS $note) {
        echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['ScheduleNote']['title'] . ']</span><span class="color2b">' . $note['ScheduleNote']['body'] . '</span>';
        if ($note['ScheduleNote']['member_id'] == $loginMember['id']) {
            echo ' &nbsp; ' . $this->Html->link('<i class="icon-remove"></i>', '/schedule_notes/delete/' . $note['ScheduleNote']['id'], array(
                'escape' => false,
                'class' => 'scheduleNotesDelete'
            ));
        }
    }
    ?>
    <script type="text/javascript">
        $(function() {
            $('a.dialogScheduleNote').click(function() {
                dialogFull(this);
                return false;
            });
            $('a.scheduleNotesDelete').click(function() {
                $.get(this.href, {}, function(result) {
                    if(result === 'ok') {
                        $('div.scheduleNotesSchedule').parent().load(wwwRoot + 'schedule_notes/schedule/<?php echo $schedule['Schedule']['id']; ?>');
                    }
                });
                return false;
            });
        })
    </script>
</div>