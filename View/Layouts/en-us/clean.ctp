<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
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
        echo $this->Html->css('bootstrap.min', NULL);
        echo $this->Html->css('bootstrap-responsive.min', NULL);
        echo $this->Html->css('jquery-ui', NULL);
        echo $this->Html->css('jquery.rating', NULL);
        echo $this->Html->css('css_frame', NULL);
        echo $this->Html->css('css_style', NULL);
        echo $this->Html->css('css_icon', NULL);
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
        ?>
    </head>
    <body>
        <div id="Page"> 
            <!-- Main Start -->
            <div id="Main">
                <?php
                echo $this->Session->flash();
                echo $content_for_layout;
                ?>
            </div>
    </body>
</html>