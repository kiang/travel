<?php

if (FALSE === Configure::read('App.offline')) {
    /*
      echo $this->Html->script('http://www.google.com/jsapi?key=ABQIAAAApmhX0DdZESsry567YeF_vBQsyKWKatEedhTiXOAzs3CgIFNTaRRslrhN7aHTAhCwdYJX3j9Tu9mXIA', false);
      echo $this->Html->scriptBlock('google.load(\'maps\', \'3\', {\'locale\' : \'zh_TW\'});', array('inline' => false));
     */
    echo $this->Html->script('http://maps.google.com/maps/api/js?sensor=false', false);
    echo $this->Html->script('googlemap3', false);
}