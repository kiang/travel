<?php if ($offset === 0) { ?>
    <dl class="list2" id="linkMemberMain">
    <?php } ?>
    <?php
    if (!empty($items)) {
        foreach ($items as $item) {
            ?>
            <dt class="overspots"><?php
        echo $this->Html->link($item['Link']['title'], '/' . $foreignControllers[$item['Link']['model']] . '/view/' . $item['Link']['foreign_key']);
            ?></dt>
            <dd>
                <?php if ($loginMember['id'] == $item['Link']['member_id']) { ?>
                    <div class="float-r"><?php
            echo $this->Html->link('Delete', array('action' => 'delete', $item['Link']['id'], 'member'), array(
                'title' => 'Delete this link',
                'class' => 'dbtn dbtn_X',
                    ), 'Are you sure you want to delete this?');
                    ?></div>
                    <?php
                }
                echo $this->Html->link($item['Link']['url'], $item['Link']['url'], array(
                    'target' => '_blank',
                ));
                ?>
            </dd>
            <?php
        }
    }
    ?>
    <?php if ($offset === 0) { ?>
    </dl>
    <div class="clearfix"></div>
    <p><a id="linkMemberMore" class="dbtn dbtn3 fillet_all" href="#" title="Log of comments for the member">More &gt;&gt;</a></p>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            var linkMemberOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#linkMemberMore').click(function() {
                linkMemberOffset += 5;
                $.get('<?php echo $this->Html->url($url); ?>/' + linkMemberOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#linkMemberMore').hide();
                    } else {
                        $('#linkMemberMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        })
        //]]>
    </script>
<?php } ?>