<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>預覽列印行程表 - 就愛玩</title>
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
        $this->Html->script(array('php', 'jquery', 'jquery-ui',
            'bootstrap.min', 'jquery-ui-timepicker', 'jquery.tmpl.min',
            'jquery.rating.pack', 'latlng', 'map/jquery.ui.map.full.min',
            'googlemap3', 'travel'
                ), array('inline' => false, 'block' => 'layout_script'));
        echo $this->EasyCompressor->getLayoutScript();
        echo $this->EasyCompressor->getViewScript();
        ?>
    </head>

    <body style="color: #000;">
        <div class='container'>
            <!-- Main Start -->
            <div id="Main">
                <p>本資料由<a href="http://travel.olc.tw">就愛玩</a>（ <a href="http://travel.olc.tw">http://travel.olc.tw</a> ）所提供。</p>
                <?php echo $content_for_layout; ?>
            </div>
            <!-- Main End -->
            <div class="clearfix"></div>
        </div>
    </body>
</html>