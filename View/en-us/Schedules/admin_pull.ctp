<?php
echo $this->Form->create('Schedule', array('url' => array('action' => 'pull', 'admin' => true)));
echo $this->Form->input('Schedule.is_draft', array('type' => 'hidden', 'value' => '0'));
?>
<dl class="list4">
    <dt class="bg_gary1">行程基本資訊</dt>

    <dd style="padding: 10px;">
        <div class="control-group input-prepend span8">
            <label class="add-on">行程Title</label>
            <?php
            echo $this->Form->input('Schedule.title', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span8',
                'value' => $record['title'],
            ));
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">出發時間</label>
                <?php
                echo $this->Form->input('Schedule.time_start', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span4',
                    'value' => $record['date'],
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span8">
                <label class="add-on">資料來源</label>
                <?php
                echo $this->Form->input('Schedule.source', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span8',
                    'value' => $record['source'],
                ));
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </dd>
</dl>
<div class="clearfix"></div>
<?php
foreach($record AS $key => $day) {
    if(!is_array($day)) continue;
    ?>
<dl class="list3 table">
    <dt class="table-cell fillet_left">
    <a href="#" class="olcSprite mDot lightbox_map">&nbsp;</a>
    </dt>
    <dd class="table-cell fillet_right">
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">Title of this day</label>
                <?php
                echo $this->Form->input('ScheduleDay.' . $key . '.title', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                    'value' => $day['title'],
                ));
                ?>
            </div>
            <div class="control-group input-prepend span5">
                <label class="add-on">Hotel</label>
                <?php
                echo $this->Form->input('ScheduleDay.' . $key . '.point_name', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                    'value' => $day['hotel'],
                ));
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">行程</label>
                <?php
                echo $this->Form->input('ScheduleDay.' . $key . '.lines', array(
                    'type' => 'textarea',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                    'rows' => 12,
                    'value' => implode("\n", $day['points']),
                ));
                $day['source'] = str_replace(array(',', '.', '，', '。'), "\n", $day['source']);
                ?>
            </div>
            <div class="span5">
                <textarea class="span5" rows="12"><?php echo $day['source']; ?></textarea>
            </div>
        </div>
        <div class="clearfix"></div>
    </dd>
</dl>
<div class="clearfix"></div>
        <?php
}
?>

<div class="float-l">
    <div class="btn-group">
        <a class="btn btn-primary dbtnSubmit" href="#" title="公開發表本行程"><i class="icon-ok icon-white"></i> 發表</a>
        <a class="btn dbtnDraft" href="#" title="僅暫存而不公開本行程"><i class="icon-lock"></i> 暫存</a>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script>
    $(function() {
        $('a.dbtnSubmit').click(function() {
            $('input#ScheduleIsDraft').val('0');
            $(this).parents('form').submit();
            return false;
        });
        $('a.dbtnDraft').click(function() {
            $('input#ScheduleIsDraft').val('1');
            $(this).parents('form').submit();
            return false;
        });
    })
</script>