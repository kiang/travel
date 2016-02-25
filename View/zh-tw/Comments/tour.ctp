<?php if (empty($items) && empty($loginMember['id'])) return ''; ?>
<?php if ($offset === 0) { ?>
    <div id="commentTourMain">
        <div class="clearfix"></div>
        <?php if (!empty($loginMember['id'])) { ?>
            <div id="commentControlMessage" class="mark_txt clear-b" style="display:none;"></div>
            <div class="fields_s">
                <?php
                $url = array('action' => 'add', 'Tour', $tourId);
                echo $this->Form->create('Comment', array('url' => $url));
                ?>
                <div class="form1 fillet_all">
                    <?php
                    echo $this->Form->input('Comment.title', array(
                        'label' => '標題',
                        'class' => 'fillet_all',
                    ));
                    ?>
                    <p> <span class="form_title">評等</span><span class="form_content fillet_all"><?php
            echo $this->Form->radio('Comment.rank', array(1 => '', 2 => '', 3 => '', 4 => '', 5 => ''), array('class' => 'star', 'label' => false, 'legend' => false));
                    ?></span> </p>
                    <?php
                    echo $this->Form->input('Comment.body', array(
                        'type' => 'textarea',
                        'label' => '內容',
                        'class' => 'fillet_all',
                        'rows' => 5,
                    ));
                    ?>
                    <div class="clearfix"></div>
                </div>
                <p>
                    <a href="#" class="btn commentSubmit"><i class="icon-ok"></i> 送出</a>
                </p>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($items)) { ?>
        <?php if ($offset === 0) { ?>
            <div class="<?php echo!empty($loginMember['id']) ? 'fields_c' : 'fields_1'; ?>">
                <dl class="list1" id="commentBlockTourMain">
                <?php } ?>
                <?php
                foreach ($items as $item) {
                    ?>
                    <dt>
                    <div class="float-r"><?php
            if ($loginMember['id'] == $item['Comment']['member_id']) {
                echo $this->Html->link('刪除', array('action' => 'delete', $item['Comment']['id']), array(
                    'class' => 'dbtn dbtn_X',
                    'title' => '刪除本筆留言',
                        ), '確定刪除？');
            }
                    ?></div>
                    <div class="spot_stars float-r"><?php
                if ($item['Comment']['rank']) {
                    echo $this->element('showRank', array('showRank' => $item['Comment']['rank'])) . '<br />';
                }
                    ?></div>
                    <?php echo $item['Comment']['title']; ?>
                    <div class="clearfix"></div>
                    </dt>
                    <dd>
                        <div class="fields_4" style="float:right;">
                            <div class="img-s float-l"><?php
            echo $this->element('icon', array('iconData' => $item['Member']));
            $genderClass = 'spot_XY';
            if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
                $genderClass = 'spot_XX';
            }
                    ?></div>
                            <div class="spot overspots <?php echo $genderClass; ?>"><?php
                        echo $this->Html->link($item['Comment']['member_name'], '/members/view/' . $item['Comment']['member_id']);
                    ?></div>
                            <div class="color1b txt_S">@ <?php echo $item['Comment']['created']; ?></div>
                        </div>
                        <p><?php echo $item['Comment']['body']; ?></p>
                        <div class="clearfix"></div>
                    </dd>
                    <?php
                }
                ?>
            </dl>
            <?php if ($offset === 0) { ?>
                <p><a class="dbtn dbtn3 fillet_all" href="#" id="commentBlockTourMore">瀏覽更多內容 &gt;&gt;</a></p>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($offset === 0) { ?>
        <div class="clearfix"></div>
        <script type="text/javascript">
            //<![CDATA[
            $(function() {
    <?php if (!empty($loginMember['id'])) { ?>
                    $('input[type=radio].star').rating();
                    var submitted = false;
                    $('a.commentSubmit').click(function() {
                        $(this).parents('form').submit();
                        return false;
                    });
                    $('form#CommentTourForm').submit(function() {
                        if(false === submitted) {
                            submitted = true;
                            $('#commentControlMessage').hide();
                            $.post(wwwRoot + 'comments/add/Tour/<?php echo $tourId; ?>', $(this).serializeArray(), function(pageData) {
                                if(pageData == 'done') {
                                    $('div#commentTourMain').load(wwwRoot + 'comments/tour/<?php echo $tourId; ?>');
                                } else {
                                    $('#commentControlMessage').html(pageData);
                                    $('#commentControlMessage').show();
                                    submitted = false;
                                }
                            });
                        }
                        return false;
                    });
    <?php } ?>
                var commentBlockTourOffset = <?php echo $offset; ?>;
                var previousResult = '';
                $('a#commentBlockTourMore').click(function() {
                    commentBlockTourOffset += 5;
                    $.get(wwwRoot + 'comments/tour/<?php echo $tourId; ?>' + commentBlockTourOffset, {}, function(result) {
                        if(previousResult === result) {
                            $('a#commentBlockTourMore').hide();
                        } else {
                            $('#commentBlockTourMain').append(result);
                            previousResult = result;
                        }
                    });
                    return false;
                });
            })
            //]]>
        </script>
    </div>
<?php } ?>