<?php if ($offset === 0) { ?>
    <dl class="list2" id="commentMemberLogMain">
    <?php } ?>
    <?php
    if (!empty($items)) {
        foreach ($items as $item) {
            if (empty($item['Comment']['title'])) {
                $item['Comment']['title'] = mb_substr($item['Comment']['title'], 0, 10, 'UTF-8') . '...';
            }
            ?>
            <dt class="overspots"><?php
        echo $this->Html->link($item['Comment']['title'], '/' . $foreignControllers[$item['Comment']['model']] . '/view/' . $item['Comment']['foreign_key']);
            ?></dt>
            <dd>
                <?php if ($loginMember['id'] == $item['Comment']['member_id']) { ?>
                    <div class="float-r"><?php
            echo $this->Html->link('Delete', array('action' => 'delete', $item['Comment']['id'], 'member_log'), array(
                'title' => 'Delete this comment',
                'class' => 'dbtn dbtn_X',
                    ), 'Are you sure you want to delete this?');
                    ?></div>
                    <?php
                }
                if ($item['Comment']['rank']) {
                    echo '<span class="form_title" style="float:left;">Rank</span><span class="form_content fillet_all">';
                    echo $this->element('showRank', array('showRank' => $item['Comment']['rank']));
                    echo '</span><div class="clearfix"></div>';
                }
                ?>

            </dd>
            <?php
        }
    }
    ?>
    <?php if ($offset === 0) { ?>
    </dl>
    <div class="clearfix"></div>
    <p><a id="commentMemberLogMore" class="dbtn dbtn3 fillet_all" href="#" title="Log of comments for the member">More &gt;&gt;</a></p>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            var commentMemberLogOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#commentMemberLogMore').click(function() {
                commentMemberLogOffset += 5;
                $.get('<?php echo $this->Html->url($url); ?>/' + commentMemberLogOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#commentMemberLogMore').hide();
                    } else {
                        $('#commentMemberLogMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        })
        //]]>
    </script>
<?php } ?>