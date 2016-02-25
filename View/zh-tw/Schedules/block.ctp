<?php if (!empty($items)): ?>
    <div id="ScheduleBlockPage">
        <h3>行程</h3>
        <div class="box">
            <?php
            foreach ($items as $item) {
                echo '<span class="olc-left-content"><span class="olc-icon ui-icon-video"></span>' .
                $this->Html->link($item['Schedule']['title'], '/schedules/view/' . $item['Schedule']['id']) .
                '</span>' .
                '<div align="right" class="clear">by ' .
                $this->Html->link($item['Schedule']['member_name'], '/members/view/' . $item['Schedule']['member_id']) .
                '<span class="dateTime"> @ ' . $item['Schedule']['created'] . '</span></div>';
            }
            echo '<div class="paging" class="clear">' . $this->element('paginator') . '</div>';
            $scripts = '
$(function() {
    $(\'#ScheduleBlockPage div.paging a\').click(function() {
        $(\'#ScheduleBlockPage\').load(this.href);
        return false;
    });
});';
            echo $this->Html->scriptBlock($scripts);
            ?>
        </div>
    </div>
<?php endif; ?>