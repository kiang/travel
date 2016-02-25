<?php if (empty($offset)) { ?>
    <div id="tourFindingMain" class="fields_bg1">
    <?php } ?>
    <?php
    foreach ($items as $item) {
        $title = $this->Travel->getValue($item['Tour'], 'title');
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
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <?php if (empty($offset)) { ?>
    </div>
    <div class="block"><a id="tourFindingMore" class="dbtn dbtn3 fillet_all" href="#">More &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var tourFindingOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#tourFindingMore').click(function() {
                tourFindingOffset += 12;
                $.get('<?php echo $this->Html->url($url); ?>/' + tourFindingOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#tourFindingMore').hide();
                    } else {
                        $('div#tourFindingMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        -->
    </script>
<?php } ?>