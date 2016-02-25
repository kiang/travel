<div style="width: 320px; background-color: #FFF; height: 100%; overflow-y: auto; overflow-x: hidden;" class="pull-left">
    <div style="margin: 10px;">
        <div id="Header"><h1 class="float-l"><?php
echo $this->Html->link('就愛玩', '/', array('title' => '就愛玩'));
?></h1>
            <div style="float:right; padding-right: 25px; padding-bottom: 40px;">
                <?php
                echo $this->Html->link('<i class="icon-arrow-left"></i> Back', '/schedules/view/' . $schedule['Schedule']['id'], array(
                    'title' => 'Back to view the schedule',
                    'rel' => 'popover',
                    'data-placement' => 'bottom',
                    'data-content' => 'Click this button to view in normal mode',
                    'class' => 'hasPopover btn span1 backLink',
                    'escape' => false,
                ));
                echo '<br />' . $this->Html->link('<i class="icon-pencil"></i> Edit', '/schedules/edit/' . $schedule['Schedule']['id'], array(
                    'title' => 'Enter advanced editing interface',
                    'rel' => 'popover',
                    'data-placement' => 'bottom',
                    'data-content' => 'Within the interface, you could update more details for each line',
                    'class' => 'hasPopover btn span1 editLink',
                    'escape' => false,
                ));
                ?>
            </div>
        </div>
        <div style="padding: 3px; margin-bottom: 20px;">
            <input type="text" placeholder="Address bar" class="addressBar span3 hasPopover" rel="popover" data-placement="right" data-content="You could input address or city of the point you want to visit. The result will be marked in the map if we could find any record." data-original-title="Find a city or address" />
            <a href="#" class="addressBarGo btn btn-primary hasPopover" rel="popover" data-placement="right" data-content="After inputing text in address bar, you could click this button to start the search." data-original-title="Search now"><i class="icon-search icon-white"></i> GO </a>
            <a href="#" class="btnTutorial btn"><i class="icon-question-sign"></i>Tutorial</a>
            <a href="#" class="showBounds btn hasPopover" rel="popover" data-placement="right" data-content="When diving into specified point, you could return to all points view anytime by clicking this button." data-original-title="Show all points"><i class="icon-bell"></i></a>
        </div>
        <div class="scheduleLineList clearfix"></div>
    </div>
</div>
<div id="map_canvas"></div>
<div id="tutorial" class="tutorial">
    <div data-target="input.addressBar" data-arrow="mr" data-location="tr">
        <h1>Enter a name of place</h1>
        <p>
            Like "Taipei", "Tokyo", "Rome", or the address like "5th Avenue, New York"
        </p>
    </div>
    <div data-target="a.addressBarGo" data-arrow="mr" data-location="tr">
        <h1>Click "GO"</h1>
        <p>
            Yes, just do it!
        </p>
    </div>
    <div data-target="div#map_canvas" data-arrow="ml" data-location="tr">
        <h1>The marker will be added to map</h1>
        <p>
            If the name or address could be found, there will be a marker show up in the map
            <br /><?php echo $this->Html->image('tutorial/map_marker.png'); ?>
            <br /><a href="#" class="btn"><i class="icon-plus"></i>Add</a>
            <br />Click this button to add it to the day currently viewing
            <br /><a href="#" class="btn"><i class="icon-home"></i>Hotel</a>
            <br />Click this button to set it as accommodation of the day currently viewing
            <br /><a href="#" class="btn"><i class="icon-folder-open"></i>Discovery</a>
            <br />Click this button to discovery nearby area.
        </p>
    </div>
    <div data-target="select.scheduleDayList" data-arrow="mr" data-location="tr">
        <h1>Switch to different day</h1>
        <p>
            Use this menu to swithc to another day, or click
            <br /><?php echo $this->Html->image('tutorial/day_switch.png'); ?>
            <br />to add one new day.
        </p>
    </div>
    <div data-target="ul.scheduleLineList li" data-arrow="mr" data-location="tr">
        <h1>Click the line</h1>
        <p>
            <?php echo $this->Html->image('tutorial/schedule_line.png'); ?>
            <br />The focus of the map would be set to clicked point.
            <br /><?php echo $this->Html->image('tutorial/schedule_line_click.png'); ?>
            <br /><a href="#" class="btn"><i class="icon-folder-open"></i>Discovery</a>
            <br />Click this button to discovery nearby area.
            <br /><a href="#" class="btn"><i class="icon-pencil"></i>Edit</a>
            <br />Click this button to have a dialog editing the name
            <br /><?php echo $this->Html->image('tutorial/schedule_line_edit.png'); ?>
            <br />Click Save to update name of the point
            <br /><a href="#" class="btn"><i class="icon-remove"></i>Delete</a>
            <br />Click this button to delete selected point from itinerary.
        </p>
    </div>
    <div data-target="a.showBounds" data-arrow="mr" data-location="tr">
        <h1>Back to summary view</h1>
        <p>
            Click this button to view summary of the viewing day in the map.
        </p>
    </div>
    <div data-target="div#map_canvas" data-arrow="ml" data-location="tr">
        <h1>Don't know the address?</h1>
        <p>
            You could simply click on any position of the map to have a marker and doing the same operations of itinerary.
        </p>
    </div>
</div>
<script id="dayListOptions" type="text/x-jquery-tmpl">
    <select class="scheduleDayList span3 hasPopover" rel="popover" data-placement="right" data-content="Switch between days using this drop down menu" data-original-title="Switch days">
        {{each scheduleDays}}
        <option value="${$value.id}">[${$value.sort}] ${$value.title}</option>
        {{/each}}
        <option value="0">+ Add one day</option>
    </select>
</script>
<script id="dayListEditForm" type="text/x-jquery-tmpl">
    <form class="form-inline" id="lineEdit${lineId}">
        <input type="text" value="${linePointName}" class="span3" />
        <a href="#" class="btn" data-id="${lineId}" data-day-id="${dayId}"><i class="icon-ok"></i> Save</a>
    </form>
</script>
<script id="pointsNearBackMarkerWindow" type="text/x-jquery-tmpl">
    <div>
        <a href="${wwwRoot}points/view/${pointId}" target="_blank">${pointTitle}</a><br />
        <div class="btn-group">
            <a href="#" onclick="quickAddLine('${lat}', '${lng}', '${theTitle}', '${pointId}'); return false;" class="btn"><i class="icon-plus"></i>Add</a>
            <a href="#" onclick="quickSetStay('${lat}', '${lng}', '${theTitle}', '${pointId}'); return false;" class="btn"><i class="icon-home"></i>Hotel</a>
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>Discovery</a>
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
            <a href="#" onclick="quickAddLine('${lat}', '${lng}', '${theTitle}'); return false;" class="btn"><i class="icon-plus"></i>Add</a>
            <a href="#" onclick="quickSetStay('${lat}', '${lng}', '${theTitle}'); return false;" class="btn"><i class="icon-home"></i>Hotel</a>
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>Discovery</a>
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
            <a href="#" onclick="pointsNearby('${lat}', '${lng}'); return false;" class="btn"><i class="icon-folder-open"></i>Discovery</a>
            <a href="#" onclick="scheduleDayListEdit('${lineId}'); return false;" class="btn"><i class="icon-pencil"></i>Edit</a>
            <a href="#" onclick="scheduleDayListDelete('${lineId}'); return false;" class="btn"><i class="icon-remove"></i>Delete</a>
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