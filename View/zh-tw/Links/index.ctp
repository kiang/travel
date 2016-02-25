<div id="LinksControlPage">
    <h3>連結</h3>
    <div id="linkControlMessage" class="error" style="display:none;"></div>
    <?php
    $class = '';
    if ($loginMember['id'] > 0) {
        $class = ' LinksControl';
    }
    echo $this->Html->link('', array_merge(array('action' => 'add'), $url), array(
        'class' => 'olc-icon ui-icon-document' . $class,
        'title' => '點選這裡新增相關連結'
    )) . $this->Html->link('新增相關連結', array_merge(array('action' => 'add'), $url), array(
        'class' => $class,
        'title' => '點選這裡新增相關連結'
    ));
    echo '<hr /><div id="LinksControlPanel"></div>';
    if (!empty($items)) {
        echo '<ul>';
        foreach ($items as $item) {
            echo '<li>' .
            '<div><span class="olc-left-content"><span class="olc-icon ui-icon-extlink"></span>' .
            $this->Html->link($item['Link']['title'], $item['Link']['url'], array('target' => '_blank')) .
            '</span>';
            if ($loginMember['id'] == $item['Link']['member_id']) {
                echo $this->Html->link('', array('action' => 'delete', $item['Link']['id']), array(
                    'class' => 'olc-icon ui-icon-trash',
                    'title' => '點選這裡來刪除這個連結',
                        ), '確定刪除？');
            }
            echo '</div>' .
            '<div align="right">by ' .
            $this->Html->link($item['Link']['member_name'], '/members/view/' . $item['Link']['member_id']) .
            '<span class="dateTime"> @ ' . $item['Link']['created'] . '</span></div>' .
            '</li>';
        }
        echo '</ul>' .
        '<div class="paging">' . $this->element('paginator') . '</div>';
    }
    ?>
    <script type="text/javascript">
        $(function() {
            $('#LinksListTable th a, #LinksControlPage div.paging a').click(function() {
                $('#LinksControlPage').load(this.href);
                return false;
            });
            $('a.LinksControl').click(function() {
                $('#LinksControlPanel').load(this.href);
                return false;
            });
        });
    </script>
</div>