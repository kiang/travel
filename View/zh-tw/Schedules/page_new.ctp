<div id="schedulePageNewMain" class="fields_2box">
    <?php
    if (!empty($items)) {
        foreach ($items AS $item) {
            $icon = '';
            if (!empty($item['Member']['basename'])) {
                $icon = $this->Media->file(
                        "s/{$item['Member']['dirname']}/{$item['Member']['basename']}");
            }
            if (empty($icon)) {
                $icon = $this->Html->image('head_s.png');
            } else {
                $icon = $this->Media->embed($icon);
            }
            $icon = $this->Html->link($icon, '/members/view/' . $item['Schedule']['member_id'], array(
                'title' => '瀏覽' . $item['Schedule']['member_name'] . '的個人檔案',
                'escape' => false,
                    ));
            $linkMember = $this->Html->link($item['Schedule']['member_name'], '/members/view/' . $item['Schedule']['member_id'], array(
                'title' => $item['Schedule']['member_name'],
                    ));
            $linkSchedule = $this->Html->link($item['Schedule']['title'], '/schedules/view/' . $item['Schedule']['id'], array(
                'title' => $item['Schedule']['title'],
                    ));
            $genderClass = 'spot_XY';
            if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
                $genderClass = 'spot_XX';
            }
            ?>
            <div class="fields_2">
                <div class="block fillet_all shadow-box2">
                    <div class="block_item_title overspots"><?php echo $linkSchedule; ?></div>
                    <ul class="list1">
                        <li>共計 <span class="mark_txt"><?php
        echo $item['Schedule']['count_days'];
            ?></span> 天行程 <span class="mark_txt"><?php
                        echo $item['Schedule']['count_joins'];
            ?></span> 位同行</li>
                        <li>行經 <span class="mark_txt"><?php
                            echo $item['Schedule']['count_points'];
            ?></span> 個地點</li>
                    </ul>
                    <p class="clearfix"></p>
                    <div class="img-s float-l"><?php echo $icon; ?></div>
                    <div class="spot overspots <?php echo $genderClass; ?>"><?php echo $linkMember; ?></div>
                    <div class="overspots">&nbsp;<?php echo $item['Schedule']['intro']; ?></div>
                    <hr />
                    <div class="txt_S color1b float-l"><?php echo $item['Schedule']['created']; ?></div>
                    <ul class="list1 float-r">
                        <?php if (!empty($loginMember['id'])) { ?>
                            <li><?php
                echo $this->Html->link('加入', '/schedules/import/' . $item['Schedule']['id'], array(
                    'title' => '將行程加入我的行程表',
                    'class' => 'icon icon_plus scheduleImport',
                ));
                            ?></li>
                        <?php } ?>
                        <li><?php
                echo $this->Html->link('點閱', '/schedules/view/' . $item['Schedule']['id'], array(
                    'title' => '瀏覽行程的詳細內容',
                    'class' => 'icon icon_hand',
                ));
                        ?><span class="txt_S color1b"><?php
                    echo $item['Schedule']['count_views'];
                        ?></span></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
<?php if ($offset === 0): ?>
    <div class="clearfix"></div>
    <div class="block"><a class="dbtn dbtn3 fillet_all" href="#" id="schedulePageNewMore">瀏覽更多內容 &gt;&gt;</a></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var schedulePageNewOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#schedulePageNewMore').click(function() {
                schedulePageNewOffset += 20;
                $.get('<?php echo $this->Html->url('/schedules/page_new/'); ?>' + schedulePageNewOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#schedulePageNewMore').hide();
                    } else {
                        $('div#schedulePageNewMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
            $('a.scheduleImport').click(function() {
                dialogFull(this, '匯入行程');
                return false;
            });
        });
        -->
    </script>
<?php endif; ?>