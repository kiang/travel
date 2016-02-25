<div id="FavoritesMemberPage">
    <h3><?php echo $subTitle; ?></h3>
    <?php
    $scripts = '';
    if (!empty($items)) {
        echo '<ul>';
        foreach ($items as $item) {
            echo '<li><div><span class="olc-left-content">';
            echo $this->Html->link('<strong>' . $item['Favorite']['foreignTitle'] . '</strong>', '/' . $foreignControllers[$item['Favorite']['model']] . '/view/' . $item['Favorite']['foreign_key'], array('escape' => false)
            );
            echo '</span>';
            if ($loginMember['id'] > 0 && $item['Favorite']['model'] == 'Schedule') {
                echo $this->Html->link('', array('controller' => 'schedules',
                    'action' => 'copy', $item['Favorite']['foreign_key']
                        ), array(
                    'class' => 'olc-icon ui-icon-copy',
                    'title' => '複製一份行程進行編輯',
                ));
                echo $this->Html->link('', array('controller' => 'schedules',
                    'action' => 'import', $item['Favorite']['foreign_key']
                        ), array(
                    'class' => 'olc-icon ui-icon-arrowthickstop-1-s',
                    'title' => '匯入部份行程到自己的行程中',
                ));
            }
            echo '</div>';
            echo '<div align="right" class="dateTime"> @ ' . $item['Favorite']['created'] . '</div>';
            echo '</li>';
        }
        echo '</ul>';
        echo '<div class="paging">' . $this->element('paginator') . '</div>';
    }
    ?>
    <script type="text/javascript">
        $(function() {
            $('#FavoritesMemberPage div.paging a').click(function() {
                $('#FavoritesMemberPage').load(this.href);
                return false;
            });
        });
    </script>
</div>