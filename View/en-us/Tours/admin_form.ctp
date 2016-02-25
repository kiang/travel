<div class="Tours form">
    <h3><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>旅行社</h3>
    <?php
    if ($id > 0) {
        echo $this->Form->input('Tour.id');
    }
    ?>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">啟用</label>
            <?php
            echo $this->Form->input('Tour.is_active', array(
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
            echo $this->Form->input('Tour.title_zh_tw', array(
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
            echo $this->Form->input('Tour.title_en_us', array(
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
            echo $this->Form->input('Tour.title', array(
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
            echo $this->Form->input('Tour.fax', array(
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
            <label class="add-on">連絡電話</label>
            <?php
            echo $this->Form->input('Tour.telephone', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">連絡信箱</label>
            <?php
            echo $this->Form->input('Tour.email', array(
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
            echo $this->Form->input('Tour.website', array(
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
            echo $this->Form->input('Tour.area_id', array(
                'type' => 'text',
                'id' => 'TourArea1',
                'label' => false,
                'div' => false,
                'class' => 'span1',
            ));
            ?>
        </div>
        <div class="control-group span6">
            <label class="add-on">所在區域</label>
            <?php
            if (!empty($this->validationErrors['Tour']['area_id'][0])) {
                echo '<div id="tourArea" class="error table-cell table_td_85p">';
            } else {
                echo '<div id="tourArea" class="table-cell table_td_85p">';
            }
            if (!empty($areaPath)) {
                echo implode(' >> ', Set::extract('{n}.Area.name', $areaPath));
                echo $this->Html->link('(修改)', '#', array('id' => 'tourAreaEdit'));
                $replaceArea = false;
            } else {
                $replaceArea = true;
            }
            echo '</div>';
            if (!empty($this->validationErrors['Tour']['area_id'][0])) {
                echo '<div class="error-message error">' . $this->validationErrors['Tour']['area_id'][0] . '</div>';
            }
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">地理經度</label>
            <?php
            echo $this->Form->input('Tour.longitude', array(
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">地理緯度</label>
            <?php
            echo $this->Form->input('Tour.latitude', array(
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
            echo $this->Form->input('Tour.postcode', array(
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
            echo $this->Form->input('Tour.address_zh_tw', array(
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
            echo $this->Form->input('Tour.address_en_us', array(
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
            echo $this->Form->input('Tour.address', array(
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
        echo '<div>瀏覽人次：' . $this->request->data['Tour']['count_views'] . '</div>';
        echo '<div>相關行程數量：' . $this->request->data['Tour']['count_schedules'] . '</div>';
        echo '<div>評論數：' . $this->request->data['Tour']['count_comments'] . '</div>';
        echo '<div>連結數：' . $this->request->data['Tour']['count_links'] . '</div>';
        echo '<div>評分數：' . $this->request->data['Tour']['count_ranks'] . '</div>';
        echo '<div>建立時間：' . $this->request->data['Tour']['created'] . '</div>';
        echo '<div>更新時間：' . $this->request->data['Tour']['modified'] . '</div>';
        echo '</blockquote>';
    }
    ?>
</div>
<?php
$scripts = '
$(function() {
	$(\'.findLatLng\').click(function() {
		findLatLng($(\'#TourLatitude\'), $(\'#TourLongitude\'), $(this).prev().find(\'input\').val());
		return false;
	});
	$(\'.findAddress\').click(function() {
		var latitude = $(\'#TourLatitude\').val();
		var longitude = $(\'#TourLongitude\').val();
		if(latitude != \'\' && longitude != \'\') {
			$.get(\'' . $this->Html->url('/tours/get_address/') . '\' + latitude + \'/\' + longitude,
			null, function(data) {
				$(\'#TourAddressEnUs\').val(data);
			});
		}
		return false;
	});
        $(\'#TourTimeOpen,#TourTimeClose\').timepicker({timeOnly: true});
	';
if (!$replaceArea) {
    $scripts .= '
    $(\'#tourAreaEdit\').click(function() {
    	$(\'#tourArea\').load(\'' . $this->Html->url('/areas/getForm/Tour') . '\');
    	return false;
	});';
} else {
    $scripts .= '
    $(\'#tourArea\').load(\'' . $this->Html->url('/areas/getForm/Tour') . '\');';
}
$scripts .= '
});';
echo $this->Html->scriptBlock($scripts);