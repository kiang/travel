<div id="RanksMemberPage">
    <h3>評分記錄</h3>
    <?php
    $scripts = '';
    if (!empty($items)) {
        echo '<ul>';
        foreach ($items as $item) {
            echo '<li>';
            echo $this->Html->link('<strong>' . $item['Rank']['foreignTitle'] . '</strong>', '/' . $foreignControllers[$item['Rank']['model']] . '/view/' . $item['Rank']['foreign_key'], array('escape' => false)
            );
            echo '<div>';
            echo $this->element('showRank', array('showRank' => $item['Rank']['rank']));
            echo '</div>';
            echo '<div align="right"> @ ' . $item['Rank']['modified'] . '</div>';
            echo '</li>';
        }
        echo '</ul>';
        echo '<div class="paging">' . $this->element('paginator') . '</div>';
    }
    $scripts .= '
$(function() {
    $(\'#RanksMemberPage div.paging a\').click(function() {
        $(\'#RanksMemberPage\').load(this.href);
        return false;
    });
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>