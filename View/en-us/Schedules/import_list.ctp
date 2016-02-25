<?php if ($offset === 0) { ?>
    <ul class="list2 txt_N" id="scheduleImportListMain">
        <li class="dTable">
            <div class="table-cell_center table_td_5p bg_gary1"></div>
            <div class="table-cell_center bg_gary1">Title</div>
        </li>
    <?php } ?>
    <?php
    foreach ($items as $item) {
        $timeStart = strtotime($item['Schedule']['time_start']);
        $timeEnd = strtotime('+' . ($item['Schedule']['count_days'] - 1) . ' days', $timeStart);
        ?>
        <li class="dTable">
            <div class="table-cell_center table-cell_middle table_td_5p">
                <input type="radio" class="importToDay" name="data[Schedule][to]" value="<?php echo $item['Schedule']['id']; ?>">
            </div>
            <div class="table-cell_middle"> <span class="color1a"><?php
    echo date('Y-m-d', $timeStart);
        ?> ~ <?php
                echo date('Y-m-d', $timeEnd);
        ?></span><br />
                <?php
                echo $this->Html->link($item['Schedule']['title'], array('action' => 'view', $item['Schedule']['id']), array('target' => '_blank'));
                ?></div>
        </li>
        <?php
    }
    ?>
    <?php if ($offset === 0) { ?>
    </ul>
    <div class="clearfix"></div>
    <p><a class="dbtn dbtn3 fillet_all" href="#" id="scheduleImportListMore">More &gt;&gt;</a></p>
    <script type="text/javascript">
        <!--
        $(function() {
            var scheduleImportListOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#scheduleImportListMore').click(function() {
                scheduleImportListOffset += 5;
                $.get('<?php echo $this->Html->url('/schedules/import_list/'); ?>' + scheduleImportListOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#scheduleImportListMore').hide();
                    } else {
                        $('#scheduleImportListMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
            $('input.importToDay').click(function() {
                $('li.itemImportTo').remove();
                var target = $('ul.listImportTo');
                var appendContent = $(this).parent().parent().clone();
                $('div :first', appendContent).parent().remove();
                appendContent.addClass('itemImportTo').appendTo(target);
                $('a#importResult').replaceWith($('a', appendContent));
            });
        });
        -->
    </script>
<?php } ?>