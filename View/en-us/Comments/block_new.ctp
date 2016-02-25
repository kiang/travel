<?php
if (!isset($model)) {
    exit();
}
?>
<div id="blockNewCommens"<?php
if (in_array($model, array('Schedule'))) {
    echo ' class="box"';
}
?>>
    <h3>最新<?php
     switch ($model) {
         case 'Schedule':
             echo 'Itineraries';
             break;
         case 'Point':
             echo 'Points';
             break;
     }
?>評論</h3>
    <?php
    if (!empty($items)) {
        echo '<ul>';
        foreach ($items as $item) {
            $item['Comment']['body'] = mb_substr($item['Comment']['body'], 0, 30, 'utf8');
            echo '<li>';
            echo '<div>';
            echo $this->Html->link($item['Comment']['body'] . '...', '/' . $foreignControllers[$model] . '/view/' . $item['Comment']['foreign_key']) . '</div>';
            echo '<div align="right" class="dateTime"> @ ' . $item['Comment']['created'] . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    }
    ?>
</div>