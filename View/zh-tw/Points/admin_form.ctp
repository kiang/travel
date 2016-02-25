<div class="Points form">
    <h3><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>地點</h3>
    <?php
    if ($id > 0) {
        echo $this->Form->input('Point.id');
    }
    ?>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">啟用</label>
            <?php
            echo $this->Form->input('Point.is_active', array(
                'type' => 'checkbox',
                'label' => false,
                'div' => false,
            ));
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">中文名稱</label>
            <?php
            echo $this->Form->input('Point.title_zh_tw', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">英文名稱</label>
            <?php
            echo $this->Form->input('Point.title_en_us', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">原文名稱</label>
            <?php
            echo $this->Form->input('Point.title', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group span4">
            <label class="add-on">地點類型</label>
            <?php
            echo $this->Form->input('Point.PointType', array(
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => $pointTypes,
                'label' => false,
                'div' => false,
                'class' => 'checkbox inline',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">營業時間</label>
            <?php
            echo $this->Form->input('Point.time_open', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span1 timepick',
            ));
            ?>
            <span class="add-on">~</span>
            <?php
            echo $this->Form->input('Point.time_close', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span1 timepick',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">營業說明</label>
            <?php
            echo $this->Form->input('Point.time_note', array(
                'type' => 'textarea',
                'rows' => 3,
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">連絡電話</label>
            <?php
            echo $this->Form->input('Point.telephone', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">傳真號碼</label>
            <?php
            echo $this->Form->input('Point.fax', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span8">
            <label class="add-on">官方網站</label>
            <?php
            echo $this->Form->input('Point.website', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="control-group">
        <div class="control-group input-prepend span2">
            <label class="add-on">區域編號</label>
            <?php
            echo $this->Form->input('Point.area_id', array(
                'type' => 'text',
                'id' => 'PointArea1',
                'label' => false,
                'div' => false,
                'class' => 'span1',
            ));
            ?>
        </div>
        <div class="control-group span6">
            <label class="add-on">所在區域</label>
            <?php
            if (!empty($this->validationErrors['Point']['area_id'][0])) {
                echo '<div id="pointArea" class="error table-cell table_td_85p">';
            } else {
                echo '<div id="pointArea" class="table-cell table_td_85p">';
            }
            if (!empty($areaPath)) {
                echo implode(' >> ', Set::extract('{n}.Area.name', $areaPath));
                echo $this->Html->link('(修改)', '#', array('id' => 'pointAreaEdit'));
                $replaceArea = false;
            } else {
                $replaceArea = true;
            }
            echo '</div>';
            if (!empty($this->validationErrors['Point']['area_id'][0])) {
                echo '<div class="error-message error">' . $this->validationErrors['Point']['area_id'][0] . '</div>';
            }
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">地理經度</label>
            <?php
            echo $this->Form->input('Point.longitude', array(
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">地理緯度</label>
            <?php
            echo $this->Form->input('Point.latitude', array(
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">郵遞區號</label>
            <?php
            echo $this->Form->input('Point.postcode', array(
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
            <a href="#" class="btn findLatLng" title="以地址查詢Google Map上的座標"><i class="icon-search"></i> 座標</a>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span8">
            <label class="add-on">中文地址</label>
            <?php
            echo $this->Form->input('Point.address_zh_tw', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span8">
            <label class="add-on">英文地址</label>
            <?php
            echo $this->Form->input('Point.address_en_us', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span8">
            <label class="add-on">原文地址</label>
            <?php
            echo $this->Form->input('Point.address', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php
    if ($id > 0) {
        echo '<blockquote>';
        echo '<div>瀏覽人次：' . $this->request->data['Point']['count_views'] . '</div>';
        echo '<div>相關行程數量：' . $this->request->data['Point']['count_schedules'] . '</div>';
        echo '<div>評論數：' . $this->request->data['Point']['count_comments'] . '</div>';
        echo '<div>連結數：' . $this->request->data['Point']['count_links'] . '</div>';
        echo '<div>評分數：' . $this->request->data['Point']['count_ranks'] . '</div>';
        echo '<div>建立時間：' . $this->request->data['Point']['created'] . '</div>';
        echo '<div>更新時間：' . $this->request->data['Point']['modified'] . '</div>';
        echo '</blockquote>';
    }
    ?>
</div>
<?php
$scripts = '
$(function() {
	$(\'.findLatLng\').click(function() {
		findLatLng($(\'#PointLatitude\'), $(\'#PointLongitude\'), $(this).prev().find(\'input\').val());
		return false;
	});
	$(\'.findAddress\').click(function() {
		var latitude = $(\'#PointLatitude\').val();
		var longitude = $(\'#PointLongitude\').val();
		if(latitude != \'\' && longitude != \'\') {
			$.get(\'' . $this->Html->url('/points/get_address/') . '\' + latitude + \'/\' + longitude,
			null, function(data) {
				$(\'#PointAddressEnUs\').val(data);
			});
		}
		return false;
	});
	$(\'#PointPointType\').parent().find(\'.checkbox\').addClass(\'span-3\');
        $(\'#PointTimeOpen,#PointTimeClose\').timepicker({timeOnly: true});
	';
if (!$replaceArea) {
    $scripts .= '
    $(\'#pointAreaEdit\').click(function() {
    	$(\'#pointArea\').load(\'' . $this->Html->url('/areas/getForm/Point') . '\');
    	return false;
	});';
} else {
    $scripts .= '
    $(\'#pointArea\').load(\'' . $this->Html->url('/areas/getForm/Point') . '\');';
}
$scripts .= '
});';
echo $this->Html->scriptBlock($scripts);