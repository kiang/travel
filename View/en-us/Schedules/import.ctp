<ul class="steps_menu">
    <li class="fields_4 importSteps step1 current"> <strong>Step1</strong>
        <p>Source itinerary</p>
    </li>
    <li class="fields_4 importSteps step2"> <strong>Step2</strong>
        <p>Target itinerary</p>
    </li>
    <li class="fields_4 importSteps step3"> <strong>Step3</strong>
        <p>Confirm</p>
    </li>
    <li class="fields_4 importSteps step4"> <strong>Step4</strong>
        <p>Finish</p>
    </li>
</ul>
<?php echo $this->Form->create('Schedule', array('url' => array('action' => 'import', $fromSchedule['Schedule']['id']))); ?>
<div id="importStep1" class="importBlocks">
    <p class="clearfix"></p>
    <h2 class="title">Select a source</h2>
    <p>
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="2" value="Next" />
    </p>
    <h3 class="title"><?php echo $fromSchedule['Schedule']['title']; ?></h3>
    <ul class="list2 txt_N">
        <li class="dTable">
            <div class="table-cell_center table_td_5p bg_gary1">
                <input name="" type="checkbox" class="importCheckAll" checked="checked" title="All" />
            </div>
            <div class="table-cell_center table_td_15p bg_gary1">Date</div>
            <div class="table-cell_center bg_gary1">Content</div>
        </li>
        <?php
        $i = 1;
        $baseTime = strtotime($fromSchedule['Schedule']['time_start']);
        $weekDays = array(
            1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'
        );
        foreach ($fromSchedule['ScheduleDay'] AS $scheduleDay) {
            $theDay = strtotime('+' . ($i - 1) . ' days', $baseTime);
            ?>
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td_5p">
                    <input type="checkbox" name="data[Schedule][days][]" checked="checked" class="importFromDay" value="<?php echo $scheduleDay['id']; ?>" />
                </div>
                <div class="table-cell_center table-cell_middle table_td_15p">Day <?php echo $i; ?> <br />
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
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="2" value="Next" />
    </p>
    <p class="clearfix"></p>
</div>
<p class="clearfix"></p>
<div id="importStep2" class="importBlocks">
    <h2 class="title">Select a target</h2>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="Next" />
    </p>
    <div id="scheduleImportList"></div>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="1" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="3" value="Next" />
    </p>
    <p class="clearfix"></p>
</div>
<div id="importStep3" class="importBlocks">
    <h2 class="title">Confirm</h2>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="2" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="4" value="Next" />
    </p>
    <h3 class="title">Data source</h3>
    <ul class="list2 txt_N listImportFrom">
        <li class="dTable">
            <div class="table-cell_center table_td_15p bg_gary1">Date</div>
            <div class="table-cell_center bg_gary1">Content</div>
        </li>
    </ul>
    <p class="clearfix"></p>
    <h3 class="title">Target</h3>
    <ul class="list2 txt_N listImportTo">
        <li class="dTable">
            <div class="table-cell_center bg_gary1">Itinerary</div>
        </li>
    </ul>
    <p>
        <input type="button" class="dbtn dbtn1 fillet_all stepButtons" rel="2" value="Previous" />
        <input type="button" class="dbtn dbtn2 fillet_all stepButtons" rel="4" value="Next" />
    </p>
    <p class="clearfix"></p>
</div>
<div id="importStep4" class="importBlocks">
    <h2 class="title">Finish</h2>
    <p>
    <p>Continue edit target itinerary: <a href="#" class="mark_txt" id="importResult"></a></p>
    <p>Back to source itinerary: <?php echo $this->Html->link($fromSchedule['Schedule']['title'], '/schedules/view/' . $fromSchedule['Schedule']['id']); ?></p>
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