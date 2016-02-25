<ul class="list1">
    <li><?php
echo $this->Html->link('返回', '/schedules/view/' .
        $scheduleId, array(
    'title' => '返回上一頁',
    'class' => 'icon icon_back',
));
?></li>
</ul>
<div class="clearfix"></div>
<?php
echo $this->Form->create('ScheduleDay', array('url' => array('action' => 'add', $scheduleId)));
?>
<dl class="list3 dTable">
    <dt class="table-cell fillet_left">
    <a href="#" class="olcSprite mDot lightbox_map">&nbsp;</a>
    </dt>
    <dd class="table-cell fillet_right">
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">本日名稱</label>
                <?php
                echo $this->Form->input('ScheduleDay.title', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
            <div class="control-group span4">
                <?php
                echo $this->Html->link('<i class="icon-search"></i> 交通工具', '/transports', array(
                    'title' => '交通工具參考表',
                    'class' => 'btn lightbox_page transportBtn',
                    'data-target' => 'ScheduleDayTransportId',
                    'escape' => false,
                ));
                echo $this->Form->hidden('ScheduleDay.transport_id');
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span5">
                <label class="add-on">住宿地點</label>
                <?php
                echo $this->Form->input('ScheduleDay.point_name', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                echo $this->Form->hidden('ScheduleDay.point_id');
                ?>
                <a class="btn hasPopover" rel="popover" data-placement="top" data-content="點選後會展開地圖輔助工具，在工具中輸入住址就可以嘗試找到對應經緯度" href="#" title="以地址查詢座標" id="scheduleDayLatLng"><i class="icon-search"></i> 座標</a>
                <div class="clearfix"></div>
            </div>
            <div class="control-group input-prepend span3">
                <label class="add-on">返回時間</label>
                <?php
                echo $this->Form->input('ScheduleDay.time_arrive', array(
                    'type' => 'text',
                    'div' => false,
                    'class' => 'span2 timepick',
                    'label' => false,
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">地理經度</label>
                <?php
                echo $this->Form->input('ScheduleDay.longitude', array(
                    'label' => false,
                    'div' => false,
                    'value' => 0,
                    'class' => 'span3',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span4">
                <label class="add-on">地理緯度</label>
                <?php
                echo $this->Form->input('ScheduleDay.latitude', array(
                    'label' => false,
                    'div' => false,
                    'value' => 0,
                    'class' => 'span3',
                ));
                ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-group input-prepend span8">
                <label class="add-on">備註說明</label>
                <?php
                echo $this->Form->input('ScheduleDay.note', array(
                    'type' => 'textarea',
                    'label' => false,
                    'div' => false,
                    'class' => 'span7',
                    'rows' => 3,
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </dd>
</dl>
<div class="clearfix"></div>
<div id="lineBlock">
    <div class="clearfix"></div>
    <dl class="list3 dTable">
        <div class="selecter">
            <ul class="list1 float-r">
                <li><a href="#" class="dbtn dbtn_add" title="新增地點">新增</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </dl>
</div>
<div class="clearfix"></div>
<div class="float-l">
    <a class="btn btn-primary dbtnSubmit" href="#" title="儲存本行程"><i class="icon-ok icon-white"></i> 儲存</a>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    //<![CDATA[
    $(scheduleDaysAdd);
    //]]>
</script>
<script id="lineTemplate" type="text/x-jquery-tmpl">
    <dl class="list3 dTable">
        <dt class="table-cell fillet_left">
        <a href="#" class="olcSprite lightbox_map">02</a>
        <a href="#" class="dbtn dbtn_move hasPopover" rel="popover" data-placement="top" data-content="透過滑鼠點選後可以上下拖曳，藉此調整行程的順序" title="拖曳調整順序">移動</a>
        <a href="#" class="dbtn dbtn_delete hasPopover" rel="popover" data-placement="right" data-content="點選後刪除這一個行程" title="刪除本地點">刪除</a>
        </dt>
        <dd class="table-cell fillet_right">
            <div class="control-group">
                <div class="control-group input-prepend span5">
                    <label class="add-on">地點名稱</label>
                    <?php
                    echo $this->Form->hidden('ScheduleLine.sort', array(
                        'name' => 'data[ScheduleLine][sort][]',
                        'id' => false,
                        'class' => 'lineSort',
                        'value' => '${lineSort}',
                    ));
                    echo $this->Form->hidden('ScheduleLine.point_id', array(
                        'name' => 'data[ScheduleLine][point_id][]',
                        'id' => false,
                        'class' => 'linePointId',
                    ));
                    echo $this->Form->input('ScheduleLine.point_name.', array(
                        'name' => 'data[ScheduleLine][point_name][]',
                        'id' => false,
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 linePoint',
                    ));
                    ?>
                    <a class="btn lineLatLon" href="#" title="以地址查詢Google Map上的座標"><i class="icon-search"></i> 座標</a>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group input-prepend span3">
                    <label class="add-on">停留時間</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.time_arrive', array(
                        'name' => 'data[ScheduleLine][time_arrive][]',
                        'id' => false,
                        'type' => 'text',
                        'div' => false,
                        'class' => 'input-small inline timepick',
                        'label' => false,
                    )) . '<span class="add-on">~</span>';
                    echo $this->Form->input('ScheduleLine.time_leave', array(
                        'name' => 'data[ScheduleLine][time_leave][]',
                        'id' => false,
                        'type' => 'text',
                        'div' => false,
                        'class' => 'input-small inline timepick',
                        'label' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">地理經度</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.longitude', array(
                        'name' => 'data[ScheduleLine][longitude][]',
                        'id' => false,
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 lineLon',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">地理緯度</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.latitude', array(
                        'name' => 'data[ScheduleLine][latitude][]',
                        'id' => false,
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 lineLat',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group span4">
                    <?php
                    echo $this->Html->link('<i class="icon-search"></i> 活動項目', '/activities', array(
                        'title' => '活動項目參考表',
                        'class' => 'btn lightbox_page activityBtn',
                        'data-target' => 'ScheduleLineActivityN${lineSort}',
                        'escape' => false,
                    ));
                    ?>
                    <input id="ScheduleLineActivityN${lineSort}" type="hidden" name="data[ScheduleLine][activity_id][]" value="0" />
                </div>
                <div class="control-group span4">
                    <?php
                    echo $this->Html->link('<i class="icon-search"></i> 交通工具', '/transports', array(
                        'title' => '交通工具參考表',
                        'class' => 'btn lightbox_page transportBtn',
                        'data-target' => 'ScheduleLineTransportN${lineSort}',
                        'escape' => false,
                    ));
                    ?>
                    <input id="ScheduleLineTransportN${lineSort}" type="hidden" name="data[ScheduleLine][transport_id][]" value="0" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">備註說明</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.note', array(
                        'name' => 'data[ScheduleLine][note][]',
                        'id' => false,
                        'type' => 'textarea',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                        'rows' => 3,
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
        <div class="selecter">
            <br /><br /><br /><br /><br />
            <ul class="list1 float-r">
                <li><a href="#" class="dbtn dbtn_add" title="新增地點">新增</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </dl>
</script>
<?php
$this->Html->script(array('co/schedule_days/add'), array('inline' => false));