<div id="blockAllComments">
    <?php
    foreach ($comments AS $comment) {
        echo '<div class="box">';
        echo '<div class="span-2 memberIconBox">';
        $icon = '';
        if (!empty($comment['Member']['basename'])) {
            $icon = $this->Media->file(
                    "s/{$comment['Member']['dirname']}/{$comment['Member']['basename']}");
        }
        if (empty($icon)) {
            echo $this->Html->image('head_s.png');
        } else {
            echo $this->Media->embed($icon);
        }
        echo '<br />' . $this->Html->link($comment['Comment']['member_name'], '/members/view/' . $comment['Comment']['member_id']);
        echo '</div>';
        if ($comment['Comment']['rank']) {
            echo $this->element('showRank', array('showRank' => $comment['Comment']['rank'])) . '<br />';
        }
        if (isset($comment['Comment']['topic'])) {
            echo $this->Html->link('<strong> ' . $comment['Comment']['topic'] . ' </strong>', '/' . $foreignControllers[$comment['Comment']['model']] . '/view/' . $comment['Comment']['foreign_key'], array('escape' => false)
            );
        }
        echo '<br />';
        echo '<span class="olc-icon ui-icon-comment"></span>';
        echo '<span><strong>' . $comment['Comment']['title'];
        echo '</strong></span><span class="dateTime"> , ' . $comment['Comment']['created'] . '</span>';
        echo '<div class="clear commentBody">' . $comment['Comment']['body'] . '</div>';
        echo '</div>';
    }
    echo '<div class="paging">' . $this->element('paginator') . '</div>';
    ?>
    <script type="text/javascript">
        $(function() {
            $('#blockAllComments div.paging a').click(function() {
                $('#blockAllComments').load(this.href);
                return false;
            });
        });
    </script>
</div>