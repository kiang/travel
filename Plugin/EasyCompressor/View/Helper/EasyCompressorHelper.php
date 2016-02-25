<?php

/**
 * Easy Compressor Plugin - compress Js and CSS in a easy way
 * @author Glauco Custódio (@glauco_dsc) <glauco.custodio@gmail.com>     
 * @link https://github.com/glaucocustodio/easy-compressor-plugin
 * http://blog.glaucocustodio.com - http://glaucocustodio.com
 */
App::uses('HtmlHelper', 'View/Helper');

class EasyCompressorHelper extends HtmlHelper {

    /**
     * Method responsible to generate hash from sum of modification time of all files and to prepare
     * files string that will be compressed
     */
    public function getTagVariables($assets = array(), $type = NULL) {
        if ($type == 'css') {
            $basePath = CSS;
            $delimiter = '/css/';
        } else {
            $basePath = JS;
            $delimiter = '/js/';
        }

        $modificationTime = 0;
        foreach ($assets as $c) {
            $modificationTime += filemtime($basePath . end(explode($delimiter, $c)));
        }

        return array(md5($modificationTime), $assets);
    }

    /**
     * Method responsible to return a hash from current page (controller.action)
     */
    public function getPageHash() {
        return md5($this->params['controller'] . $this->params['action']);
    }

    /**
     * Method responsible to return view css, getting them by default css block
     */
    public function getViewCSS() {
        preg_match_all('#href=\"([^"]*)\"#', $this->_View->Blocks->get('css'), $viewCSS);

        if (isset($viewCSS[1]) && !empty($viewCSS[1])) {
            if (Configure::read('debug') == 0 || Configure::read('EasyCompressor.enabled')) {
                list($viewModificationTime, $viewFiles) = $this->getTagVariables($viewCSS[1], 'css');
                return sprintf('<link rel="stylesheet" type="text/css" href="%s&p=' . $this->getPageHash() . '&mt=' . $viewModificationTime . '"/>' . PHP_EOL, Router::url('/easy_compressor/easy_compressor/css.css?f=' . $viewFiles));
            } else {
                return $this->getUncompressedCSS($viewCSS[0]);
            }
        }
        return;
    }

    /**
     * Method responsible to return uncompressed CSS
     */
    public function getUncompressedCSS($assets = array()) {
        $css = NULL;
        foreach ($assets as $c) {
            $css .= sprintf('<link rel="stylesheet" type="text/css" %s />' . PHP_EOL, $c);
        }
        return $css;
    }

    /**
     * Method responsible to return CSS from 'layout_css' block
     */
    public function getLayoutCSS() {
        $block = $this->_View->Blocks->get('layout_css');
        if(Configure::read('debug') > 0) {
            return $block;
        }
        $targetFile = 'cc_' . md5($block . date('Ym')) . '.css';
        if (!file_exists(CSS . $targetFile)) {
            $existedFiles = glob(CSS . 'cc_*');
            if (is_array($existedFiles)) {
                foreach (glob(CSS . 'cc_*') AS $file) {
                    unlink($file);
                }
            }
            preg_match_all('#href=\"([^"]*)\"#', $block, $layoutCSS);
            if (isset($layoutCSS[1]) && !empty($layoutCSS[1])) {
                $fh = fopen(CSS . $targetFile, 'w');
                foreach ($layoutCSS[1] AS $c) {
                    $cssFile = CSS . end(explode('/css/', $c));
                    fputs($fh, file_get_contents($cssFile) . PHP_EOL);
                }
                fclose($fh);
            }
        }
        if (file_exists(CSS . $targetFile)) {
            return sprintf('<link rel="stylesheet" type="text/css" href="%s" />' . PHP_EOL, Router::url('/css/' . $targetFile));
        } else {
            return $this->_View->Blocks->get('layout_css');
        }
        return;
    }

    /**
     * Method responsible to return uncompressed scripts
     */
    public function getUncompressedScripts($assets = array()) {
        $scripts = NULL;
        foreach ($assets as $c) {
            $scripts .= sprintf('<script type="text/javascript" %s ></script>' . PHP_EOL, $c);
        }
        return $scripts;
    }

    /**
     * Method responsible to return view scripts, getting them by default scripts block
     */
    public function getViewScript() {
        $block = $this->_View->Blocks->get('script');
        if(Configure::read('debug') > 0) {
            return $block;
        }
        $targetFile = 'c/' . md5($block . date('Ym')) . '.js';
        if (!file_exists(JS . $targetFile)) {
            preg_match_all('#src=\"([^"]*)\"#', $block, $viewScripts);
            if (isset($viewScripts[1]) && !empty($viewScripts[1])) {
                App::import('Vendor', 'jsmin', array('file' => 'jsmin/jsmin.php'));
                $fh = fopen(JS . $targetFile, 'w');
                foreach ($viewScripts[1] AS $c) {
                    $jsFile = JS . end(explode('/js/', $c));
                    fputs($fh, JsMin::minify(file_get_contents($jsFile)));
                }
                fclose($fh);
            }
        }
        if (file_exists(JS . $targetFile)) {
            return sprintf('<script type="text/javascript" src="%s"></script>' . PHP_EOL, Router::url('/js/' . $targetFile));
        } else {
            return $block;
        }
        return;
    }

    /**
     * Method responsible to return scripts from 'layout_script' block
     */
    public function getLayoutScript() {
        $block = $this->_View->Blocks->get('layout_script');
        if(Configure::read('debug') > 0) {
            return $block;
        }
        $targetFile = 'cc_' . md5($block . date('Ym')) . '.js';
        if (!file_exists(JS . $targetFile)) {
            $existedFiles = glob(JS . 'cc_*');
            if (is_array($existedFiles)) {
                foreach (glob(JS . 'cc_*') AS $file) {
                    unlink($file);
                }
            }
            preg_match_all('#src=\"([^"]*)\"#', $block, $layoutScripts);
            if (isset($layoutScripts[1]) && !empty($layoutScripts[1])) {
                App::import('Vendor', 'jsmin', array('file' => 'jsmin/jsmin.php'));
                $fh = fopen(JS . $targetFile, 'w');
                foreach ($layoutScripts[1] AS $c) {
                    $jsFile = JS . end(explode('/js/', $c));
                    fputs($fh, JsMin::minify(file_get_contents($jsFile)));
                }
                fclose($fh);
            }
        }
        if (file_exists(JS . $targetFile)) {
            return sprintf('<script type="text/javascript" src="%s"></script>' . PHP_EOL, Router::url('/js/' . $targetFile));
        } else {
            return $block;
        }
        return;
    }

}

?>
