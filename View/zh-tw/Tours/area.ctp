<?php
if (empty($items))
    return;
if (empty($offset)) {
    ?>
    <div id="tourAreaMain">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="span3">名稱</th>
                    <th class="span4">電話</th>
                    <th>區域</th>
                </tr>
            </thead>
        </table>
        <?php } ?>
    <table class="table table-bordered">
        <?php
        foreach ($items AS $item) {
            ?>
            <tr>
                <td class="span3"><?php
            echo $this->Html->link($this->Travel->getValue($item['Tour'], 'title'), array('action' => 'view', $item['Tour']['id']));
            ?></td>
                <td class="span4"><?php echo $item['Tour']['telephone']; ?></td>
                <td><?php
                if (!empty($item['Area'])) {
                    $prefix = false;
                    foreach ($item['Area'] AS $area) {
                        if (!$prefix) {
                            $prefix = true;
                        } else {
                            echo ' > ';
                        }
                        echo $this->Html->link($area['Area']['name'], '/areas/index/' . $area['Area']['id'] . '#area_tours');
                    }
                }
            ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
<?php if (empty($offset)) { ?>
    </div>
    <div class="block"><a id="tourAreaMore" class="dbtn dbtn3 fillet_all" href="#">瀏覽更多內容 &gt;&gt;</a></div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            var tourAreaOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#tourAreaMore').click(function() {
                tourAreaOffset += 15;
                $.get('<?php echo $this->Html->url($url); ?>/' + tourAreaOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#tourAreaMore').hide();
                    } else {
                        $('div#tourAreaMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        -->
    </script>
<?php } ?>