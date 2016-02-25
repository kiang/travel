<?php if(empty($items)) return ''; ?>
<?php if (empty($offset)) { ?>
    <div class="clearfix"></div>
    <div class="fields_bg1" id="tourNearMain">
    <?php } ?>
    <?php
    $tourStack = array();
    foreach ($items as $item) {
        $title = $this->Travel->getValue($item['Tour'], 'title');
        $tourStack[$item['Tour']['id']] = array(
            'title' => $title,
            'latitude' => $item['Tour']['latitude'],
            'longitude' => $item['Tour']['longitude'],
        );
        ?>
        <div class="fields_4">
            <div class="block">
                <div class="category categoryA01 float-l">&nbsp;</div>
                <div class="overspots"><?php
    echo $this->Html->link($title, '/tours/view/' . $item['Tour']['id'], array(
        'class' => 'nearTourLink',
        'rel' => $item['Tour']['id'],
    ));
        ?></div>
                <div class="spot_stars"><?php
                echo $this->element('showRank', array('showRank' => $item['Tour']['rank']));
        ?> ~<?php echo round($item['Tour']['distance'], 2); ?>km</div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <p><a id="tourNearMore" class="dbtn dbtn3 fillet_all" href="#">More &gt;&gt;</a></p>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            var tourNearOffset = <?php echo $offset; ?>;
            $('a#tourNearMore').click(function() {
                tourNearOffset += 12;
                var previousResult = '';
                $.get('<?php echo $this->Html->url($url); ?>/' + tourNearOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#tourNearMore').hide();
                    } else {
                        $('div#tourNearMain').append(result);
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
        var tours = <?php echo $this->JqueryEngine->value($tourStack); ?>;
        $('a.nearTourLink').click(function() {
            var tourId = $(this).attr('rel');
            olcNewTour(map, {
                lat: tours[tourId].latitude,
                lng: tours[tourId].longitude,
                title: tours[tourId].title,
                titleLink: this.href,
                refObject: this
            });
            $('div.spot_stars', $(this).parent().parent()).hide();
            return false;
        });
    });
    //]]>
</script>