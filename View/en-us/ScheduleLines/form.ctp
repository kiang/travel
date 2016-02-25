<div class="ScheduleLines form">
    <?php
    if ($id > 0) {
        echo $this->Form->input('ScheduleLine.id');
    }
    echo $this->Form->input('ScheduleLine.foreign_key', array('type' => 'hidden'));
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '目的地的名稱，輸入過程停頓時，系統會自動查詢有沒有符合的項目'));
    echo '地點</div>';
    echo $this->Form->input('ScheduleLine.point_name', array(
        'label' => false,
        'div' => 'span-6',
        'class' => 'span-6',
    ));
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '在這個地點停留或預計停留的時間'));
    echo '停留時間</div>' . $this->Form->input('ScheduleLine.minutes_stay', array(
        'type' => 'text',
        'label' => false,
        'div' => 'span-6',
        'class' => 'span-6',
    ));
    $longitude = 0;
    if (!empty($this->request->data['ScheduleLine']['longitude'])) {
        $longitude = $this->request->data['ScheduleLine']['longitude'];
    }
    echo $this->Html->link('(展開)', '#', array('id' => 'scheduleLineFormDetailShow'));
    echo '<div id="scheduleLineFormDetail" style="display:none;">';
    echo $this->Html->link('(精簡)', '#', array('id' => 'scheduleLineFormDetailHide'));
    echo '<hr />';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '東經或是西經度數，請使用帶有小數的形式，像 120.960515'));
    echo '經度</div>' . $this->Form->input('ScheduleLine.longitude', array(
        'type' => 'text',
        'value' => $longitude,
        'label' => false,
        'div' => 'span-6',
        'class' => 'span-6',
    ));
    $display = '';
    if (empty($this->request->data['ScheduleLine']['foreign_key'])) {
        $display = 'display:none;';
    }
    $latitude = 0;
    if (!empty($this->request->data['ScheduleLine']['latitude'])) {
        $latitude = $this->request->data['ScheduleLine']['latitude'];
    }
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '北緯或是南緯度數，請使用帶有小數的形式，像 23.69781'));
    echo '緯度</div>' . $this->Form->input('ScheduleLine.latitude', array(
        'type' => 'text',
        'value' => $latitude,
        'label' => false,
        'div' => 'span-6',
        'class' => 'span-6',
    )) . $this->Html->link('', '#', array(
        'id' => 'getLatLng',
        'class' => 'olc-icon ui-icon-search',
        'style' => $display,
        'title' => '點選這裡會透過已經選擇的地點或旅館來查詢經緯度',
    )) . $this->Html->link('', '#', array(
        'id' => 'findLatLng',
        'class' => 'olc-icon ui-icon-zoomin',
        'title' => '點選這裡會顯示座標查詢工具',
    )) . $this->Html->link('', '#', array(
        'id' => 'clearLatLng',
        'class' => 'olc-icon ui-icon-trash',
        'title' => '點選這裡會清除目前座標資料',
    ));
    echo '<hr />';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '前往目的地的交通方式，可以在右邊選一個合適的的Type，點選右邊圖示會自動填上選擇的項目'));
    echo '交通</div>';
    echo $this->Form->input('ScheduleLine.transport_id', array(
        'type' => 'select',
        'label' => false,
        'options' => $transports,
        'div' => 'span-3',
        'class' => 'span-3',
        'empty' => '--請選擇--',
    ));
    echo $this->Form->input('ScheduleLine.transport_name', array(
        'label' => false,
        'div' => 'span-5',
        'class' => 'span-5',
    ));
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '在目的地進行的主要活動，可以在右邊選一個合適的的Type，點選右邊圖示會自動填上選擇的項目'));
    echo '活動</div>';
    echo $this->Form->input('ScheduleLine.activity_id', array(
        'type' => 'select',
        'label' => false,
        'options' => $activities,
        'div' => 'span-3',
        'class' => 'span-3',
        'empty' => '--請選擇--',
    ));
    echo $this->Form->input('ScheduleLine.activity_name', array(
        'label' => false,
        'div' => 'span-5 last',
        'class' => 'span-5',
    ));
    echo '<hr />';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '到達這個地點的時間，或預計到達的時間'));
    echo '到達</div>' . $this->Form->input('ScheduleLine.time_arrive', array(
        'type' => 'text',
        'label' => false,
        'div' => 'span-5',
        'class' => 'span-5',
    ));
    echo '<hr />';
    echo '<div class="span-3">';
    echo $this->element('tooltip', array('tipMessage' => '這個行程也許還有其他需要補充的資訊'));
    echo '備註</div>' . $this->Form->input('ScheduleLine.note', array(
        'type' => 'textarea',
        'label' => false,
        'div' => 'span-15',
        'class' => 'span-15',
        'rows' => 2,
    ));
    echo '</div>';
    echo '<div class="clear"></div>';
    echo $this->Html->scriptBlock('
function getLatLngTrigger() {
	var latField = $(\'#ScheduleLineLatitude\').val();
	var lngField = $(\'#ScheduleLineLongitude\').val();
	if((latField == \'\' || latField == \'0\') && (lngField == \'\' || lngField == \'0\')) {
		$(\'#getLatLng\').trigger(\'click\');
	}
}
$(function() {
	$(\'#ScheduleLineTransportId\').change(function() {
		$(\'#ScheduleLineTransportName\').attr(\'value\',
			$(\'#ScheduleLineTransportId option:selected\').text()
		);
	});
	$(\'#ScheduleLineActivityId\').change(function() {
		$(\'#ScheduleLineActivityName\').attr(\'value\',
			$(\'#ScheduleLineActivityId option:selected\').text()
		);
	});
    $(\'#ScheduleLinePointName\').autocomplete({
        source: \'' . $this->Html->url('/points/auto_list/') . '\',
        select: function(event, ui) {
            $(\'#ScheduleLineForeignKey\').attr(\'value\', ui.item.id);
            $(\'#getLatLng\').show();
            getLatLngTrigger();
        }
    });
    $(\'a#scheduleLineFormDetailShow\').click(function() {
    	$(\'div#scheduleLineFormDetail\').show();
    	$(this).hide();
    	return false;
    });
    $(\'a#scheduleLineFormDetailHide\').click(function() {
    	$(\'a#scheduleLineFormDetailShow\').show();
    	$(\'div#scheduleLineFormDetail\').hide();
    	return false;
    });
    $(\'#getLatLng\').click(function() {
    	var key = $(\'#ScheduleLineForeignKey\').val();
    	if(key <= 0) {
    		alert(\'請先選擇適當的地點或旅館\');
    	} else {
    		$.get(\'' . $this->Html->url('/schedule_lines/get_latlng/') . '\' + key, function(result) {
    			if(result != \'[]\' && eval(result).length == 2) {
    				result = eval(result);
    				$(\'#ScheduleLineLatitude\').val(result[0]);
    				$(\'#ScheduleLineLongitude\').val(result[1]);
    			}
    		});
    	}
    	return false;
    });
    $(\'#findLatLng\').click(function() {
    	findLatLng($(\'#ScheduleLineLatitude\'), $(\'#ScheduleLineLongitude\'));
    	return false;
    });
    $(\'#clearLatLng\').click(function() {
    	$(\'#ScheduleLineLatitude\').val(\'\');
    	$(\'#ScheduleLineLongitude\').val(\'\');
    	return false;
    });
    $(\'#ScheduleLineMinutesStay,#ScheduleLineTimeArrive\').timepicker({timeOnly: true});
});
');
    ?>
</div>