<!DOCTYPE html>
<html>
    <head>
        <title><?php
if (!empty($title_for_layout)) {
    echo $title_for_layout . ' | ';
}
?>就愛玩</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
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
        echo $this->Html->css('jquery.tutorial');
        ?>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
            }
            #map_canvas {
                height: 100%;
            }
            .markerLabels {
                color: red;
                background-color: white;
                font-size: 10px;
                width: 80px;
                text-align: center;
                border: 1px solid black;
                white-space: nowrap;
            }
            .scheduleLineBarLong {
                width: 250px;
                text-align: left;
            }
            .scheduleLineBarShort {
                width: 210px;
                text-align: left;
            }
        </style>
        <script type="text/javascript">
            var wwwRoot = '<?php echo $this->Html->url('/', true); ?>';
        </script>
        <?php
        if (Configure::read('App.offline')) {
            echo $this->Html->script('googlemap3_offline');
        } else {
            echo $this->Html->script('http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false');
        }
        $this->Html->script(array('php', 'jquery', 'jquery-ui',
            'bootstrap.min', 'jquery-ui-timepicker', 'jquery.tmpl.min',
            'jquery.rating.pack', 'latlng', 'map/jquery.ui.map.full.min',
            'googlemap3', 'travel'
                ), array('inline' => false, 'block' => 'layout_script'));
        echo $this->EasyCompressor->getLayoutScript();
        echo $this->Html->script('ui.datepicker-zh-TW');
        echo $this->EasyCompressor->getViewScript();
        echo $this->Html->script('jquery.tutorial');
        echo $this->Html->meta(array('property' => 'og:site_name', 'content' => '就愛玩'));
        echo $this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url('/css/images/head_l.png', true)));
        ?>
    </head>
    <body>
        <?php
        echo $content_for_layout;
        ?>
    </body>
</html>