<?php if (empty($offset)) { ?>
    <div class="TabBox">
        <div class="fields_3box" id="pointAreaMain">
        <?php } ?>
        <?php
        foreach ($items AS $item) {
            $height = '';
            if (!isset($item['Comment'][0])) {
                $height = 'style="height: 115px;"';
            }
            ?><div class="fields_3">
                <div class="block" <?php echo $height; ?>>
                    <div class="category categoryA01 float-l">&nbsp;</div>
                    <div class="block_item_title overspots"><?php
        echo $this->Html->link($this->Travel->getValue($item['Point'], 'title'), array('action' => 'view', $item['Point']['id']));
            ?></div>
                    <?php if (isset($item['Comment'][0])) { ?>
                        <div class="overspots"><?php
                echo $this->Html->link($item['Comment'][0]['title'], array('action' => 'view', $item['Point']['id']));
                        ?></div>
                        <div class="clearfix"></div>
                        <p><?php echo $item['Comment'][0]['body']; ?></p>
                        <div class="fields_4 float-l">
                            <div class="img-s float-l"><?php
                    echo $this->element('icon', array('iconData' => $item['Comment'][0]['Member']));
                    $genderClass = 'spot_XY';
                    if (isset($item['Comment'][0]['Member']['gender']) && $item['Comment'][0]['Member']['gender'] === 'f') {
                        $genderClass = 'spot_XX';
                    }
                        ?></div>
                            <div class="spot overspots <?php echo $genderClass; ?>"><?php
                        echo $this->Html->link($item['Comment'][0]['member_name'], '/members/view/' . $item['Comment'][0]['member_id']);
                        ?></div>
                            <div class="color1b txt_S">
                                <div class="spot_stars float-l"><?php
                        echo $this->element('showRank', array('showRank' => $item['Comment'][0]['rank'])) . '<br />';
                        ?></div>
                                <?php echo $item['Comment'][0]['created']; ?></div>
                        </div>
                    <?php } ?>
                    <ul class="list1 float-l">
                        <li><?php
                echo $this->Html->link('加入', '/schedule_lines/push/Point/' . $item['Point']['id'], array(
                    'title' => '將地點加入我的行程表',
                    'class' => 'icon icon_plus pointPush',
                ));
                    ?></li>
                        <li><?php
                        echo $this->Html->link('點閱', '/points/view/' . $item['Point']['id'], array(
                            'title' => '瀏覽地點的詳細內容',
                            'class' => 'icon icon_hand',
                        ));
                        echo $item['Point']['count_views'];
                    ?></span></li>
                    </ul>
                </div>
            </div><?php
                    }
                ?>

        <div class="clearfix"></div>
        <?php if (empty($offset)) { ?>
        </div>
    </div>
    <div class="block"><a id="pointAreaMore" class="dbtn dbtn3 fillet_all" href="#">瀏覽更多內容 &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var pointAreaOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#pointAreaMore').click(function() {
                pointAreaOffset += 15;
                $.get('<?php echo $this->Html->url($url); ?>/' + pointAreaOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#pointAreaMore').hide();
                    } else {
                        $('div#pointAreaMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
            $('a.pointPush').click(function() {
                dialogFull(this, '匯入地點到行程');
                return false;
            });
        });
        -->
    </script>
<?php } ?>