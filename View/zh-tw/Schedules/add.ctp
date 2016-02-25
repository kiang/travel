<?php
echo $this->Form->create('Schedule');
echo $this->Form->input('Schedule.is_draft', array('type' => 'hidden', 'value' => '0'));
?>
<dl class="list4">
    <dt class="bg_gary1">行程基本資訊</dt>

    <dd style="padding: 10px;">
        <div class="control-group input-prepend span8">
            <label class="add-on">行程標題</label>
            <?php
            echo $this->Form->input('Schedule.title', array(
                'value' => date('Y-m-d') . ' 的行程',
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span8',
            ));
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span2">
                <label class="add-on">活動天數</label>
                <?php
                echo $this->Form->input('Schedule.count_days', array(
                    'value' => '1',
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span2">
                <label class="add-on">參與人數</label>
                <?php
                echo $this->Form->input('Schedule.count_joins', array(
                    'value' => '1',
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span4">
                <label class="add-on">出發時間</label>
                <?php
                echo $this->Form->input('Schedule.time_start', array(
                    'type' => 'text',
                    'label' => false,
                    'value' => date('Y-m-d H:i:00'),
                    'div' => false,
                    'class' => 'span4',
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="advancedForm" style="display:none;">
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">出發地點</label>
                    <?php
                    echo $this->Form->input('Schedule.point_text', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    echo $this->Form->input('Schedule.point_id', array('type' => 'hidden'));
                    ?>
                    <a class="btn hasPopover" rel="popover" data-placement="top" data-content="點選後會展開地圖輔助工具，在工具中輸入住址就可以嘗試找到對應經緯度" href="#" title="以地址查詢座標" id="scheduleLatLng"><i class="icon-search"></i> 座標</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">地理經度</label>
                    <?php
                    echo $this->Form->input('Schedule.longitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">地理緯度</label>
                    <?php
                    echo $this->Form->input('Schedule.latitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">行程簡介</label>
                    <?php
                    echo $this->Form->input('Schedule.intro', array(
                        'type' => 'textarea',
                        'label' => false,
                        'div' => false,
                        'class' => 'span8',
                        'rows' => 3,
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="line" style="margin-left: 20px;">
            <div class="btn-group">
                <?php if($loginMember['id'] > 0) { ?>
                <a class="btn btn-primary dbtnSubmit hasPopover" title="公開發布這個行程" rel="popover" data-placement="top" data-content="如果希望行程讓人可以公開讀取，請點選 發表" href="#"><i class="icon-ok icon-white"></i> 發表</a>
                <?php } ?>
                <a class="btn dbtnDraft hasPopover" title="暫時保存這個行程" rel="popover" data-placement="top" data-content="草稿狀態只有自己看得到，適合私人行程，或是尚未準備好公開的行程" href="#"><i class="icon-lock"></i> 草稿</a>
                <a class="btn dbtnAdvanced hasPopover" title="展開進階表單" rel="popover" data-placement="top" data-content="如果已經熟悉相關操作，這個按鈕可以展開更多選項" href="#"><i class="icon-book"></i> 進階</a>
            </div>
        </div>
    </dd>
</dl>
<div class="advancedForm" style="display:none;">
    <div class="selecter">
        <div class="float-l">
            第 1 天
        </div>
        <ul class="list1 float-r">
            <li><a href="#" class="btn addLine" title="新增地點"><i class="icon-plus"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <dl class="list3 table">
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
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">地理緯度</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.latitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
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
    <div id="lineBlock"></div>
    <div class="clearfix"></div>
    <div class="selecter">
        <ul class="list1 float-r">
            <li><a href="#" class="btn addLine" title="新增地點"><i class="icon-plus"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <div class="float-l">
        <div class="btn-group">
            <?php if($loginMember['id'] > 0) { ?>
            <a class="btn btn-primary dbtnSubmit" href="#" title="公開發表本行程"><i class="icon-ok icon-white"></i> 發表</a>
            <?php } ?>
            <a class="btn dbtnDraft" href="#" title="僅暫存而不公開本行程"><i class="icon-lock"></i> 暫存</a>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(schedulesAdd);
    // -->
</script>
<script id="lineTemplate" type="text/x-jquery-tmpl">
    <dl class="list3 table">
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
                    <a class="btn lineLatLon hasPopover" rel="popover" data-placement="top" data-content="點選後會展開地圖輔助工具，在工具中輸入住址就可以嘗試找到對應經緯度" href="#" title="以地址查詢座標"><i class="icon-search"></i> 座標</a>
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
    </dl>
</script>
<?php
$this->Html->script(array('co/schedules/add'), array('inline' => false));