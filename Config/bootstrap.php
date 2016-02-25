<?php

/*
 * 參考下面文章加入，不過在使用中文資料表時會有問題
 * http://micropipes.com/blog/2009/02/23/how-addonsmozillaorg-defends-against-xss-attacks/
 */
/*
  if (array_key_exists('url',$_GET) &&
  !preg_match('/\/api\//', $_GET['url']) &&
  preg_match('/[^\w\d\/\.\-_!: ]/u',$_GET['url'])) {
  header("HTTP/1.1 400 Bad Request");
  exit;
  }
 */
require APP . 'Plugin' . DS . 'Media' . DS . 'config' . DS . 'core.php';
CakePlugin::loadAll();
CakePlugin::load('Opauth', array('routes' => true, 'bootstrap' => true));
$debug = Configure::read('debug');
if ($debug > 0) {
    Configure::write('Opauth.path', '/~kiang/travel/auth/');
    Configure::write('Opauth.Strategy.Facebook', array(
        'app_id' => '346293725421',
        'app_secret' => '51e8831aa8507d770cfc5a1f5ed677f4'
    ));
} else {
    Configure::write('Opauth.Strategy.Facebook', array(
        'app_id' => '184657388705',
        'app_secret' => '78fbdda94cb951f1b06314c990f959e8'
    ));
}
Configure::write('Opauth.Strategy.Flickr', array(
    'key' => '91cac390d484c63916969c39d8a43631',
    'secret' => 'b5685aca9b450774'
));
Configure::write('Opauth.Strategy.GitHub', array(
    'client_id' => '8fee3a9f962b75eda23f',
    'client_secret' => 'fcc5d4aa4308f7eda90684385d635d97afed29e8'
));
Configure::write('Opauth.Strategy.Google', array(
    'client_id' => '869162167865.apps.googleusercontent.com',
    'client_secret' => 'z_z21ZBRV8RR4zPQWzAkzvVN'
));
Configure::write('Opauth.Strategy.LinkedIn', array(
    'api_key' => 'qxn1wyp8qsvk',
    'secret_key' => 'krvAxj8aID5h9RmJ'
));
/*
Configure::write('Opauth.Strategy.Live', array(
    'client_id' => '00000000400EAA5D',
    'client_secret' => 'aNeBjySbMdOBIYfYcjw2aP8h3KRVdHKl'
));
Configure::write('Opauth.Strategy.Twitter', array(
    'key' => 'iHUVs8lYA3rIxoSTfInXzw',
    'secret' => 'CmDi3kMM2e7XuRLRqTUC4k2F9gaGXe4qTbFwdoz8'
));
Configure::write('Opauth.Strategy.OpenID', array());
 * 
 */