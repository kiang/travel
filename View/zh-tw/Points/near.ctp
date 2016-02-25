<?php if(empty($items)) return ''; ?>
<?php if (empty($offset)) { ?>
    <div class="clearfix"></div>
    <div class="fields_bg1" id="pointNearMain">
    <?php } ?>
    <?php
    $pointStack = array();
    foreach ($items as $item) {
        $title = $this->Travel->getValue($item['Point'], 'title');
        $pointStack[$item['Point']['id']] = array(
            'title' => $title,
            'latitude' => $item['Point']['latitude'],
            'longitude' => $item['Point']['longitude'],
        );
        ?>
        <div class="fields_4">
            <div class="block">
                <div class="category categoryA01 float-l">&nbsp;</div>
                <div class="overspots"><?php
    echo $this->Html->link($title, '/points/view/' . $item['Point']['id'], array(
        'class' => 'nearPointLink',
        'rel' => $item['Point']['id'],
    ));
        ?></div>
                <div class="spot_stars"><?php
                echo $this->element('showRank', array('showRank' => $item['Point']['rank']));
        ?> ~<?php echo round($item['Point']['distance'], 2); ?>km</div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <p><a id="pointNearMore" class="dbtn dbtn3 fillet_all" href="#">瀏覽更多內容 &gt;&gt;</a></p>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            var pointNearOffset = <?php echo $offset; ?>;
            $('a#pointNearMore').click(function() {
                pointNearOffset += 12;
                var previousResult = '';
                $.get('<?php echo $this->Html->url($url); ?>/' + pointNearOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#pointNearMore').hide();
                    } else {
                        $('div#pointNearMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        //]]>
    </script>
<?php } ?>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        var points = <?php echo $this->JqueryEngine->value($pointStack); ?>;
        $('a.nearPointLink').click(function() {
            var pointId = $(this).attr('rel');
            olcNewPoint(map, {
                lat: points[pointId].latitude,
                lng: points[pointId].longitude,
                title: points[pointId].title,
                titleLink: this.href,
                refObject: this
            });
            $('div.spot_stars', $(this).parent().parent()).hide();
            return false;
        });
    });
    //]]>
</script>