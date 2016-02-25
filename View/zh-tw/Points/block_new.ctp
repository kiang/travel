<div id="blockNewPoints">
    <h3>最新地點</h3>
    <div class="box">
        <?php
        if (!empty($items)) {
            foreach ($items as $item) {
                echo '<div><span class="olc-icon ui-icon-image"></span>';
                echo $this->Html->link($this->Travel->getValue($item['Point'], 'title'), '/points/view/' . $item['Point']['id']) . '</div>';
                echo '<div align="right" class="dateTime"> @ ' . $item['Point']['created'] . '</div>';
            }
        }
        ?>
    </div></div>