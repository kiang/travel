<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    var $helpers = array('Html', 'Form', 'JqueryEngine', 'Travel', 'Session', 'Media.Media', 'EasyCompressor.EasyCompressor');
    var $components = array('Acl',
        'Auth' => array(
            'loginAction' => '/members/login',
            'loginRedirect' => '/',
            'authorize' => array(
                'Actions' => array(
                    'userModel' => 'Member',
                )
            ),
            'authenticate' => array(
                'Api.Token' => array(
                    'userModel' => 'Member',
                    'scope' => array('Member.user_status' => 'Y'),
                ),
                'Form' => array(
                    'userModel' => 'Member',
                    'scope' => array('Member.user_status' => 'Y'),
                ),
            ),
        ),
        'Security' => array(
            'csrfExpires' => '+2 hour',
            'blackHoleCallback' => 'blackhole',
        ),
        'RequestHandler', 'Session', 'Paginator');
    var $loginMember;
    var $foreignControllers = array(
        'Point' => 'points',
        'Schedule' => 'schedules',
        'Member' => 'members',
        'Tour' => 'tours',
        'Program' => 'programs',
    );
    var $locale = 'en-us';

    function beforeFilter() {
        $this->loginMember = $this->getLoginMember();
        if (isset($this->request->params['named']['limit'])) {
            $this->Session->write('block', 1);
            unset($this->request->params['named']['limit']);
            exit();
        }
        if ($this->Session->read('block') == 1) {
            echo 'something is going wrong...';
            //$this->Session->delete('block');
            exit();
        }
        $this->locale = $this->Session->read('Config.language');
        if (!in_array($this->locale, array('zh-tw', 'en-us'))) {
            $this->locale = '';
        }
        if (empty($this->locale)) {
            //check the browser
            if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) || false !== strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh')) {
                $this->locale = 'zh-tw';
            } else {
                $this->locale = 'en-us';
            }
            $this->Session->write('Config.language', $this->locale);
        }
        $this->viewPath = $this->locale . DS . $this->viewPath;
        $this->layoutPath = $this->layoutPath . $this->locale;
        $this->set('locale', $this->locale);

        if (!$this->request->is('ajax')) {
            $baseUrl = Router::url('/', true);
            $referer = $this->referer();
            if (false === strpos($referer, $baseUrl) && $referer !== '/') {
                $fh = fopen(TMP . 'referers', 'a+');
                fwrite($fh, implode("\n", array(
                            date('Y-m-d H:i:s'),
                            $referer
                        )) . "\n");
                fclose($fh);
            }
        }
    }

    function getLoginMember() {
        $this->loginMember = $this->Session->read('Auth.User');
        if (empty($this->loginMember)) {
            $this->loginMember = array(
                'id' => 0,
                'group_id' => 0,
                'username' => '',
            );
        }
        Configure::write('loginMember', $this->loginMember);
        return $this->loginMember;
    }

    function beforeRender() {
        $this->set('loginMember', $this->getLoginMember());
        $this->set('currentUrl', isset($this->request['url']['url']) ? $this->request['url']['url'] : '');
    }

    function redirect($url, $status = null, $exit = true) {
        if (!$this->request->is('ajax')) {
            return parent::redirect($url, $status, $exit);
        } else {
            exit();
        }
    }

    function paginate($object = null, $scope = array(), $whitelist = array()) {
        if (is_string($object)) {
            $assoc = null;

            if (strpos($object, '.') !== false) {
                list($object, $assoc) = explode('.', $object);
            }

            if ($assoc && isset($this->{$object}->{$assoc})) {
                $object = $this->{$object}->{$assoc};
            } elseif ($assoc && isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$assoc})) {
                $object = $this->{$this->modelClass}->{$assoc};
            } elseif (isset($this->{$object})) {
                $object = $this->{$object};
            } elseif (isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$object})) {
                $object = $this->{$this->modelClass}->{$object};
            }
        } elseif (empty($object) || $object == null) {
            if (isset($this->{$this->modelClass})) {
                $object = $this->{$this->modelClass};
            } else {
                $className = null;
                $name = $this->uses[0];
                if (strpos($this->uses[0], '.') !== false) {
                    list($name, $className) = explode('.', $this->uses[0]);
                }
                if ($className) {
                    $object = $this->{$className};
                } else {
                    $object = $this->{$name};
                }
            }
        }
        if (!is_object($object)) {
            trigger_error(sprintf(__('Controller::paginate() - can\'t find model %1$s in controller %2$sController'), $object, $this->name), E_USER_WARNING);
            return array();
        }
        $alias = 'paginate.' . $this->request->params['controller'] . '.' . $this->request->params['action'] . '.' . $object->alias;
        $sessionVars = $this->Session->read($alias);
        if (!empty($this->passedArgs)) {
            if (!empty($this->passedArgs['page'])) {
                $this->Session->write($alias . '.page', $this->passedArgs['page']);
            }
            if (!empty($sessionVars)) {
                $this->passedArgs = array_unique(array_merge($sessionVars, $this->passedArgs));
            }
        } else if (!empty($sessionVars)) {
            $this->passedArgs = $sessionVars;
        }
        return parent::paginate($object, $scope, $whitelist);
    }

    protected function getForeignTitle($model, $foreign_key) {
        if (!isset($this->$model)) {
            $this->loadModel($model);
        }
        switch ($model) {
            case 'Schedule':
                return $this->Schedule->field('title', array(
                            'Schedule.id' => $foreign_key
                ));
                break;
            case 'Point':
                $point = $this->Point->read(array('title_zh_tw', 'title_en_us', 'title'), $foreign_key);
                if (!empty($point['Point']['title_zh_tw'])) {
                    return $point['Point']['title_zh_tw'];
                } elseif (!empty($point['Point']['title_en_us'])) {
                    return $point['Point']['title_en_us'];
                }
                return $point['Point']['title'];
                break;
            case 'Member':
                return $this->Member->field('username', array(
                            'Member.id' => $foreign_key
                ));
                break;
            case 'Program':
                return $this->Program->field('name', array(
                            'Program.id' => $foreign_key
                ));
                break;
        }
    }
    
    /*
     * from http://stackoverflow.com/questions/15333470/cakephp-get-details-about-security-component-error
     */
    public function blackhole($errorType) {

        $errorMap['auth'] = 'form validation error, or a controller/action mismatch error.';
        $errorMap['csrf'] = 'CSRF error.';
        $errorMap['get'] = 'HTTP method restriction failure.';
        $errorMap['post'] = $errorMap['get'];
        $errorMap['put'] = $errorMap['get'];
        $errorMap['delete'] = $errorMap['get'];
        $errorMap['secure'] = 'SSL method restriction failure.';
        $errorMap['myMoreValuableErrorType'] = 'My custom and very ' .
                'specific reason for the error type.';

        CakeLog::notice("Request to the '{$this->request->params['action']}' " .
                "endpoint was blackholed by SecurityComponent due to a {$errorMap[$errorType]}");
    }

}
