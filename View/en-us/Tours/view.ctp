<?php
$title = $this->Travel->getValue($this->request->data['Tour'], 'title');
echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url('/tours/view/' . $this->request->data['Tour']['id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:title', 'content' => $title), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:description',
    'content' => "{$title} is an interesting tour"
        ), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:published_time', 'content' => $this->request->data['Tour']['created']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:modified_time', 'content' => $this->request->data['Tour']['modified']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:author', 'content' => $this->Html->url('/', true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:section', 'content' => 'tours'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:tag', 'content' => 'location, tour, poi'), null, array('inline' => false));
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
        echo $this->Html->link($area['Area']['name'], '/areas/index/' . $area['Area']['id'] . '#area_tours');
    }
}
if (!empty($loginMember['id'])) {
    echo ' &nbsp; ' . $this->Html->link('>> Share another tour', '/tours/add', array('class' => 'btn btnTourAdd'));
}
?>&nbsp;</div>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <div class="title2" id="InfoBoxContainer">
            <h2 class="spot spot_location float-l">Tour information</h2>
            <div class="clearfix"></div>
        </div>
        <dl class="dl-horizontal topbox">
            <dt>Title</dt>
            <dd>&nbsp;<?php
            if(!empty($this->request->data['Tour']['title_en_us'])) {
                $title = $this->request->data['Tour']['title_en_us'];
            }
        if (!empty($this->request->data['Tour']['website'])) {
            echo $this->Html->link($title, $this->request->data['Tour']['website'], array(
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
                echo $this->request->data['Tour']['telephone'];
?></dd>
            <dt>Fax: </dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Tour']['fax'];
?></dd>
            <dt>Email: </dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Tour']['email'];
?></dd>
            <dt>Address: </dt>
            <dd>&nbsp;<?php
                $address = $this->Travel->getValue($this->request->data['Tour'], 'address');
                if (!empty($this->request->data['Tour']['postcode'])) {
                    echo "[{$this->request->data['Tour']['postcode']}] ";
                }
                if (!empty($this->request->data['Tour']['address_en_us'])) {
                    $address = $this->request->data['Tour']['address_en_us'];
                }
                echo $address;
?></dd>
        </dl>
        <ul class="list1">
            <li class="txt_S color1b"><?php echo $this->request->data['Tour']['created']; ?> posted</li>
            <li class="txt_S color1b"><?php echo $this->request->data['Tour']['modified']; ?> updated</li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<hr class="line" />
<div class="btn-group float-r">
    <?php
    echo $this->Html->link('<i class="icon-chevron-left"></i> Back', '/tours', array(
        'title' => 'Back to tours list',
        'class' => 'btn',
        'escape' => false,
    ));
    if (!empty($loginMember['id'])) {
        echo $this->Html->link('<i class="icon-pencil"></i> Edit', array('action' => 'edit', $this->request->data['Tour']['id']), array(
            'class' => 'btn',
            'title' => 'Edit this tour',
            'escape' => false,
        ));
    }
    ?>
</div>
<div class="clearfix"></div>
<div>
    <h4>Related links</h4>
    <p class="tourViewLinks"><?php
        echo $this->Html->link('Related links', '/links/tour/' . $this->request->data['Tour']['id'], array(
            'title' => 'related links',
            'class' => 'auto_content',
        ));
    ?></p>
</div>
<div>
    <h4>Comments</h4>
    <p class="tourViewComments"><?php
        echo $this->Html->link('Comments', '/comments/tour/' . $this->request->data['Tour']['id'], array(
            'title' => 'tour comments',
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
<?php
if (!empty($this->request->data['Tour']['latitude']) && !empty($this->request->data['Tour']['longitude'])) {
    echo 'positionToMap(\'' . $this->request->data['Tour']['latitude'] . '\', \'' . $this->request->data['Tour']['longitude'] . '\', \'' . addslashes($title) . '\');';
}
if (!empty($loginMember['id'])) {
    ?>$('span#tourViewWatch').load('<?php echo $this->Html->url('/favorites/add/Tour/' . $this->request->data['Tour']['id']); ?>');<?php
}
if (!empty($area['Area']['id'])) {
    ?>
                $('a.btnTourAdd').click(function() {
                    var targetUrl = this.href;
                    $.get('<?php echo $this->Html->url('/areas/getForm/Tour/1/' . $area['Area']['id']); ?>', [], function() {
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