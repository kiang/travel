<?php

App::uses('String', 'Utility');
if (!empty($areas)) {
    $count = 0;
    $scripts = '';
    $selected = 0;
    foreach ($areas AS $area) {
        if ($area['selected'] > 0) {
            $selected = $area['selected'];
        }
        ++$count;
        $uuid = String::uuid();
        echo '<select name="data[Area][' . $number . '][]" id="' . $uuid . '">';
        echo '<option value="' . $area['parent_id'] . '">---請選擇---</option>';
        foreach ($area['options'] AS $option) {
            echo '<option value="' . $option['Area']['id'] . '"';
            if ($area['selected'] == $option['Area']['id']) {
                echo ' selected="selected"';
            }
            echo '>' . $option['Area']['name'] . '</option>';
        }
        echo '</select>';
        echo '<span id="' . $uuid . 'child">';
        $scripts .= '
	$(\'#' . $uuid . '\').change(function() {
		var areaValue = $(this).val();
		if(areaValue != ' . $area['parent_id'] . ') {
			$(\'#' . $uuid . 'child\').load(\'' . $this->Html->url(array('action' => 'getForm', $model, $number)) . '/\' + areaValue);
		}
		$(\'#' . $model . 'Area' . $number . '\').val(areaValue);
	});
';
    }
    while ($count > 0) {
        echo '</span>';
        --$count;
    }
    if ($selected > 0) {
        $scripts .= '$(\'#' . $model . 'Area' . $number . '\').val(' . $selected . ');';
    }
    echo $this->Html->scriptBlock('
$(function() {
' . $scripts . '
});
    ');
}