<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article#">
        <title><?php
if (!empty($title_for_layout)) {
    echo $title_for_layout . ' | ';
}
?>就愛玩</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php
        echo $this->Html->meta('icon', $this->Html->webroot('/favicon.ico'));
        echo $this->Html->meta('keywords', 'Travel, Travel itinerary, Hotel, Point, POI, Backpacker, Vocation');
        echo $this->Html->meta('description', 'This website is built for users in Taiwan. We focus on topics around traveling, like sharing travel travel itineraries, points and hotels. And even the comments, ranks after the travel. We try to offer the necessary information for travel planning and the tools to deal with them.');
        $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min',
            'jquery-ui', 'jquery.rating', 'css_frame', 'css_style', 'css_icon',
            'jquery-jvectormap'
                ), NULL, array('inline' => false, 'block' => 'layout_css'));
        echo $this->EasyCompressor->getLayoutCSS();
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
        echo $this->EasyCompressor->getViewScript();
        echo $this->Html->meta(array('property' => 'og:site_name', 'content' => '就愛玩'));
        echo $this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url('/css/images/head_l.png', true)));
        ?>
    </head>
    <body>
        <div class='container'>
            <!-- Header Start -->
            <div id="Header">
                <div class="fields_3">
                    <div class="block">
                        <h1 class="float-l"><?php
        echo $this->Html->link('就愛玩', '/', array('title' => '就愛玩 Index'));
        ?></h1>
                        <ul id="Btns" class="float-r">
                            <?php
                            $buttons = array(
                                array(
                                    'Create an itinerary', 'schedules/add', 'dbtn_route'
                                ), array(
                                    'Find an itinerary', 'schedules', 'dbtn_area'
                                ), array(
                                    'Find a point', 'points', 'dbtn_point'
                                )
                            );
                            $buttonActived = false;
                            foreach ($buttons AS $button) {
                                $options = array('title' => $button[0]);
                                if (!$buttonActived && ($currentUrl == $button[1] || substr($currentUrl, 0, strlen($button[1])) === $button[1])) {
                                    $options['class'] = 'active';
                                    $buttonActived = true;
                                }
                                echo '<li class="' . $button[2] . '">';
                                echo $this->Html->link($button[0], '/' . $button[1], $options);
                                echo '</li>';
                            }
                            ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="fields_3">
                    <div class="block">
                        <div id="Search" class="clearfix">
                            <?php
                            echo $this->Form->create('Finding', array('url' => '/findings'));
                            echo $this->Form->text('keyword', array(
                                'id' => 'Search_bar',
                                'class' => 'fillet_all shadow-box1 txt_L float-l',
                                'placeholder' => 'Where you want to go?',
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
                    </div>
                </div>
                <div class="fields_3">
                    <div class="block">
                        <div class="img-s float-l"><?php
                            $icon = '';
                            if (!empty($loginMember['id'])) {
                                if (!empty($loginMember['basename'])) {
                                    $icon = $this->Media->file(
                                            "s/{$loginMember['dirname']}/{$loginMember['basename']}");
                                }
                                $memberUrl = '/members/view/' . $loginMember['id'];
                                $memberTitle = 'My page';
                            } else {
                                $memberUrl = '/members/login';
                                $memberTitle = 'Login';
                            }
                            if (empty($icon)) {
                                $icon = $this->Html->image('head_s.png');
                            } else {
                                $icon = $this->Media->embed($icon);
                            }
                            echo $this->Html->link($icon, $memberUrl, array(
                                'title' => $memberTitle,
                                'escape' => false,
                            ));
                            $genderClass = 'spot_XY';
                            if (isset($loginMember['gender']) && $loginMember['gender'] === 'f') {
                                $genderClass = 'spot_XX';
                            }
                            ?></div>
                        <div class="spot overspots <?php echo $genderClass; ?>"><?php
                            if (!empty($loginMember['id'])) {
                                echo $this->Html->link($loginMember['username'], $memberUrl);
                            } else {
                                echo $this->Html->link('Guest', '/members/login');
                            }
                            ?></div>
                        <?php
                        if (!empty($loginMember['id'])) {
                            echo $this->Html->link('<strong>Log out</strong>', '/members/logout', array(
                                'escape' => false,
                            )) . ' | ';
                        }
                        echo $this->Html->link('<strong>' . $memberTitle . '</strong>', $memberUrl, array(
                            'escape' => false,
                            'class' => 'color2b',
                        ));
                        ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php
                echo $this->Html->link('<i class="icon-flag"></i> 中文', '/members/l/zh-tw', array('class' => 'btn span1 pull-right', 'escape' => false));
                ?>
                <div class="clearfix"></div>
                <hr class="line" />
                <div id="Cart">
                    <div id="CartLine1"><span class="dbtn dbtn1 fillet_all">My itineraries</span></div>
                    <div id="CartBox_contentbox"></div>
                </div>
            </div>
            <!-- Header End -->
            <div class="clearfix"></div>
            <div class="btn-group">
                <?php
                switch ($loginMember['group_id']) {
                    case 1:
                        echo $this->Html->link('行程', '/admin/schedules', array('class' => 'btn'));
                        echo $this->Html->link('地點', '/admin/points', array('class' => 'btn'));
                        echo $this->Html->link('旅行社', '/admin/tours', array('class' => 'btn'));
                        echo $this->Html->link('新增地點', '/points/add', array('class' => 'btn'));
                        echo $this->Html->link('評論', '/admin/comments', array('class' => 'btn'));
                        echo $this->Html->link('區域', '/admin/areas', array('class' => 'btn'));
                        echo $this->Html->link('連結', '/admin/links', array('class' => 'btn'));
                        echo $this->Html->link('資料審核', '/admin/submits', array('class' => 'btn'));
                        echo $this->Html->link('使用者', '/admin/members', array('class' => 'btn'));
                        echo $this->Html->link('群組', '/admin/groups', array('class' => 'btn'));
                        echo $this->Html->link('頻道', '/admin/channels', array('class' => 'btn'));
                        break;
                    case 3:
                        echo $this->Html->link('匯入行程', '/admin/schedules/pull', array('class' => 'btn'));
                        echo $this->Html->link('匯入地點', '/admin/channels/rss', array('class' => 'btn'));
                        break;
                }
                ?>
            </div>
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
                <a href="#" title="Back to top" class="dbtn_top fillet_bottom">▲TOP</a>
                <p>
                    <a href="http://cakephp.org/" target="_blank"><img src="http://travel.olc.tw/img/cake.power.gif" alt="CakePHP: the rapid development php framework" width="98" height="13" border="0"></a>
                    <a href="http://olc.tw/" target="_blank">J.T. Studio</a>
                    <a href="http://olc.tw/contact" target="_blank">Contact</a>
                    <?php echo $this->Html->link('About', '/pages/about_us'); ?>
                    <?php echo $this->Html->link('Service Agreement', '/pages/service_agreement'); ?>
                    <a href="http://osobiz.com/category/%E5%B0%B1%E6%84%9B%E7%8E%A9/" target="_blank">Blog</a>
                    <a href="https://www.facebook.com/travel.olc.tw" target="_blank">Facebook</a>
                    <a href="https://plus.google.com/113112425852599921933" target="_blank">Google+</a>
                </p>
            </div>
            <!-- Footer End -->
            <div class="clearfix"></div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
        <script type="text/javascript">
            //<![CDATA[
            $(function() {
                $('div#CartBox_contentbox').load('<?php echo $this->Html->url('/schedules/cart_list'); ?>', [], function() {
                    $('div#Cart').hover(function() {
                        $('#CartList1').show();
                    }, function() {
                        $('#CartList1').hide();
                    });
                });
                $('form#FindingIndexForm').submit(function() {
                    if($('input#Search_bar').val() === '') {
                        return false;
                    }
                });
            });
            //]]>
        </script>
    </body>
</html>