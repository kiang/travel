<?php if(empty($items) && empty($loginMember['id'])) return ''; ?>
<?php if ($offset === 0) { ?>
    <div class="clearfix"></div>
    <?php if (!empty($loginMember['id'])) { ?>
        <div id="linkControlMessage" class="mark_txt clear-b" style="display:none;"></div>
        <div class="fields_s">
            <?php
            $url = array('action' => 'add', 'Point', $pointId);
            echo $this->Form->create('Link', array('url' => $url));
            ?>
            <div class="form1 fillet_all">
                <?php
                echo '<p>' . $this->Form->input('Link.title', array(
                    'label' => 'Sub.',
                    'class' => 'fillet_all',
                    'div' => false,
                ));
                echo '</p><p>' . $this->Form->input('Link.url', array(
                    'label' => 'Url',
                    'class' => 'fillet_all',
                    'div' => false,
                ));
                echo '</p><p>' . $this->Form->input('Link.body', array(
                    'type' => 'textarea',
                    'label' => 'Desc.',
                    'class' => 'fillet_all',
                    'rows' => 5,
                    'div' => false,
                )) . '</p>';
                ?>
                <div class="clearfix"></div>
            </div>
            <p>
                <a href="#" class="btn linkSubmit"><i class="icon-ok"></i> Submit</a>
            </p>
            <?php
            echo $this->Form->end();
            ?>
        </div>
    <?php } ?>
<?php } ?>
<?php if (!empty($items)) { ?>
    <?php if ($offset === 0) { ?>
        <div class="<?php echo!empty($loginMember['id']) ? 'fields_c' : 'fields_1'; ?>">
            <dl class="list1" id="linkBlockPointMain">
            <?php } ?>
            <?php
            foreach ($items as $item) {
                ?>
                <dt>
                <div class="float-r"><?php
        if ($loginMember['id'] == $item['Link']['member_id']) {
            echo $this->Html->link('Delete', array('action' => 'delete', $item['Link']['id']), array(
                'class' => 'dbtn dbtn_X',
                'title' => 'Delete this comment',
                    ), 'Are you sure you want to delete this?');
        }
                ?></div>
                <?php
                echo $this->Html->link($item['Link']['title'], $item['Link']['url'], array(
                    'target' => '_blank'
                ));
                ?>
                </dt>
                <dd>
                    <div class="fields_4" style="float:right;">
                        <div class="img-s float-l"><?php
        echo $this->element('icon', array('iconData' => $item['Member']));
        $genderClass = 'spot_XY';
        if (isset($item['Member']['gender']) && $item['Member']['gender'] === 'f') {
            $genderClass = 'spot_XX';
        }
                ?></div>
                        <div class="spot overspots <?php echo $genderClass; ?>"><?php
                    echo $this->Html->link($item['Link']['member_name'], '/members/view/' . $item['Link']['member_id']);
                ?></div>
                        <div class="color1b txt_S">@ <?php echo $item['Link']['created']; ?></div>
                    </div>
                    <p><?php echo $item['Link']['body']; ?></p>
                    <div class="clearfix"></div>
                </dd>
                <?php
            }
            ?>
        </dl>
        <?php if ($offset === 0) { ?>
            <p><a class="dbtn dbtn3 fillet_all" href="#" id="linkBlockPointMore">More &gt;&gt;</a></p>
        </div>
    <?php } ?>
<?php } ?>
<?php if ($offset === 0) { ?>
    <div class="clearfix"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#rankingAtLink').load('<?php echo $this->Html->url('/ranks/add/rankingAtLink/Point/' . $pointId); ?>');
    <?php if (!empty($loginMember['id'])) { ?>
                var submitted = false;
                $('a.linkSubmit').click(function() {
                    $(this).parents('form').submit();
                    return false;
                });
                $('form#LinkPointForm').submit(function() {
                    if(false === submitted) {
                        submitted = true;
                        $('#linkControlMessage').hide();
                        $.post(wwwRoot + 'links/add/Point/<?php echo $pointId; ?>', $(this).serializeArray(), function(pageData) {
                            if(pageData == 'done') {
                                $('p.pointViewLinks').load(wwwRoot + 'links/point/<?php echo $pointId; ?>');
                            } else {
                                $('#linkControlMessage').html(pageData);
                                $('#linkControlMessage').show();
                                submitted = false;
                            }
                        });
                    }
                    return false;
                });
    <?php } ?>
            var linkBlockPointOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#linkBlockPointMore').click(function() {
                linkBlockPointOffset += 5;
                $.get(wwwRoot + 'links/point/<?php echo $pointId; ?>' + linkBlockPointOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#linkBlockPointMore').hide();
                    } else {
                        $('#linkBlockPointMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        })
        //]]>
    </script>
<?php } ?>