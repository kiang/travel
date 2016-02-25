<div class="block">
    <div>
        <div class="list2">
            <h2 class="fillet_all color2a">快速登入</h2>
        </div>
        <div class="clearfix"></div>
        <p class="lead"><a href="http://travel.olc.tw" title="就愛玩" style="color: #C00;">就愛玩</a>是一個以「旅遊排程」為主題的網站。<br />
            您可分享自己的旅遊行程，也可以透過行程、地點等資訊與朋友們交流！</p>
    </div>
    <div class="fields_s">
        <?php echo $this->Form->create('Member', array('action' => 'login', 'class' => 'form-inline')); ?>
        <dl class="list4">
            <dt class="bg_gary1">登入您的個人帳戶</dt>
            <dd style="padding: 40px;">
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">帳號</span>
                        <?php
                        echo $this->Form->input('Member.username', array(
                            'type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'span2',
                            'tabindex' => 1,
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="input-prepend">
                        <span class="add-on">密碼</span>
                        <?php
                        echo $this->Form->input('Member.password', array(
                            'type' => 'password',
                            'label' => false,
                            'div' => false,
                            'class' => 'span2',
                            'tabindex' => 1,
                        ));
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="line">
                    <?php
                    echo $this->Form->button('登入', array('type' => 'submit', 'class' => 'btn btn-primary'));
                    echo ' &nbsp; ' . $this->Html->link('忘記密碼', '/members/passwordForgotten/', array('class' => 'btn'));
                    ?>
                </div>
            </dd>
        </dl>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="fields_c">
        <div class="title2">
            <h2 class="spot spot_profile float-l">註冊加入本站會員</h2>
            <div class="clearfix"></div>
        </div>
        <div class="list1">
            <ul class="table">
                <li class="dTable"><span class="table_td1">行程：</span>瀏覽會員所分享的各種旅遊行程路線，並且方便您即時匯入自己的行程表中。</li>
                <li class="dTable"><span class="table_td1">地點：</span>瀏覽既有的地點資料庫，或自行新增地點，來充實您的旅行地圖。</li>
                <li class="dTable"><span class="table_td1">區域：</span>全球會員、行程與地點的分佈總覽。</li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <?php
        echo $this->Html->link('註冊', '/members/signup/', array('class' => 'btn btn-primary btn-large'));
        ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>