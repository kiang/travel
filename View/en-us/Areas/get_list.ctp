<div id="AreasGetList">
    <div id="areaControlMessage" class="error" style="display:none;"></div>
    <div class="fields_1">
        <dl class="list1">
            <?php
            $ownerCheck = ($loginMember['id'] == $owner || $loginMember['group_id'] == 1);
            if (!empty($areas)) {
                foreach ($areas AS $area) {
                    $prefix = false;
                    $areaTitle = '';
                    foreach ($area['Area'] AS $subArea) {
                        if (!$prefix) {
                            $prefix = true;
                        } else {
                            $areaTitle .= ' > ';
                        }
                        $areaTitle .= $subArea['Area']['name'];
                    }
                    ?><dt>
                    <div class="float-r"><?php
            if ($ownerCheck) {
                echo $this->Html->link('Remove', array('action' => 'del', $area['AreasModel']['id']), array(
                    'class' => 'dbtn dbtn_X areaRemoveControl',
                    'title' => 'Click here to remove this area',
                ));
            }
                    ?></div>
                    <?php echo $areaTitle; ?>
                    </dt><?php
        }
    }
    if ($ownerCheck && count($areas) < 10) {
        echo '<div class="clearfix"></div>' . $this->Html->link('Add', array_merge(array('action' => 'add'), $url), array(
            'class' => 'icon icon_plus AreasControl',
            'title' => 'Click here to add an area',
        ));
    }
            ?>
        </dl>
    </div>
    <div id="AreasControlPanel"></div>
    <script type="text/javascript">
        <!--
        $(function() {
            $('a.AreasControl').click(function() {
                $('#AreasControlPanel').load(this.href);
                return false;
            });
            $('a.areaRemoveControl').click(function() {
                $('#areaControlMessage').hide();
                $.get(this.href, null, function(pageData) {
                    if(pageData == 'done') {
                        $('div#AreasGetList').load('<?php echo $this->Html->url($url); ?>');
                    } else {
                        $('#areaControlMessage').html(pageData);
                        $('#areaControlMessage').show();
                    }
                });
                return false;
            });
        });
        // -->
    </script>
    <div class="clearfix"></div>
</div>