<?php if(empty($items)) return ''; ?>
<?php if (empty($offset)) { ?>
    <div id="schedulePointMain">
    <?php } ?>
    <?php foreach ($items as $item) { ?>
        <div class="fields_2">
            <div class="block fillet_all shadow-box2">
                <div class="block_item_title overspots"><?php
    echo $this->Html->link($item['Schedule']['title'], array('action' => 'view', $item['Schedule']['id']));
        ?></div>
                <ul class="list1">
                    <li><span class="mark_txt"><?php echo $item['Schedule']['count_days']; ?></span> days with <span class="mark_txt"><?php echo $item['Schedule']['count_joins']; ?></span> joinees</li>
                </ul>
                <p class="clearfix"></p>
                <div class="img-s float-l"><?php
                echo $this->element('icon', array('iconData' => $item['Member']));
                $genderClass = 'spot_XY';
                if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
                    $genderClass = 'spot_XX';
                }
        ?></div>
                <div class="spot overspots <?php echo $genderClass; ?>"><?php
                echo $this->Html->link($item['Schedule']['member_name'], '/members/view/' . $item['Schedule']['member_id']);
        ?></div>
                <div class="overspots">&nbsp;<?php
                echo $this->Html->link($item['Schedule']['intro'], array('action' => 'view', $item['Schedule']['id']));
        ?></div>
                <hr />
                <div class="txt_S color1b float-l"><?php echo $item['Schedule']['time_start']; ?></div>
                <ul class="list1 float-r">
                    <li><?php
                echo $this->Html->link('Import', '/schedules/import/' . $item['Schedule']['id'], array(
                    'title' => 'Import this itinerary to mine',
                    'class' => 'icon icon_plus scheduleImport',
                ));
        ?></li>
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
    <?php } ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <div class="block"><a id="schedulePointMore" class="dbtn dbtn3 fillet_all" href="#">More &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var schedulePointOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#schedulePointMore').click(function() {
                schedulePointOffset += 10;
                $.get('<?php echo $this->Html->url($url); ?>/' + schedulePointOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#schedulePointMore').hide();
                    } else {
                        $('div#schedulePointMain').append(result);
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
<?php } ?>