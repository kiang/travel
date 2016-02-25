<?php

if (!empty($scheduleLineMessage)) {
    echo $scheduleLineMessage;
} else {
    $scope = 'line' . $id . 'EditPage';
    echo '<div id="' . $scope . '">';
    echo $this->Form->create('ScheduleLine', array('id' => $scope . 'Form'));
    echo '<div class="form"></div>';
    echo $this->Form->end('送出');
    echo $this->Html->scriptBlock('
$(function() {
    $(\'#' . $scope . 'Form div.form\').load(\'' . $this->Html->url(array('action' => 'form', $id)) . '\');
    var submitted = false;
    $(\'form#' . $scope . 'Form\').submit(function() {
    	if(false === submitted) {
    		submitted = true;
    		$.post(\'' . $this->Html->url(array('action' => 'edit', $id)) . '\', $(this).serializeArray(), function(pageData) {
    			if(pageData == \'done\') {
    				$(\'#ScheduleDay' . $this->request->data['ScheduleLine']['schedule_day_id'] . '\').load(\'' . $this->Html->url(array(
                'controller' => 'schedule_days',
                'action' => 'view',
                $this->request->data['ScheduleLine']['schedule_day_id']
            )) . '\');
    			} else {
    				$(\'#' . $scope . '\').load(\'' .
            $this->Html->url(array('action' => 'edit', $id)) .
            '\');
    			}
    			submitted = false;
    		});
		}
    	return false;
    });
});');
    echo '</div>';
}