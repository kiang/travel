<div id="AreasViewPage">
    <h2><?php
if (!empty($parents)) {
    $prefix = false;
    foreach ($parents AS $parent) {
        if (!$prefix) {
            $prefix = true;
        } else {
            echo ' > ';
        }
        echo $this->Html->link($parent['Area']['name'], array($parent['Area']['id']), array('class' => 'AreaControlLink'));
    }
}
?></h2>
    <?php
    foreach ($areas AS $area) {
        ?>
        <div class="olc-icon ui-icon ui-icon-grip-diagonal-se"></div>
        <div class="areaListBox">
            <?php
            if ($area['Area']['rght'] == ($area['Area']['lft'] + 1)) {
                echo '<span class="olc-left-content">' . $area['Area']['name'] . '</span>';
            } else {
                echo $this->Html->link($area['Area']['name'], array($area['Area']['id']), array(
                    'class' => 'AreaControlLink olc-left-content'
                ));
            }
            echo '<br /><a class="olc-icon ui-icon ui-icon-video" title="行程">&nbsp;</a>';
            if ($area['Area']['countSchedule'] <= 0) {
                echo '<span class="olc-left-content">0</span>';
            } else {
                echo $this->Html->link($area['Area']['countSchedule'], array(
                    'controller' => 'schedules',
                    'action' => 'index',
                    '0', $area['Area']['id']
                        ), array('class' => 'olc-left-content'));
            }
            echo '<br /><a class="olc-icon ui-icon ui-icon-image" title="地點">&nbsp;</a>';
            if ($area['Area']['countPoint'] <= 0) {
                echo '<span class="olc-left-content">0</span>';
            } else {
                echo $this->Html->link($area['Area']['countPoint'], array(
                    'controller' => 'points',
                    'action' => 'index',
                    'Area', $area['Area']['id']
                        ), array('class' => 'olc-left-content'));
            }
            ?>
        </div>
        <?php
    }
    ?>
    <div class="paging clear"><?php echo $this->element('paginator'); ?></div>
    <script type="text/javascript">
        $(function() {
            $('#AreasViewPage .AreaControlLink, #AreasViewPage div.paging a').click(function() {
                $('#AreasViewPage').load(this.href);
                return false;
            });
        });
    </script>
</div>