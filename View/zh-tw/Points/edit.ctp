<?php
echo $this->Form->create('Point', array('url' => array('action' => 'edit', $id)));
?>
<div class="clearfix"></div>
<div class="block"> <span class="mark_txt">※地點由會員共筆編撰，經站方審核後更新。</span>
    <dl class="list4">
        <dt class="bg_gary1">基本資訊</dt>
        <dd>
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
                <div class="control-group input-prepend span4">
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
            <div class="clearfix"></div>
        </dd>
        <dt class="bg_gary1">聯絡資訊</dt>
        <dd>
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
        </dd>
        <dt class="bg_gary1">交通資訊</dt>
        <dd>
            <div class="control-group">
                <div class="control-group span8">
                    <label class="add-on">所在區域</label>
                    <?php
                    echo $this->Form->hidden('Point.area_id', array('id' => 'PointArea1'));
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
                    <a id="pointAddLatLng" href="#" class="btn" title="以地址查詢Google Map上的座標"><i class="icon-search"></i> 座標</a>
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
        </dd>
    </dl>
    <div class="float-l">
        <a class="btn btn-primary dbtnSubmit" href="#" title="儲存本地點"><i class="icon-ok icon-white"></i> 儲存</a>
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
        $('#pointAddLatLng').click(function() {
            findLatLng($('#PointLatitude'), $('#PointLongitude'));
            return false;
        });
        $('input.timepick').timepicker({
            timeOnly:true
        });
<?php if (!$replaceArea) { ?>
            $('#pointAreaEdit').click(function() {
                $('#pointArea').load('<?php echo $this->Html->url('/areas/getForm/Point'); ?>');
                return false;
            });
<?php } else { ?>
            $('#pointArea').load('<?php echo $this->Html->url('/areas/getForm/Point'); ?>');
<?php } ?>
        
    });
    // -->
</script>