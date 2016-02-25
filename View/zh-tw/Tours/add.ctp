<?php
echo $this->Form->create('Tour', array('url' => array('action' => 'add', $scheduleLineId, $from)));
?>
<div class="clearfix"></div>
<div class="block"> <span class="mark_txt">※旅行社由會員共筆編撰，經站方審核後更新。</span>
    <dl class="list4">
        <dt class="bg_gary1">基本資訊</dt>
        <dd>
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
            </div>
            <div class="clearfix"></div>
        </dd>
        <dt class="bg_gary1">聯絡資訊</dt>
        <dd>
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
        </dd>
        <dt class="bg_gary1">交通資訊</dt>
        <dd>
            <div class="control-group">
                <div class="control-group span8">
                    <label class="add-on">所在區域</label>
                    <?php
                    echo $this->Form->hidden('Tour.area_id', array('id' => 'TourArea1'));
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
                    <a id="tourAddLatLng" href="#" class="btn" title="以地址查詢Google Map上的座標"><i class="icon-search"></i> 座標</a>
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
        </dd>
    </dl>
    <div class="float-l">
        <a class="btn btn-primary dbtnSubmit" href="#" title="儲存本旅行社"><i class="icon-ok icon-white"></i> 儲存</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(function() {
        $('a.dbtnSubmit').click(function() {
            $(this).parents('form').submit();
            return false;
        });
        $('#tourAddLatLng').click(function() {
            findLatLng($('#TourLatitude'), $('#TourLongitude'));
            return false;
        });
        $('input.timepick').timepicker({
            timeOnly:true
        });
        $('#tourArea').load('<?php echo $this->Html->url('/areas/getForm/Tour'); ?>');
    });
    // -->
</script>