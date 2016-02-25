<?php

if (!empty($scheduleLineMessage)) {
    echo $scheduleLineMessage;
} else {
    $scope = 'line' . $scheduleDayId . $afterLineId . 'AddPage';
    echo '<div id="' . $scope . '">';
    echo $this->Form->create('ScheduleLine', array('id' => $scope . 'Form'));
    echo '<div class="form"></div>';
    echo $this->Form->end('送出');
    echo $this->Html->scriptBlock('
$(function() {
    $(\'#' . $scope . 'Form .form\').load(\'' . $this->Html->url(array('action' => 'form')) . '\');
    var submitted = false;
    $(\'form#' . $scope . 'Form\').submit(function() {
    	if(false === submitted) {
    		submitted = true;
    		$.post(\'' . $this->Html->url(array('action' => 'add', $scheduleDayId, $afterLineId)) . '\', $(this).serializeArray(), function(pageData) {
    			if(pageData == \'done\') {
    				$(\'#ScheduleDay' . $scheduleDayId . '\').load(\'' . $this->Html->url(array(
                'controller' => 'schedule_days',
                'action' => 'view',
                $scheduleDayId
            )) . '\');
					$(\'#' . $scope . '\').remove();
    			} else {
    				$(\'#' . $scope . '\').load(\'' .
            $this->Html->url(array('action' => 'add', $scheduleDayId, $afterLineId)) .
            '\');
    				submitted = false;
    			}
    		});
		}
    	return false;
    });
});');
    echo '</div>';
}