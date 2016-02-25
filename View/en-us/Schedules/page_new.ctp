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
                'title' => 'Browse ' . $item['Schedule']['member_name'] . '\'s profile',
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
                        <li><span class="mark_txt"><?php
        echo $item['Schedule']['count_days'];
            ?></span> days with <span class="mark_txt"><?php
                        echo $item['Schedule']['count_joins'];
            ?></span> joinees</li>
                        <li>Bypass <span class="mark_txt"><?php
                            echo $item['Schedule']['count_points'];
            ?></span> points</li>
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
                echo $this->Html->link('Import', '/schedules/import/' . $item['Schedule']['id'], array(
                    'title' => 'Import this itinerary to mine',
                    'class' => 'icon icon_plus scheduleImport',
                ));
                            ?></li>
                        <?php } ?>
                        <li><?php
                echo $this->Html->link('Clicks', '/schedules/view/' . $item['Schedule']['id'], array(
                    'title' => 'Check the details of this itinerary',
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
    <div class="block"><a class="dbtn dbtn3 fillet_all" href="#" id="schedulePageNewMore">More &gt;&gt;</a></div>
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
                dialogFull(this, 'Import itinerary');
                return false;
            });
        });
        -->
    </script>
<?php endif; ?>