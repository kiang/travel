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
        echo $this->Html->meta('keywords', '自助旅行,旅遊,行程,旅館,地點,背包,放假,休閒, backpacker, Travel, Schedules, Hotels, Points, Chinese Travel');
        echo $this->Html->meta('description', '就愛玩是個以旅遊為題的網站，在網站上您可以分享自己的旅遊行程、地點與旅館，以及這些旅遊資訊衍生的心得、評價等，我們試著提供在規劃旅遊時所需要取得的資訊與整理這些資訊的工具。

This website is built for Chinese users. We focus on topics around traveling, like sharing travel schedules, points and hotels. And even the comments, ranks after the travel. We try to offer the necessary information for travel planning and the tools to deal with them.');
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
        echo $this->Html->script('ui.datepicker-zh-TW');
        echo $this->EasyCompressor->getViewScript();
        echo $this->Html->meta(array('property' => 'og:site_name', 'content' => '就愛玩'));
        echo $this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url('/css/images/head_l.png', true)));
        ?>
    </head>
    <body>
        <div class='container'>
            <!-- Header Start -->
            <div id="Header">
                <div class="span8">
                    <h2><?php echo $tour['Tour']['title']; ?></h2>
                    Tel: <?php echo $tour['Tour']['telephone']; ?>
                    <?php
                    if(!empty($tour['Tour']['fax'])) {
                        echo ' / Fax: ' . $tour['Tour']['fax'];
                    }
                    ?>
                    <br /><?php echo $tour['Tour']['address']; ?>
                </div>
                <div class="span2 pull-right">
                    <h1><?php
        echo $this->Html->link('就愛玩', '/', array('title' => '就愛玩 首頁'));
        ?></h1>
                </div>
                <div class="clearfix"></div>
                <hr class="line" />
                <div id="Cart">
                    <div id="CartLine1"><span class="dbtn dbtn1 fillet_all">我的行程表</span></div>
                    <div id="CartBox_contentbox"></div>
                </div>
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
            });
            //]]>
        </script>
    </body>
</html>