<?php

if (!empty($rankingData)) {
    echo $this->Form->radio($rankingData['model'] . '.rank', array(1 => '', 2 => '', 3 => '', 4 => '', 5 => ''), array('class' => 'star', 'label' => false, 'legend' => false, 'value' => $rankingData['rank']));
    echo $this->Html->scriptBlock('
$(function() {
    $(\'#' . $placeHolder . ' input[type=radio].star\').rating({
    	callback: function() {
    		$(\'#' . $placeHolder . '\').load(\'' . $this->Html->url('/ranks/add/' . $placeHolder . '/' . $rankingData['model'] . '/' . $rankingData['foreign_key']) . '/\' + this.value);
    	}
    });
});
');
}