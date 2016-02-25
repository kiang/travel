<div id="block<?php echo $formKey; ?>">
    <div class="span4">
        <?php echo $this->Form->create('Channel', array('id' => 'channelForm' . $formKey)); ?>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">標題</label>
                <?php
                echo $this->Form->input('Channel.title', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3 cTitle',
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">網址</label>
                <?php
                echo $this->Form->input('Channel.url', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3 cUrl',
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">摘要</label>
                <?php
                echo $this->Form->input('Channel.summary', array(
                    'type' => 'textarea',
                    'rows' => 3,
                    'label' => false,
                    'div' => false,
                    'class' => 'span3 cSummary',
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">日期</label>
                <?php
                echo $this->Form->input('Channel.the_date', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3 cDate',
                ));
                ?>
            </div>
        </div>
        <div class="control-group span4" id="lineSpool<?php echo $formKey; ?>">
            <div class="pointBlock">
                <a href="#" class="btn" id="btnPoint<?php echo $formKey; ?>">新增地點</a>
            </div>
            <div class="scheduleBlock">
                <a href="#" class="btn" id="btnSchedule<?php echo $formKey; ?>">新增行程</a>
            </div>
        </div>
        <div class="control-group span4">
            <input type="submit" class="btn span1" value="儲存" />
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="span5 frameSpool" style="height: 500px; overflow-y: scroll; overflow-x: auto;">
        &nbsp;blcok
    </div>
    <script type="text/javascript">
        $(function() {
            $('form#channelForm<?php echo $formKey; ?>').submit(function() {
                $.post('<?php echo $this->Html->url('/admin/channels/add'); ?>', $(this).serializeArray(), function(result) {
                    if(result === 'ok') {
                        $('div#block<?php echo $formKey; ?>').remove();
                    } else {
                        alert(result);
                    }
                });
                return false;
            });
            $('a#btnPoint<?php echo $formKey; ?>').click(function() {
                var newObj = $('#linkPointTemplate<?php echo $formKey; ?>').tmpl();
                var pointIdObj = newObj.find('input.pointId');
                newObj.find('input.pointText').autocomplete({
                    source: '<?php echo $this->Html->url('/points/auto_list/'); ?>',
                    select: function(event, ui) {
                        pointIdObj.val(ui.item.id);
                    }
                });
                newObj.appendTo('div#lineSpool<?php echo $formKey; ?> div.pointBlock');
                return false;
            }).trigger('click');
            $('a#btnSchedule<?php echo $formKey; ?>').click(function() {
                var newObj = $('#linkScheduleTemplate<?php echo $formKey; ?>').tmpl();
                var scheduleIdObj = newObj.find('input.scheduleId');
                newObj.find('input.scheduleText').autocomplete({
                    source: '<?php echo $this->Html->url('/schedules/auto_list/'); ?>',
                    select: function(event, ui) {
                        scheduleIdObj.val(ui.item.id);
                    }
                });
                newObj.appendTo('div#lineSpool<?php echo $formKey; ?> div.scheduleBlock');
                return false;
            }).trigger('click');
        });
    </script>
    <script id="linkPointTemplate<?php echo $formKey; ?>" type="text/x-jquery-tmpl">
        <div class="control-group input-prepend span4">
            <label class="add-on">地點</label>
            <input name="data[ChannelLink][Point][]" class="pointId" type="hidden" />
            <input name="data[PointText][]" class="span3 pointText" type="text" />
        </div>
    </script>
    <script id="linkScheduleTemplate<?php echo $formKey; ?>" type="text/x-jquery-tmpl">
        <div class="control-group input-prepend span4">
            <label class="add-on">行程</label>
            <input name="data[ChannelLink][Schedule][]" class="scheduleId" type="hidden" />
            <input name="data[ScheduleText][]" class="span3 scheduleText" type="text" />
        </div>
    </script>
</div>