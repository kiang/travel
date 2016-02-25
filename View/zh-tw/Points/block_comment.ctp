<?php if ($offset === 0) { ?>
    <div class="TabBox">
        <div class="clearfix"></div>
        <div class="fields_3box" id="pointBlockCommentMain">
        <?php } ?>
        <?php
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
            $icon = $this->Html->link($icon, '/members/view/' . $item['Comment']['member_id'], array(
                'title' => '瀏覽' . $item['Comment']['member_name'] . '的個人檔案',
                'escape' => false,
                    ));
            $title = '';
            if (!empty($item['Point']['title'])) {
                $title = $item['Point']['title'];
            } elseif (!empty($item['Point']['title_zh_tw'])) {
                $title = $item['Point']['title_zh_tw'];
            } else {
                $title = $item['Point']['title_en_us'];
            }
            ?>
            <div class="fields_3">
                <div class="block">
                    <div class="category categoryA01 float-l">&nbsp;</div>
                    <div class="block_item_title overspots"><?php
        echo $this->Html->link($title, '/points/view/' . $item['Point']['id']);
            ?></div>
                    <div class="overspots"><?php
                    echo $this->Html->link($item['Comment']['title'], '/points/view/' . $item['Point']['id']);
            ?></div>
                    <div class="clearfix"></div>
                    <p><?php
                    echo $item['Comment']['body'];
                    $genderClass = 'spot_XY';
                    if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
                        $genderClass = 'spot_XX';
                    }
            ?></p>
                    <div class="fields_4 float-l">
                        <div class="img-s float-l"><?php echo $icon; ?></div>
                        <div class="spot overspots <?php echo $genderClass; ?>"><?php
                    echo $this->Html->link($item['Comment']['member_name'], '/members/view/' . $item['Comment']['member_id'], array(
                        'title' => $item['Comment']['member_name'],
                    ))
            ?></div>
                        <div class="color1b txt_S">
                            <div class="spot_stars float-l"><?php
                        if (!empty($item['Comment']['rank'])) {
                            echo $this->element('showRank', array('showRank' => $item['Comment']['rank'])) . '<br />';
                        }
            ?></div>
                            <?php echo $item['Comment']['created']; ?></div>
                    </div>
                    <div class="clearfix"></div>
                    <ul class="list1 float-l">
                        <?php if (!empty($loginMember['id'])) { ?>
                            <li><?php
                    echo $this->Html->link('加入', '/schedule_lines/push/Point/' . $item['Point']['id'], array(
                        'title' => '將地點加入我的行程表',
                        'class' => 'icon icon_plus pointPush',
                    ));
                            ?></li>
                        <?php } ?>
                        <li><?php
                    echo $this->Html->link('點閱', '/points/view/' . $item['Point']['id'], array(
                        'title' => '瀏覽地點的詳細內容',
                        'class' => 'icon icon_hand',
                    ));
                    echo $item['Point']['count_views'];
                        ?></span></li>
                    </ul>
                </div>
            </div>
            <?php
        }
        ?>

        <?php if ($offset === 0) { ?>
        </div>
        <div class="clearfix"></div>
        <div class="block"><a class="dbtn dbtn3 fillet_all" href="#" id="pointBlockCommentMore">瀏覽更多內容 &gt;&gt;</a></div>
        <div class="clearfix"></div>
        <script type="text/javascript">
            <!--
            $(function() {
                var pointBlockCommentOffset = <?php echo $offset; ?>;
                var previousResult = '';
                $('a#pointBlockCommentMore').click(function() {
                    pointBlockCommentOffset += 15;
                    $.get('<?php echo $this->Html->url('/points/block_comment/'); ?>' + pointBlockCommentOffset, {}, function(result) {
                        if(previousResult === result) {
                            $('a#pointBlockCommentMore').hide();
                        } else {
                            $('div#pointBlockCommentMain').append(result);
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
    </div>
<?php } ?>