<?php if ($offset === 0) { ?>
    <h4>Collected Feeds</h4>
    <div>
        <div id="indexChannelMain">
        <?php } ?>
        <?php
        foreach ($items AS $item) {
            ?>
            <h4><?php
        echo $this->Html->link($item['Channel']['title'], $item['Channel']['url'], array(
            'target' => '_blank'
        ));
            ?></h4>
            <div class="badge"><i class="icon-calendar">&nbsp;</i><?php echo $item['Channel']['the_date']; ?></div>
            <p><?php echo $item['Channel']['summary']; ?></p>
            <?php
            if (!empty($item['Schedule'])) {
                echo '<h5>Related itineraries:</h5><ul>';
                foreach ($item['Schedule'] AS $link) {
                    echo '<li>' . $this->Html->link($link['foreign_title'], '/' . $link['controller'] . '/view/' . $link['foreign_key']) . '</li>';
                }
                echo '</ul>';
            }
            if (!empty($item['Point'])) {
                echo '<h5>Related points:</h5><ul>';
                foreach ($item['Point'] AS $link) {
                    echo '<li>' . $this->Html->link($link['foreign_title'], '/' . $link['controller'] . '/view/' . $link['foreign_key']) . '</li>';
                }
                echo '</ul>';
            }
            ?>
            <div class="clearfix"></div>
            <?php
        }
        ?>
        <?php if ($offset === 0) { ?>
        </div>
        <div class="block"><a class="dbtn dbtn3 fillet_all" href="#" id="indexChannelMore">More &gt;&gt;</a></div>
        <div class="clearfix"></div>
        <script type="text/javascript">
            <!--
            $(function() {
                var indexChannelOffset = <?php echo $offset; ?>;
                var previousResult = '';
                $('a#indexChannelMore').click(function() {
                    indexChannelOffset += 5;
                    $.get('<?php echo $this->Html->url('/channels/index/'); ?>' + indexChannelOffset, {}, function(result) {
                        if(previousResult === result) {
                            $('a#indexChannelMore').hide();
                        } else {
                            $('div#indexChannelMain').append(result);
                            previousResult = result;
                        }
                    });
                    return false;
                });
            });
            -->
        </script>
    </div>
<?php } ?>