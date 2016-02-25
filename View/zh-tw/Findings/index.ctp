<?php
if (!empty($findingList)) {
    $findingResult = $findingList;
    end($findingList);
    $firstPoint = $findingList[key($findingList)];
}
?>
<div class="block">
    <div class="list2">
        <h2 class="fillet_all color2a">想去 <?php echo $keyword; ?> 玩？ </h2>
        <div class="clearfix"></div>
    </div>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <?php if (!empty($newFindings)) { ?>
            <div class="title2">
                <h2 class="spot spot_point float-l">熱門地點</h2>
                <div class="clearfix"></div>
            </div>
            <div class="list1">
                <ul>
                    <?php foreach ($newFindings AS $newFinding) { ?>
                        <li><a href="#" class="newFinding"><?php echo $newFinding['Finding']['keyword']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</div>
<hr class="line" />
<div class="block">
    <div id="findingIndexTabs">
        <ul>
            <?php if (!empty($firstPoint)) { ?>
                <li><?php
            echo $this->Html->link('附近地點', '/points/near/Finding/' . $firstPoint['id'], array(
                'id' => 'findingNearPoints'
            ));
                ?></li>
            <?php } ?>
            <?php if (!empty($keyword)) { ?>
                <li><?php
            echo $this->Html->link("相關行程", '/schedules/finding/' . $keyword);
                ?></li>
                <li><?php
                echo $this->Html->link("相關地點", '/points/finding/' . $keyword);
                ?></li>
            <?php } ?>
            <li><a href="#findingIndexList">探索記錄</a></li>
        </ul>
        <div class="fields_bg1" id="findingIndexList">
            <?php
            if (!empty($findingList)) {
                while (isset($findingList[key($findingList)])) {
                    $point = $findingList[key($findingList)];
                    ?><div class="fields_4">
                        <div class="block">
                            <div class="category categoryA01 float-l">&nbsp;</div>
                            <div class="overspots"><?php
            echo $this->Html->link($point['keyword'], '#', array(
                'class' => 'newFinding',
                'rel' => $point['id'],
            ));
                    ?></div>
                        </div>
                    </div><?php
                        prev($findingList);
                    }
                }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $('.newFinding').click(function() {
            $('#Search_bar').val($(this).html()).parents('form').submit();
            return false;
        });
        $('#findingIndexTabs').tabs({
            cache: true
        });
<?php if (!empty($findingList)) { ?>
            var findingResult = <?php echo $this->JqueryEngine->value($findingResult); ?>;
            positionToMap('<?php echo $firstPoint['latitude']; ?>', '<?php echo $firstPoint['longitude']; ?>', '<?php echo $firstPoint['keyword']; ?>');
            $('#findingIndexList a.findingPoint').click(function() {
                var findingHash = $(this).attr('rel');
                var clickPoint;
                $.each(findingResult, function(key, obj) {
                    if(key == findingHash) {
                        olcNewPoint(map, {
                            lat: obj.latitude,
                            lng: obj.longitude,
                            title: obj.keyword,
                            refObject: clickPoint
                        });
                        $('a#findingNearPoints').attr('href', '<?php echo $this->Html->url('/points/near/Finding/'); ?>' + obj.id);
                        $('#findingIndexTabs').tabs('load', 'ui-tabs-1');
                    }
                });
                return false;
            });
            $('a.FindingsControl').click(function() {
                $('#FindingsControlPanel').load(this.href, null, function() {
                    $('html, body').animate({
                        scrollTop: $('#FindingsControlPanel').offset().top
                    }, 2000);
                });
                return false;
            });
<?php } ?>
    })
    //]]>
</script>