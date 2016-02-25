<div id="CommentsControlPage">
    <h3>留言板</h3>
    <div id="commentControlMessage" class="error" style="display:none;"></div>
    <?php
    $class = '';
    if ($loginMember['id'] > 0 && $addLink) {
        $class = ' CommentsControl';
    }
    if (!empty($url[1])) {
        echo $this->Html->link('', array_merge(array('action' => 'add'), $url), array(
            'class' => 'olc-icon ui-icon-document' . $class,
            'title' => '點選這裡新增留言'
        )) . $this->Html->link('新增留言', array_merge(array('action' => 'add'), $url), array(
            'class' => $class,
            'title' => '點選這裡新增留言'
        ));
    }
    echo '<hr /><div id="CommentsControlPanel"></div>';
    if (!empty($items)) {
        foreach ($items as $item) {
            echo '<div class="box clear">';
            echo '<div class="span-2 memberIconBox">';
            echo $this->element('icon', array('iconData' => $item['Member']));
            echo '<br />' . $this->Html->link($item['Comment']['member_name'], '/members/view/' . $item['Comment']['member_id']);
            echo '</div>';
            if ($item['Comment']['rank']) {
                echo $this->element('showRank', array('showRank' => $item['Comment']['rank'])) . '<br />';
            }
            if (isset($item['Comment']['foreignTitle'])) {
                echo $this->Html->link('<strong> ' . $item['Comment']['foreignTitle'] . ' </strong>', '/' . $foreignControllers[$item['Comment']['model']] . '/view/' . $item['Comment']['foreign_key'], array('escape' => false)
                ) . '<br />';
            }
            echo '<span class="olc-left-content"><span class="olc-icon ui-icon-comment"></span>';
            if ($item['Comment']['title']) {
                echo '<strong>' . $item['Comment']['title'] . '</strong><span class="dateTime"> , </span>';
            }
            echo '<span class="dateTime">' . $item['Comment']['created'] . '</span>';
            echo '</span>';
            if ($loginMember['id'] == $item['Comment']['member_id']) {
                echo $this->Html->link('', array('action' => 'delete', $item['Comment']['id']), array(
                    'class' => 'olc-icon ui-icon-trash',
                    'title' => '點選這裡來刪除這個評論',
                        ), 'Are you sure you want to delete this?');
            }
            echo '<div class="clear commentBody">';
            echo $item['Comment']['body'];
            echo '</div></div>';
        }
        echo '<div class="paging">' . $this->element('paginator') . '</div>';
    }
    ?>
    <script type="text/javascript">
        $(function() {
            $('#CommentsControlPage div.paging a').click(function() {
                $('#CommentsControlPage').load(this.href);
                return false;
            });
            $('a.CommentsControl').click(function() {
                $('#CommentsControlPanel').load(this.href);
                return false;
            });
        });
    </script>
</div>