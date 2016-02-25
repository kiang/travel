<?php
$genderClass = 'spot_XY';
if (isset($this->request->data['Member']['gender']) && $this->request->data['Member']['gender'] === 'f') {
    $genderClass = 'spot_XX';
}
?>
<div class="block">
    <div id="Breadcrumb" class="spot <?php echo $genderClass; ?>"><?php
if (!empty($areas)) {
    foreach ($areas AS $area) {
        $this->Html->addCrumb($area['Area']['name'], '/areas/index/' . $area['Area']['id']);
    }
    if (!empty($this->request->data['Member']['nickname'])) {
        $this->Html->addCrumb($this->request->data['Member']['nickname']);
    } else {
        $this->Html->addCrumb($this->request->data['Member']['username']);
    }
    echo $this->Html->getCrumbs(' > ');
} else {
    echo '--';
}
?></div>
    <div class="fields_s">
        <div class="img-l"><?php
        echo $this->element('icon', array('iconData' => $this->request->data['Member'], 'iconSize' => 'm/'));
?></div>
    </div>
    <div class="fields_c">
        <div class="title2">
            <h2 class="spot spot_profile float-l">Profile</h2>
            <div class="clearfix"></div>
        </div>
        <div class="list1">
            <ul class="table">
                <li class="dTable"><span class="table_td2">Nickname: </span><?php
            if (!empty($this->request->data['Member']['nickname'])) {
                echo $this->request->data['Member']['nickname'];
            } else {
                echo $this->request->data['Member']['username'];
            }
?></li>
                <li><?php echo $this->request->data['Member']['intro']; ?></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <ul class="list1">
            <li class="txt_S color1b"><?php echo $this->request->data['Member']['created']; ?> created</li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<hr class="line" />
<div class="btn-group pull-right">
    <?php
    echo $this->Html->link('<i class="icon-arrow-left"></i> Back', '/areas', array(
        'class' => 'btn',
        'title' => 'Back to areas',
        'escape' => false,
    ));
    if ($this->request->data['Member']['id'] == $loginMember['id']) {
        echo $this->Html->link('<i class="icon-pencil"></i> Edit', '/members/edit', array(
            'title' => 'Edit personal profile and change the icon',
            'class' => 'btn dialogProfile',
            'escape' => false,
        ));
        $providers = array(
            'Facebook' => 'facebook',
            'Google' => 'google',
            'Flickr' => 'flickr',
            'GitHub' => 'github',
            'LinkedIn' => 'linkedin',
            );
        foreach ($providers AS $provider => $uri) {
            if(isset($oauths[$provider])) continue;
            echo $this->Html->link('<i class="icon-eye-open"></i> ' . $provider, '/auth/' . $uri, array(
                'class' => 'btn',
                'escape' => false,
            ));
        }
    }
    ?>
</div>
<div class="clearfix"></div>
<div class="block">
    <div id="memberViewTab">
        <ul>
            <li><?php
    echo $this->Html->link('Itineraries', '/schedules/member/' . $this->request->data['Member']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('Comments', '/comments/member/' . $this->request->data['Member']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('Favories', '/favorites/member/' . $this->request->data['Member']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('My comments', '/comments/member_log/' . $this->request->data['Member']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('My links', '/links/member/' . $this->request->data['Member']['id']);
    ?></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    <!--
    $(function(){
        $('div#memberViewTab').tabs({
            cache: true
        });
		
        $('a.dialogProfile').click(function() {
            dialogFull(this);
            return false;
        });
		
    });
    // -->
</script>