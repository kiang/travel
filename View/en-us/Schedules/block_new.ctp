<?php if ($offset === 0): ?>
    <div class="clearfix"></div>
    <div class="fields_bg1" id="scheduleBlockNewMain">
    <?php endif; ?>
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
            <div class="fields_4">
                <div class="block">
                    <div class="img-s float-l"><?php echo $icon; ?></div>
                    <div class="block_item_title overspots"><?php echo $linkSchedule; ?></div>
                    <div class="spot overspots <?php echo $genderClass; ?>"><?php echo $linkMember; ?></div>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <div class="clearfix"></div>
    <?php if ($offset === 0): ?>
    </div>
    <p><a class="dbtn dbtn3 fillet_all" href="#" id="scheduleBlockNewMore">More &gt;&gt;</a></p>
    <script type="text/javascript">
        <!--
        $(function() {
            var scheduleBlockNewOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#scheduleBlockNewMore').click(function() {
                scheduleBlockNewOffset += 20;
                $.get('<?php echo $this->Html->url('/schedules/block_new/'); ?>' + scheduleBlockNewOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#scheduleBlockNewMore').hide();
                    } else {
                        $('div#scheduleBlockNewMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        -->
    </script>
<?php endif; ?>