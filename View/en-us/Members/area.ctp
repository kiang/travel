<?php if (empty($offset)) { ?>
    <div class="TabBox">
        <div class="fields_3box fields_h_s" id="memberAreaMain">
        <?php } ?>
        <?php
        foreach ($items AS $item) {
            ?><div class="fields_3">
                <div class="block">
                    <div class="fields_4 float-l">
                        <div class="img-s float-l"><?php
        echo $this->element('icon', array('iconData' => $item['Member']));
        $genderClass = 'spot_XY';
        if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
            $genderClass = 'spot_XX';
        }
            ?></div>
                        <div class="spot overspots <?php echo $genderClass; ?>"><?php
                        echo $this->Html->link($item['Member']['nickname'], '/members/view/' . $item['Member']['id']);
            ?></div>
                        <div class="color1b">created: <?php echo $item['Member']['created']; ?></div>
                    </div>
                    <div class="clearfix"></div>
                    <p><?php echo $item['Member']['intro']; ?></p>
                    <ul class="list1 float-l">
                        <li><?php
                        echo $this->Html->link('Itineraries', '/members/view/' . $item['Member']['id'] . '#ui-tabs-1', array(
                            'class' => 'icon icon_route',
                            'title' => 'Itineraries shared by members',
                        ));
            ?></li>
                        <li><?php
                        echo $this->Html->link('Comments', '/members/view/' . $item['Member']['id'] . '#ui-tabs-4', array(
                            'class' => 'icon icon_comment',
                            'title' => 'Comments shared by members',
                        ));
            ?></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div><?php
                    }
        ?>
        <div class="clearfix"></div>
        <?php if (empty($offset)) { ?>
        </div>
        <div class="block"><a id="memberAreaMore" class="dbtn dbtn3 fillet_all" href="#">More &gt;&gt;</a></div>
        <div class="clearfix"></div>
        <script type="text/javascript">
            <!--
            $(function() {
                var memberAreaOffset = <?php echo $offset; ?>;
                var previousResult = '';
                $('a#memberAreaMore').click(function() {
                    memberAreaOffset += 18;
                    $.get('<?php echo $this->Html->url($url); ?>/' + memberAreaOffset, {}, function(result) {
                        if(previousResult === result) {
                            $('a#memberAreaMore').hide();
                        } else {
                            $('div#memberAreaMain').append(result);
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