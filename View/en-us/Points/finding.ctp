<?php if (empty($offset)) { ?>
    <div id="pointFindingMain" class="fields_bg1">
    <?php } ?>
    <?php
    foreach ($items as $item) {
        $title = $this->Travel->getValue($item['Point'], 'title');
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
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <div class="block"><a id="pointFindingMore" class="dbtn dbtn3 fillet_all" href="#">More &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var pointFindingOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#pointFindingMore').click(function() {
                pointFindingOffset += 12;
                $.get('<?php echo $this->Html->url($url); ?>/' + pointFindingOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#pointFindingMore').hide();
                    } else {
                        $('div#pointFindingMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        -->
    </script>
<?php } ?>