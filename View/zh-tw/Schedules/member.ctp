<?php if ($offset === 0) { ?>
    <div id="scheduleMemberMain">
        <div class="clearfix"></div>
        <ul class="list2">
            <li class="dTable">
                <div class="table-cell_center table-cell_middle bg_gary1">行程標題</div>
                <div class="table-cell_center table-cell_middle table_td_15p bg_gary1">更新時間</div>
                <div class="table-cell_center table-cell_middle table_td_10p bg_gary1">點閱率</div>
            </li>
        </ul>
    <?php } ?>
    <ul class="list2">
        <?php
        if (!empty($items)) {
            foreach ($items as $item) {
                ?>
                <li class="dTable">
                    <div class="table-cell_middle"><?php
                    if(!empty($item['Schedule']['is_draft'])) {
                        echo '[草稿]';
                    }
        echo $this->Html->link($item['Schedule']['title'], '/schedules/view/' . $item['Schedule']['id'], array(
            'title' => '瀏覽行程詳細內容'
        ));
                ?></div>
                    <div class="table-cell_center table-cell_middle table_td_15p"><span class="txt_S color1b"><?php
                echo $item['Schedule']['modified'];
                ?></span></div>
                    <div class="table-cell_center table-cell_middle table_td_10p"><?php
                    echo $item['Schedule']['count_views'];
                ?></div>
                </li>
                <?php
            }
        }
        ?>
    </ul>
    <?php if ($offset === 0) { ?>
    </div>
    <div class="clearfix"></div>
    <p><a class="dbtn dbtn3 fillet_all" href="#" id="scheduleMemberMore">瀏覽更多內容 &gt;&gt;</a></p>
    <script type="text/javascript">
        <!--
        $(function() {
            var scheduleMemberOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#scheduleMemberMore').click(function() {
                scheduleMemberOffset += 10;
                $.get('<?php echo $this->Html->url($url); ?>/' + scheduleMemberOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#scheduleMemberMore').hide();
                    } else {
                        $('div#scheduleMemberMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
            $('a.scheduleEdit').click(function() {
                dialogFull(this, '編輯行程');
                return false;
            });
        });
        -->
    </script>
<?php } ?>