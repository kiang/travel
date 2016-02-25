<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>就愛玩 | 自助旅行的第一站 | 客製化行程</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        echo $this->Html->meta(array('property' => 'og:site_name', 'content' => '就愛玩'));
        echo $this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url('/css/images/head_l.png', true)));
        echo $this->Html->meta('icon', $this->Html->webroot('/favicon.ico'));
        echo $this->Html->meta('keywords', '自助旅行,旅遊,行程,旅館,地點,背包,放假,休閒, backpacker, Travel, Schedules, Hotels, Points, Chinese Travel');
        echo $this->Html->meta('description', '就愛玩是個以旅遊為題的網站，在網站上您可以分享自己的旅遊行程、地點與旅館，以及這些旅遊資訊衍生的心得、評價等，我們試著提供在規劃旅遊時所需要取得的資訊與整理這些資訊的工具。

This website is built for Chinese users. We focus on topics around traveling, like sharing travel schedules, points and hotels. And even the comments, ranks after the travel. We try to offer the necessary information for travel planning and the tools to deal with them.');
        $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min',
            'jquery-ui', 'jquery.rating', 'css_frame', 'css_style', 'css_icon',
            'jquery-jvectormap'
                ), NULL, array('inline' => false, 'block' => 'layout_css'));
        echo $this->EasyCompressor->getLayoutCSS();
        echo $this->Html->css('css_default', NULL);
        ?><script type="text/javascript">
            var wwwRoot = '<?php echo $this->Html->url('/', true); ?>';
        </script>
        <?php
        if (Configure::read('App.offline')) {
            echo $this->Html->script('googlemap3_offline');
        } else {
            echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false');
        }
        $this->Html->script(array('php', 'jquery', 'jquery-ui',
            'bootstrap.min', 'jquery-ui-timepicker', 'jquery.tmpl.min',
            'jquery.rating.pack', 'latlng', 'map/jquery.ui.map.full.min',
            'googlemap3', 'travel'
                ), array('inline' => false, 'block' => 'layout_script'));
        echo $this->EasyCompressor->getLayoutScript();
        echo $this->Html->script('ui.datepicker-zh-TW');
        echo $this->EasyCompressor->getViewScript();
        echo $this->Html->script('jquery-jvectormap.min');
        echo $this->Html->script('jquery-jvectormap-world-mill-en.js');
        ?>
    </head>
    <body>
        <div class='container'>
            <!-- Header Start -->
            <div id="Header">
                <div class="block">
                    <div class="fields_s">
                        <h1 class="float-l"><?php
        echo $this->Html->link('就愛玩', '/', array('title' => '就愛玩 首頁'));
        ?></h1>
                        <div class="clearfix"></div>
                        <div class="btn-group">
                            <?php
                            if (!empty($loginMember['id'])) {
                                echo $this->Html->link('<i class="icon-user"></i> 我的首頁', '/members/view', array('class' => 'btn', 'escape' => false, 'style' => 'width: 65px;'));
                                echo $this->Html->link('<i class="icon-plane"></i> 登出', '/members/logout', array('class' => 'btn', 'escape' => false, 'style' => 'width: 65px;'));
                            } else {
                                echo $this->Html->link('<i class="icon-plane"></i> 登入', '/members/login', array('class' => 'btn', 'escape' => false, 'style' => 'width: 65px;'));
                                echo $this->Html->link('<i class="icon-user"></i> 註冊', '/members/signup', array('class' => 'btn', 'escape' => false, 'style' => 'width: 65px;'));
                            }
                            echo $this->Html->link('<i class="icon-flag"></i> English', '/members/l/en-us', array('class' => 'btn', 'escape' => false, 'style' => 'width: 65px;'));
                            ?>
                        </div>
                        <div id="Search" class="clearfix">
                            <?php
                            echo $this->Form->create('Finding', array('url' => '/findings', 'style' => 'margin: 0px;'));
                            echo $this->Form->text('keyword', array(
                                'id' => 'Search_bar',
                                'class' => 'fillet_all shadow-box1 txt_L float-l',
                                'placeholder' => '就愛玩...哪兒？',
                            ));
                            echo $this->Form->sumbit('GO', array(
                                'type' => 'submit',
                                'value' => 'GO',
                                'id' => 'Search_dbtn',
                                'class' => 'btn btn-primary'
                            ));
                            echo '<div class="clearfix"></div>';
                            echo $this->Form->end();
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <a class="btn-primary btn" href="http://osobiz.com/category/%E5%B0%B1%E6%84%9B%E7%8E%A9/" style="padding: 15px; font-size: 20px; font-weight: 900; width: 80%;" target="_blank">第一次來？</a>
                    </div>
                </div>
                <div id="vMap" class="span8" style="height: 300px;"></div>
                <div class="clearfix"></div>
                <hr class="line" />
            </div>
            <!-- Header End -->
            <div class="clearfix"></div>
            <!-- Main Start -->
            <div id="Main">
                <?php
                echo $this->Session->flash();
                echo $content_for_layout;
                ?>
            </div>
            <!-- Main End -->
            <div class="clearfix"></div>
            <!-- Footer Start -->
            <div id="Footer">
                <hr class="line" />
                <a href="#" title="回到最上面" class="dbtn_top fillet_bottom">▲TOP</a>
                <p>
                    <a href="http://cakephp.org/" target="_blank"><img src="http://travel.olc.tw/img/cake.power.gif" alt="CakePHP: the rapid development php framework" width="98" height="13" border="0"></a>
                    <a href="http://olc.tw/" target="_blank">就這間電腦工作室</a>
                    <a href="http://olc.tw/contact" target="_blank">聯絡我們</a>
                    <?php echo $this->Html->link('關於我們', '/pages/about_us'); ?>
                    <?php echo $this->Html->link('會員服務條款', '/pages/service_agreement'); ?>
                    <a href="http://osobiz.com/category/%E5%B0%B1%E6%84%9B%E7%8E%A9/" target="_blank">Blog</a>
                    <a href="https://www.facebook.com/travel.olc.tw" target="_blank">Facebook</a>
                    <a href="https://plus.google.com/113112425852599921933" target="_blank">Google+</a>
                </p>
            </div>
            <!-- Footer End -->
            <div class="clearfix"></div>
        </div>
        <script type="text/javascript">
            //<![CDATA[
            var codeMap =  <?php echo $this->JqueryEngine->value($codeMap); ?>;
            var codeMapColor =  <?php echo $this->JqueryEngine->value($codeMapColor); ?>;
            $(function() {
                $('form#FindingIndexForm').submit(function() {
                    if($('input#Search_bar').val() === '') {
                        return false;
                    }
                });
                $('#vMap').vectorMap({
                    map: 'world_mill_en',
                    backgroundColor: '#CCCCCC',
                    series: {
                        regions: [{
                                values: codeMapColor,
                                scale: ['#C8EEFF', '#0071A4'],
                                max: 50,
                                min: 0
                            }]
                    },
                    onRegionClick: function(e, code) {
                        location.href = wwwRoot + 'areas/index/' + codeMap[code]['id'];
                    },
                    onRegionLabelShow: function(e, label, code) {
                        var origLabel = label.html();
                        if(undefined !== codeMap[code]) {
                            origLabel += '<br />行程： ' + codeMap[code]['countSchedule'];
                            origLabel += '<br />地點： ' + codeMap[code]['countPoint'];
                        }
                        label.html(origLabel);
                    }
                });
            })
            
            //]]>
        </script>
    </body>
</html>