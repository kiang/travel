<div class="row">
    <div class="span4">
        <h2><?php
        echo $this->Html->link('建行程', '/schedules/add', array(
            'class' => 'dbtn_main dbtn_route',
        ));
        echo $this->Html->link('建行程', '/schedules/add');
        ?></h2>
        <p>我們提供了許多工具來協助建立完整的行程，用住址做搜尋或是在地圖中點一下就可以放入行程表<br /><?php
        echo $this->Html->image('create_itinerary.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
    <div class="span4">
        <h2><?php
        echo $this->Html->link('找行程', '/schedules', array(
            'class' => 'dbtn_main dbtn_area',
        ));
        echo $this->Html->link('找行程', '/schedules');
        ?></h2>
        <p>任何公開的行程都可以是下一趟旅行的計畫草稿，可以整份複製，或是只從裡面挑個幾天出來匯入<br /><?php
        echo $this->Html->image('find_itinerary.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
    <div class="span4">
        <h2><?php
        echo $this->Html->link('找地點', '/points', array(
            'class' => 'dbtn_main dbtn_point',
        ));
        echo $this->Html->link('找地點', '/points');
        ?></h2>
        <p>網站上的景點資料是共享的，開放給所有會員使用、編修與提供，讓行程能夠連結最新的資訊<br /><?php
        echo $this->Html->image('find_point.png', array('class' => 'img-polaroid'));
        ?></p>
    </div>
</div>