<div style="width: 320px; background-color: #FFF; height: 100%; overflow-y: auto; overflow-x: hidden;" class="pull-left">
    <div style="margin: 10px;">
        <div id="Header"><h1 class="float-l"><?php
echo $this->Html->link('就愛玩', '/', array('title' => '就愛玩'));
?></h1>
            <div style="float:right; padding-right: 25px; padding-bottom: 40px;">
                <?php
                echo $this->Html->link('<i class="icon-arrow-left"></i> 返回', '/schedules/view/' . $schedule['Schedule']['id'], array(
                    'title' => '回到行程檢視介面',
                    'rel' => 'popover',
                    'data-placement' => 'bottom',
                    'data-content' => '點選這個按鈕會回到一般檢視介面',
                    'class' => 'hasPopover btn span1 backLink',
                    'escape' => false,
                ));
                echo '<br />' . $this->Html->link('<i class="icon-pencil"></i> 編輯', '/schedules/edit/' . $schedule['Schedule']['id'], array(
                    'title' => '進入進階行程編輯介面',
                    'rel' => 'popover',
                    'data-placement' => 'bottom',
                    'data-content' => '在進階行程編輯介面可以調整個別行程的細節',
                    'class' => 'hasPopover btn span1 editLink',
                    'escape' => false,
                ));
                ?>
            </div>
        </div>
        <div style="padding: 3px; margin-bottom: 20px;">
            <input type="text" placeholder="地址列" class="addressBar span3 hasPopover" rel="popover" data-placement="right" data-content="這裡可以輸入希望前往地點的住址或座標，如果搜尋成功就會在地圖上產生標示" data-original-title="住址與座標的搜尋" />
            <a href="#" class="addressBarGo btn btn-primary hasPopover" rel="popover" data-placement="right" data-content="在左邊地址列輸入文字後，點選這個按鈕就可以進行搜尋" data-original-title="開始搜尋"><i class="icon-search icon-white"></i> 搜尋 </a>
            <a href="#" class="btnTutorial btn"><i class="icon-question-sign"></i> 教學</a>
            <a href="#" class="showBounds btn hasPopover" rel="popover" data-placement="right" data-content="在檢視個別地點時，透過點選這個按鈕可以隨時回到所有地點的檢視畫面" data-original-title="顯示所有點"><i class="icon-bell"></i></a>
        </div>
        <div class="scheduleLineList clearfix"></div>
    </div>
</div>
<div id="map_canvas"></div>
<div id="tutorial" class="tutorial">
    <div data-target="input.addressBar" data-arrow="mr" data-location="tr">
        <h1>輸入一個地名</h1>
        <p>
            像是 "台北" 、 "東京" 、 "羅馬" ，也可以是住址 "5th Avenue, New York" 等
        </p>
    </div>
    <div data-target="a.addressBarGo" data-arrow="mr" data-location="tr">
        <h1>點選搜尋按鈕</h1>
        <p>
            是的，毫不猶豫的！
        </p>
    </div>
    <div data-target="div#map_canvas" data-arrow="ml" data-location="tr">
        <h1>地圖會出現標示</h1>
        <p>
            如果輸入的地名或地址能夠找到資料，地圖中就會產生一個標示
            <br /><?php echo $this->Html->image('tutorial/map_marker.png'); ?>
            <br /><a href="#" class="btn"><i class="icon-plus"></i>Add</a>
            <br />點選後會將這個地點加入目前行程
            <br /><a href="#" class="btn"><i class="icon-home"></i>Hotel</a>
            <br />點選後會將這個地點設為目前行程的住宿地點
            <br /><a href="#" class="btn"><i class="icon-folder-open"></i>Discovery</a>
            <br />點選後會以標示位置為中心，探索附近是否有可以推薦的地點
        </p>
    </div>
    <div data-target="select.scheduleDayList" data-arrow="mr" data-location="tr">
        <h1>在不同天之間切換</h1>
        <p>
            透過這個選單可以切換到其他天，或是選擇
            <br /><?php echo $this->Html->image('tutorial/day_switch.png'); ?>
            <br />可以直接新增一天空白的行程
        </p>
    </div>
    <div data-target="ul.scheduleLineList li" data-arrow="mr" data-location="tr">
        <h1>點選已新增的行程</h1>
        <p>
            <?php echo $this->Html->image('tutorial/schedule_line.png'); ?>
            <br />點選後地圖會聚焦在剛剛點選的位置
            <br /><?php echo $this->Html->image('tutorial/schedule_line_click.png'); ?>
            <br /><a href="#" class="btn"><i class="icon-folder-open"></i>Discovery</a>
            <br />點選後會以標示位置為中心，探索附近是否有可以推薦的地點
            <br /><a href="#" class="btn"><i class="icon-pencil"></i>Edit</a>
            <br />點選後會彈出下面這樣的名稱編輯對話框
            <br /><?php echo $this->Html->image('tutorial/schedule_line_edit.png'); ?>
            <br />點選 Save 就會儲存異動後的名稱
            <br /><a href="#" class="btn"><i class="icon-remove"></i>Delete</a>
            <br />點選後會從行程中刪除這個點
        </p>
    </div>
    <div data-target="a.showBounds" data-arrow="mr" data-location="tr">
        <h1>回到圖示總覽</h1>
        <p>
            點選這個圖示會回到本日總覽
        </p>
    </div>
    <div data-target="div#map_canvas" data-arrow="ml" data-location="tr">
        <h1>不知道住址？</h1>
        <p>
            在地圖上的任意位置透過滑鼠點選也可以產生標示來進行行程的操作！
        </p>
    </div>
</div>
<script id="dayListOptions" type="text/x-jquery-tmpl">
    <select class="scheduleDayList span3 hasPopover" rel="popover" data-placement="right" data-content="透過這個選單可以在多天的行程間切換" data-original-title="每日行程">
        {{each scheduleDays}}
        <option value="${$value.id}">[${$value.sort}] ${$value.title}</option>
        {{/each}}
        <option value="0">+ 新增一天</option>
    </select>
</script>
<script id="dayListEditForm" type="text/x-jquery-tmpl">
    <form class="form-inline" id="lineEdit${lineId}">
        <input type="text" value="${linePointName}" class="span3" />
        <a href="#" class="btn" data-id="${lineId}" data-day-id="${dayId}"><i class="icon-ok"></i> 儲存</a>
    </form>
</script>
<script id="pointsNearBackMarkerWindow" type="text/x-jquery-tmpl">
    <div>
        <a href="${wwwRoot}points/view/${pointId}" target="_blank">${pointTitle}</a><br />
        <div class="btn-group">
            <a href="#" onclick="quickAddLine('${lat}', '${lng}', '${theTitle}', '${pointId}'); return false;" class="btn"><i class="icon-plus"></i>新增</a>
            <a href="#" onclick="quickSetStay('${lat}', '${lng}', '${theTitle}', '${pointId}'); return false;" class="btn"><i class="icon-home"></i>旅館</a>
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>探索</a>
        </div>
    </div>
</script>
<script id="placeServiceMarkerWindow" type="text/x-jquery-tmpl">
    <div>
        <a href="${titleLink}" target="_blank">${pointTitle}</a><br />
        {{if photo}}
        <a href="${photo_url}" target="_blank"><img src="${photo}" width="200" border="0" /></a><br />
        {{/if}}
        <div class="btn-group">
            <a href="#" onclick="quickAddLine('${lat}', '${lng}', '${theTitle}'); return false;" class="btn"><i class="icon-plus"></i>新增</a>
            <a href="#" onclick="quickSetStay('${lat}', '${lng}', '${theTitle}'); return false;" class="btn"><i class="icon-home"></i>旅館</a>
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>探索</a>
        </div>
    </div>
</script>
<script id="scheduleDayListMarkerWindow" type="text/x-jquery-tmpl">
    <div>
        {{if pointId}}
        <a href="${wwwRoot}points/view/${pointId}" target="_blank">${pointTitle}</a><br />
        {{else}}
        <a href="https://www.google.com/search?q=${encodedTitle}" target="_blank">${pointTitle}</a><br />
        {{/if}}
        <div class="btn-group">
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>探索</a>
            <a href="#" onclick="scheduleDayListEdit('${lineId}'); return false;" class="btn"><i class="icon-pencil"></i>編輯</a>
            <a href="#" onclick="scheduleDayListDelete('${lineId}'); return false;" class="btn"><i class="icon-remove"></i>刪除</a>
        </div>
    </div>
</script>
<script type="text/javascript">
    var scheduleId = <?php echo $schedule['Schedule']['id']; ?>;
    var scheduleDays = <?php echo $this->JqueryEngine->value($scheduleDays); ?>;
    var scheduleDaysMap = <?php echo $this->JqueryEngine->value($scheduleDaysMap); ?>;
    var scheduleLines = <?php echo $this->JqueryEngine->value($scheduleLines); ?>;
    var currentBounds = new google.maps.LatLngBounds;
    var scheduleDayId = <?php echo $scheduleDayId; ?>;
    var collectedMarkers = [];
    $(schedulesMapMode);
</script>
<?php
$this->Html->script(array('markerwithlabel_packed', 'co/schedules/map_mode'), array('inline' => false));