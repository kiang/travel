<ul class="steps_menu">
    <li class="fields_4 importSteps step1 current"> <strong>Step1</strong>
        <p>選擇資料來源</p>
    </li>
    <li class="fields_4 importSteps step2"> <strong>Step2</strong>
        <p>選擇匯出位置</p>
    </li>
    <li class="fields_4 importSteps step3"> <strong>Step3</strong>
        <p>確認匯出資訊</p>
    </li>
    <li class="fields_4 importSteps step4"> <strong>Step4</strong>
        <p>完成</p>
    </li>
</ul>
<?php echo $this->Form->create('Schedule', array('url' => array('action' => 'import', $fromSchedule['Schedule']['id']))); ?>
<div id="importStep1" class="importBlocks">
    <p class="clearfix"></p>
    <h2 class="title">選擇資料來源</h2>
    <p>
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="2" value="下一步" />
    </p>
    <h3 class="title"><?php echo $fromSchedule['Schedule']['title']; ?></h3>
    <ul class="list2 txt_N">
        <li class="dTable">
            <div class="table-cell_center table_td_5p bg_gary1">
                <input name="" type="checkbox" class="importCheckAll" checked="checked" title="全部" />
            </div>
            <div class="table-cell_center table_td_15p bg_gary1">日期</div>
            <div class="table-cell_center bg_gary1">日程內容</div>
        </li>
        <?php
        $i = 1;
        $baseTime = strtotime($fromSchedule['Schedule']['time_start']);
        $weekDays = array(
            1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
        );
        foreach ($fromSchedule['ScheduleDay'] AS $scheduleDay) {
            $theDay = strtotime('+' . ($i - 1) . ' days', $baseTime);
            ?>
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td_5p">
                    <input type="checkbox" name="data[Schedule][days][]" checked="checked" class="importFromDay" value="<?php echo $scheduleDay['id']; ?>" />
                </div>
                <div class="table-cell_center table-cell_middle table_td_15p">第 <?php echo $i; ?> 天<br />
                    <span class="color1a"><?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>)</span></div>
                <div class="table-cell_middle"><?php
        if (!empty($scheduleDay['title'])) {
            echo $scheduleDay['title'] . '<br />';
        }
            ?>
                    <span class="color1a"><?php
                echo $scheduleDay['summary'];
            ?></span></div>
            </li>
            <?php
            ++$i;
        }
        ?>

    </ul>
    <p>
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="2" value="下一步" />
    </p>
    <p class="clearfix"></p>
</div>
<p class="clearfix"></p>
<div id="importStep2" class="importBlocks">
    <h2 class="title">選擇匯出位置</h2>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="上一步" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="下一步" />
    </p>
    <div id="scheduleImportList"></div>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="上一步" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="下一步" />
    </p>
    <p class="clearfix"></p>
</div>
<div id="importStep3" class="importBlocks">
    <h2 class="title">確認匯出資訊</h2>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="2" value="上一步" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="4" value="下一步" />
    </p>
    <h3 class="title">資料來源</h3>
    <ul class="list2 txt_N listImportFrom">
        <li class="dTable">
            <div class="table-cell_center table_td_15p bg_gary1">日期</div>
            <div class="table-cell_center bg_gary1">日程內容</div>
        </li>
    </ul>
    <p class="clearfix"></p>
    <h3 class="title">匯出位置</h3>
    <ul class="list2 txt_N listImportTo">
        <li class="dTable">
            <div class="table-cell_center bg_gary1">行程名稱</div>
        </li>
    </ul>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="2" value="上一步" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="4" value="下一步" />
    </p>
    <p class="clearfix"></p>
</div>
<div id="importStep4" class="importBlocks">
    <h2 class="title">完成</h2>
    <p>
    <p>立即前往編輯：<a href="#" class="mark_txt" id="importResult"></a></p>
    <p>回到行程：<?php echo $this->Html->link($fromSchedule['Schedule']['title'], '/schedules/view/' . $fromSchedule['Schedule']['id']); ?></p>
    <p class="clearfix"></p>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(function() {
        $('input.stepButtons').click(function() {
            var targetStep = $(this).attr('rel');
            $('div.importBlocks').hide();
            $('div#importStep' + targetStep).show(0, function() {
                if(3 == targetStep) {
                    $('input.importFromDay').trigger('change');
                }
                if(4 == targetStep) {
                    $.post('<?php echo $this->Html->url('/schedules/import/' . $fromSchedule['Schedule']['id']); ?>', $(this).parents('form').serializeArray());
                }
            });
            $('li.importSteps').removeClass('current');
            $('li.step' + targetStep).addClass('current');
            
        });
        $('input.importFromDay').change(function() {
            $('.itemImportFrom').remove();
            var target = $('ul.listImportFrom');
            $('input.importFromDay').each(function() {
                if('checked' === $(this).attr('checked')) {
                    var appendContent = $(this).parent().parent().clone();
                    $('div :first', appendContent).parent().remove();
                    appendContent.addClass('itemImportFrom').appendTo(target);
                }
            });
        });
        $('input.importCheckAll').change(function() {
            var checkStatus = $(this).attr('checked') === 'checked';
            $('input.importFromDay').attr('checked', checkStatus);
        });
        $('div.importBlocks').hide();
        $('div#importStep1').show();
        $('div#scheduleImportList').load('<?php echo $this->Html->url('/schedules/import_list'); ?>');
    });
    -->
</script>