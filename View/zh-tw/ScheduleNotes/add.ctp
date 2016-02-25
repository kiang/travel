<div class="scheduleNotesAdd">
    <?php echo $this->Form->create('ScheduleNote', array('url' => $formAction)); ?>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">標題</label>
            <?php
            echo $this->Form->input('ScheduleNote.title', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span4',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span3">
            <label class="add-on span1">快選標題</label>
            <?php
            $options = array('注意事項', '消費方式', '招牌菜', '怎麼去', '必看', '必買', '預算');
            $listOptions = array();
            foreach ($options AS $option) {
                $listOptions[$option] = $option;
            }
            echo $this->Form->select('title_list', $listOptions, array('class' => 'span2'));
            ?>
        </div>
        <?php
        ?>
    </div>
    <div class="clearfix"></div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">內容</label>
            <?php
            echo $this->Form->input('ScheduleNote.body', array(
                'type' => 'textarea',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div>
        <div class="span1"><input type="button" class="btn btn-primary span1 noteSubmit" value="送出" /></div>
        <div class="span5">* 內容最多 255 字，超過的部份會自動截斷！</div>
    </div>
    <?php echo $this->Form->end(); ?>
    <script>
        $(function() {
            $('select#ScheduleNoteTitleList').change(function() {
                $('input#ScheduleNoteTitle').val($(this).val());
            });
            $('input.noteSubmit').click(function() {
                var theForm = $('form#ScheduleNoteAddForm');
                $.post(theForm.attr('action'), theForm.serializeArray(), function(result) {
                    if(result === 'ok') {
                        $('#dialogFull').dialog('close');
<?php if ($dayId > 0) { ?>
                        $('select#ScheduleDayId').trigger('change');
<?php } else { ?>
                        $('div.scheduleNotesSchedule').parent().load(wwwRoot + 'schedule_notes/schedule/<?php echo $scheduleId; ?>');
<?php } ?>
                } else {
                    $('div.scheduleNotesAdd').parent().html(result);
                }
            });
            return false;
        });
    })
    </script>
</div>