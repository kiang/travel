<?php

if (!isset($iconSize)) {
    $iconSize = 's/';
}
$showLarge = false;
if ($iconSize === 'memberL') {
    $iconSize = '';
    $showLarge = true;
}
$icon = '';
if (!empty($iconData['basename'])) {
    $icon = $this->Media->file("{$iconSize}{$iconData['dirname']}/{$iconData['basename']}");
}
if (empty($icon)) {
    if ($showLarge) {
        echo $this->Html->image('head_l.png');
    } else {
        echo $this->Html->image('head_s.png');
    }
} else {
    echo $this->Media->embed($icon);
}