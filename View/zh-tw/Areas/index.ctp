<?php
echo $this->Form->hidden('Member.Area.1', array('value' => $id));
?>
<div id="AreaIndexBreadcrumb" class="block"><?php
foreach ($parents AS $area) {
    $this->Html->addCrumb($area['Area']['name'], '/areas/index/' . $area['Area']['id']);
}
echo $this->Html->getCrumbs(' > ');
echo ' &nbsp; ' . $this->Html->link('(修改)', '#', array('id' => 'AreaIndexEdit'));
?></div>
<div>
    <div id="areaIndexTab">
        <ul>
            <li><?php
    echo $this->Html->link('行程', '/schedules/area/' . $id, array(
        'title' => 'area schedules',
    ));
?></li>
            <li><?php
                echo $this->Html->link('地點', '/points/area/' . $id, array(
                    'title' => 'area points',
                ));
?></li>
            <li><?php
                echo $this->Html->link('旅行社', '/tours/area/' . $id, array(
                    'title' => 'area tours',
                ));
?></li>
            <li><?php
                echo $this->Html->link('會員', '/members/area/' . $id, array(
                    'title' => 'area members',
                ));
?></li>
        </ul>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    <!--
    $(function(){
        $('div#areaIndexTab').tabs({
            cache: true,
            create: function() {
                $('ul', this).wrap($('<div class="block">'));
            }
        });
        $('#AreaIndexEdit').click(function() {
            $('#AreaIndexBreadcrumb').load('<?php echo $this->Html->url('/areas/getForm/Member'); ?>', function() {
                $(this).append('<input type="button" id="AreaIndexEditButton" value="GO" />');
                $('#AreaIndexEditButton', this).click(function() {
                    var selectedArea = $('input#MemberArea1').val();
                    var selectedTab = $('div#areaIndexTab').tabs('option', 'selected');
                    $('div#Main').load('<?php echo $this->Html->url('/areas/index/'); ?>' + selectedArea, function() {
                        $('div#areaIndexTab').tabs('select', selectedTab);
                    });
                });
            });
            return false;
        });
    });
    // -->
</script>