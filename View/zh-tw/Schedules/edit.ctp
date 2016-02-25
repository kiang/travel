<?php
echo $this->Form->create('Schedule', array('url' => array($id)));
echo $this->Form->input('Schedule.is_draft', array('type' => 'hidden'));
?>
<dl class="list4">
    <dt class="bg_gary1">行程基本資訊</dt>
    <dd style="padding: 10px;">
        <div class="control-group input-prepend span7">
            <label class="add-on">行程標題</label>
            <?php
            echo $this->Form->input('Schedule.title', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span2">
                <label class="add-on">活動天數</label>
                <?php
                echo $this->Form->input('Schedule.count_days', array(
                    'value' => $this->request->data['Schedule']['count_days'],
                    'readonly' => true,
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
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span3">
                <label class="add-on">出發時間</label>
                <?php
                echo $this->Form->input('Schedule.time_start', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span7">
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
        <div class="clearfix"></div>
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
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span7">
                <label class="add-on">行程簡介</label>
                <?php
                echo $this->Form->input('Schedule.intro', array(
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
        <div class="line">
            <div class="btn-group">
                <?php if($loginMember['id'] > 0) { ?>
                <a class="btn btn-primary dbtnSubmit hasPopover" title="公開發布這個行程" rel="popover" data-placement="top" data-content="如果希望行程讓人可以公開讀取，請點選 發表" href="#"><i class="icon-ok icon-white"></i> 發表</a>
                <?php } ?>
                <a class="btn dbtnDraft hasPopover" title="暫時保存這個行程" rel="popover" data-placement="top" data-content="草稿狀態只有自己看得到，適合私人行程，或是尚未準備好公開的行程" href="#"><i class="icon-lock"></i> 草稿</a>
                <?php
                echo $this->Html->link('<i class="icon-remove"></i> 刪除', '/schedules/delete/' . $id, array(
                    'title' => '刪除行程',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'top',
                    'data-content' => '在確認刪除後，行程相關資料都會移除，這個步驟將無法還原',
                    'escape' => false), '確定要刪除？');
                ?>
            </div>
        </div>
    </dd>
</dl>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(schedulesEdit);
    // -->
</script>
<?php
$this->Html->script(array('co/schedules/edit'), array('inline' => false));