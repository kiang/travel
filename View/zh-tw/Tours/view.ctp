<?php
$title = $this->Travel->getValue($this->request->data['Tour'], 'title');
echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url('/tours/view/' . $this->request->data['Tour']['id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:title', 'content' => $title), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:description',
    'content' => "{$title} 是一個在地的旅行社"
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
    echo ' &nbsp; ' . $this->Html->link('>> 分享另一個旅行社', '/tours/add', array('class' => 'btn btnTourAdd'));
}
?>&nbsp;</div>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <div class="title2" id="InfoBoxContainer">
            <h2 class="spot spot_location float-l">旅行社資訊</h2>
            <div class="clearfix"></div>
        </div>
        <dl class="dl-horizontal topbox">
            <dt>名稱：</dt>
            <dd>&nbsp;<?php
        if (!empty($this->request->data['Tour']['website'])) {
            echo $this->Html->link($title, $this->request->data['Tour']['website'], array(
                'title' => '瀏覽本旅行社網站',
                'target' => '_blank',
            ));
        } else {
            echo $title;
        }
        if (!empty($this->request->data['Tour']['title_en_us'])) {
            echo '<a href="#" class="dbtn dbtn1 fillet_all" title="' . $this->request->data['Tour']['title_en_us'] . '">En</a>';
        }
?>
            </dd>
            <dt>電話：</dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Tour']['telephone'];
?></dd>
            <dt>傳真：</dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Tour']['fax'];
?></dd>
            <dt>Email：</dt>
            <dd>&nbsp;<?php
                echo $this->request->data['Tour']['email'];
?></dd>
            <dt>地址：</dt>
            <dd>&nbsp;<?php
                $address = $this->Travel->getValue($this->request->data['Tour'], 'address');
                if (!empty($this->request->data['Tour']['postcode'])) {
                    echo "[{$this->request->data['Tour']['postcode']}] ";
                }
                echo $address;
                if (!empty($this->request->data['Tour']['address_en_us'])) {
                    echo '<a href="#" class="dbtn dbtn1 fillet_all" title="' . $this->request->data['Tour']['address_en_us'] . '">En</a>';
                }
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
    echo $this->Html->link('<i class="icon-chevron-left"></i> 返回', '/tours', array(
        'title' => '返回上一頁',
        'class' => 'btn',
        'escape' => false,
    ));
    if (!empty($loginMember['id'])) {
        echo $this->Html->link('<i class="icon-pencil"></i> 編輯', array('action' => 'edit', $this->request->data['Tour']['id']), array(
            'class' => 'btn',
            'title' => '編輯旅行社的詳細內容',
            'escape' => false,
        ));
    }
    ?>
</div>
<div class="clearfix"></div>
<div>
    <h4>相關連結</h4>
    <p class="tourViewLinks"><?php
        echo $this->Html->link('相關連結', '/links/tour/' . $this->request->data['Tour']['id'], array(
            'title' => 'related links',
            'class' => 'auto_content',
        ));
    ?></p>
</div>
<div>
    <h4>留言評論</h4>
    <p class="tourViewComments"><?php
        echo $this->Html->link('留言評論', '/comments/tour/' . $this->request->data['Tour']['id'], array(
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