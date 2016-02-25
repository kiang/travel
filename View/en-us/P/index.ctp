<div class="row">
    <div class="span4">
        <h2><?php
        echo $this->Html->link('Create an itinerary', '/schedules/add', array(
            'class' => 'dbtn_main dbtn_route',
        ));
        echo $this->Html->link('Create an itinerary', '/schedules/add');
        ?></h2>
        <p>We provided lots of tools to help you create a completed itinerary. You could simply add another line by searching an address or just click on map.<br /><?php
        echo $this->Html->image('create_itinerary.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
    <div class="span4">
        <h2><?php
        echo $this->Html->link('Find an itinerary', '/schedules', array(
            'class' => 'dbtn_main dbtn_area',
        ));
        echo $this->Html->link('Find an itinerary', '/schedules');
        ?></h2>
        <p>Any public shared itinerary could be the draft of your next travel plan. Either copy the whole one or pick up some days to import.<br /><?php
        echo $this->Html->image('find_itinerary.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
    <div class="span4">
        <h2><?php
        echo $this->Html->link('Find a point', '/points', array(
            'class' => 'dbtn_main dbtn_point',
        ));
        echo $this->Html->link('Find a point', '/points');
        ?></h2>
        <p>The points database here are shared. Every member could use, edit or provide one. So your itinerary could be lined with latest information.<br /><?php
        echo $this->Html->image('find_point.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
</div>