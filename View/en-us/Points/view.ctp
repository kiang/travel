<?php
$title = $this->Travel->getValue($this->request->data['Point'], 'title');
echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url('/points/view/' . $this->request->data['Point']['id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:title', 'content' => $title), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:description',
    'content' => "{$title} is an interesting point"
        ), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:published_time', 'content' => $this->request->data['Point']['created']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:modified_time', 'content' => $this->request->data['Point']['modified']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:author', 'content' => $this->Html->url('/', true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:section', 'content' => 'points'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:tag', 'content' => 'location, point, poi'), null, array('inline' => false));
?>
<div class="block">
    <div id="Breadcrumb">&nbsp;<?php
if (!empty($areas)) {
    $prefix = false;
    foreach ($areas AS $area) {
        if (!$prefix) {
            $prefix = true;
        } else {
            echo ' > ';
        }
        echo $this->Html->link($area['Area']['name'], '/areas/index/' . $area['Area']['id'] . '#area_points');
    }
}
if (!empty($loginMember['id'])) {
    echo ' &nbsp; ' . $this->Html->link('>> Share another point', '/points/add', array('class' => 'btn btnPointAdd'));
}
?>&nbsp;</div>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="Map_dbtn"><a href="#" class="wide_map">Browse wide map</a></div>
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <div class="title2" id="InfoBoxContainer">
            <h2 class="spot spot_location float-l">Point information</h2>
            <div id="InfoBox">
                <div class="btn-group">
                    <a id="InfoBox_tab1" class="btn"><i class="icon-map-marker"></i> Coordinates</a>
                    <a id="InfoBox_tab2" class="btn"><i class="icon-share"></i> Share</a>
                </div>
                <div class="clearfix"></div>
                <div id="InfoBox_contentbox">
                    <div id="InfoBox_content1">
                        <dl>
                            <dt>Decimal</dt>
                            <dd>Longitude: <?php echo $this->request->data['Point']['longitude']; ?></dd>
                            <dd>Latitude: <?php echo $this->request->data['Point']['latitude']; ?></dd>
                            <dt>Degree/Minute/Second</dt>
                            <dd>Longitude: <?php echo $this->Travel->convertLongLat($this->request->data['Point']['longitude']); ?></dd>
                            <dd>Latitude: <?php echo $this->Travel->convertLongLat($this->request->data['Point']['latitude']); ?></dd>
                        </dl>
                        <div class="clearfix"></div>
                    </div>
                    <div id="InfoBox_content2">
                        <a name="fb_share" type="icon_link"></a>
                        <g:plusone size="small" annotation="inline"></g:plusone>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <dl class="dl-horizontal topbox">
            <dt>Title</dt>
            <dd>&nbsp;<?php
            if(!empty($this->request->data['Point']['title_en_us'])) {
                $title = $this->request->data['Point']['title_en_us'];
            }
        if (!empty($this->request->data['Point']['website'])) {
            echo $this->Html->link($title, $this->request->data['Point']['website'], array(
                'title' => 'Browse the official website',
                'target' => '_blank',
            ));
        } else {
            echo $title;
        }
?>
            </dd>
            <dt>Telephone: </dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Point']['telephone'];
?></dd>
            <dt>Fax: </dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Point']['fax'];
?></dd>
            <dt>Address: </dt>
            <dd>&nbsp;<?php
                $address = $this->Travel->getValue($this->request->data['Point'], 'address');
                if (!empty($this->request->data['Point']['postcode'])) {
                    echo "[{$this->request->data['Point']['postcode']}] ";
                }
                if (!empty($this->request->data['Point']['address_en_us'])) {
                    $address = $this->request->data['Point']['address_en_us'];
                }
                echo $address;
?></dd>
            <dt>Openning hours：</dt>
            <dd>&nbsp;<?php
                if ($this->request->data['Point']['time_open'] != '00:00:00') {
                    echo $this->request->data['Point']['time_open'];
                }
                if ($this->request->data['Point']['time_close'] != '00:00:00') {
                    echo ' ~ ' . $this->request->data['Point']['time_close'];
                }
                if (!empty($this->request->data['Point']['time_note'])) {
                    echo '<br />' . $this->request->data['Point']['time_note'];
                }
?></dd>
        </dl>
        <ul class="list1">
            <li class="txt_S color1b"><?php echo $this->request->data['Point']['created']; ?> posted</li>
            <li class="txt_S color1b"><?php echo $this->request->data['Point']['modified']; ?> updated</li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<hr class="line" />
<div class="btn-group float-r">
    <?php
    echo $this->Html->link('<i class="icon-chevron-left"></i> Back', '/points', array(
        'title' => 'Back to points list',
        'class' => 'btn',
        'escape' => false,
    ));
    if (!empty($loginMember['id'])) {
        echo $this->Html->link('<i class="icon-pencil"></i> Edit', array('action' => 'edit', $this->request->data['Point']['id']), array(
            'class' => 'btn',
            'title' => 'Edit this point',
            'escape' => false,
        ));
        echo $this->Html->link('<i class="icon-forward"></i> Import', array(
            'controller' => 'schedule_lines',
            'action' => 'push',
            'Point', $this->request->data['Point']['id']
                ), array(
            'class' => 'btn pointPush',
            'title' => 'Import this point to my itinerary',
            'escape' => false,
        ));
        echo '<span id="pointViewWatch"></span>';
    }
    ?>
</div>
<div class="clearfix"></div>
<div>
    <h4>Nearby points</h4>
    <p><?php
    echo $this->Html->link('Nearby points', '/points/near/Point/' . $this->request->data['Point']['id'], array(
        'title' => 'near points',
        'class' => 'auto_content',
    ));
    ?></p>
</div>
<div>
    <h4>Related links</h4>
    <p class="pointViewLinks"><?php
        echo $this->Html->link('Related links', '/links/point/' . $this->request->data['Point']['id'], array(
            'title' => 'related links',
            'class' => 'auto_content',
        ));
    ?></p>
</div>
<div>
    <h4>Related itineraries</h4>
    <p><?php
        echo $this->Html->link('Related itineraries', '/schedules/point/' . $this->request->data['Point']['id'], array(
            'title' => 'related schedules',
            'class' => 'auto_content',
        ));
    ?></p>
</div>
<div>
    <h4>Comments</h4>
    <p class="pointViewComments"><?php
        echo $this->Html->link('Comments', '/comments/point/' . $this->request->data['Point']['id'], array(
            'title' => 'point comments',
            'class' => 'auto_content',
        ));
    ?></p>
</div>
<script type="text/javascript">
    <!--
    var hideInfoBoxTime = 0;
    $(function(){
        $('a.auto_content').each(function() {
            var targetHref = this.href;
            var targetContainer = $(this).parent();
            $.get(targetHref, {}, function(result) {
                if('' == result) {
                    targetContainer.parent().html('');
                } else {
                    targetContainer.html(result);
                }
            });
        });
        $('#InfoBox_tab1').hover(function() {
            $('#InfoBox_content1').dialog({
                position: {
                    my: 'left top',
                    at: 'left bottom',
                    of: this
                }
            });
            $('#InfoBox_content2').dialog('close');
        });
        $('#InfoBox_tab2').hover(function() {
            $('#InfoBox_content2').dialog({
                position: {
                    my: 'left top',
                    at: 'left bottom',
                    of: $('#InfoBox_tab1')
                }
            });
            $('#InfoBox_content1').dialog('close');
        });
        $('a.pointPush').click(function() {
            dialogFull(this, 'Import the point to my itinerary');
            return false;
        });
        $('a.wide_map').click(function() {
            $('div#map').width('940px');
            $('div#map').parents('div.fields_2').css('width', '100%');
            google.maps.event.trigger(map, 'resize');
            map.fitBounds(bounds);
        });
        
<?php
if (!empty($this->request->data['Point']['latitude']) && !empty($this->request->data['Point']['longitude'])) {
    echo 'positionToMap(\'' . $this->request->data['Point']['latitude'] . '\', \'' . $this->request->data['Point']['longitude'] . '\', \'' . addslashes($title) . '\');';
}
if (!empty($loginMember['id'])) {
    ?>$('span#pointViewWatch').load('<?php echo $this->Html->url('/favorites/add/Point/' . $this->request->data['Point']['id']); ?>');<?php
}
if (!empty($area['Area']['id'])) {
    ?>
                $('a.btnPointAdd').click(function() {
                    var targetUrl = this.href;
                    $.get('<?php echo $this->Html->url('/areas/getForm/Point/1/' . $area['Area']['id']); ?>', [], function() {
                        location.href = targetUrl;
                    });
                    return false;
                });
    <?php
}
?>
	
    });
    // -->
</script>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<script type="text/javascript">
    window.___gcfg = {lang: 'en-US'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>