<?php if (empty($offset)) { ?>
    <div id="scheduleAreaMain">
    <?php } ?>
    <?php foreach ($items as $item) { ?>
        <div class="fields_2">
            <div class="block fillet_all shadow-box2">
                <div class="block_item_title overspots"><?php
    echo $this->Html->link($item['Schedule']['title'], array('action' => 'view', $item['Schedule']['id']));
        ?></div>
                <ul class="list1">
                    <li>共計 <span class="mark_txt"><?php echo $item['Schedule']['count_days']; ?></span> 天行程 <span class="mark_txt"><?php echo $item['Schedule']['count_joins']; ?></span> 位同行</li>
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
                echo $this->Html->link('加入', '/schedules/import/' . $item['Schedule']['id'], array(
                    'title' => '將行程加入我的行程表',
                    'class' => 'icon icon_plus scheduleImport',
                ));
        ?></li>
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
    <?php } ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <div class="block"><a id="scheduleAreaMore" class="dbtn dbtn3 fillet_all" href="#">瀏覽更多內容 &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var scheduleAreaOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#scheduleAreaMore').click(function() {
                scheduleAreaOffset += 10;
                $.get('<?php echo $this->Html->url($url); ?>/' + scheduleAreaOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#scheduleAreaMore').hide();
                    } else {
                        $('div#scheduleAreaMain').append(result);
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
<?php } ?>