<?php

App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

/**
 * @property Member Member
 *
 */
class MembersController extends AppController {

    var $name = 'Members';
    var $helpers = array('Media.Media');
    var $components = array('Api.Api');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('login', 'logout', 'signup', 'active',
            'passwordForgotten', 'view', 'area', 'token', 'l', 'oauth', 'go');
    }

    function login() {
        $this->getLoginMember();
        if (!empty($this->loginMember['id'])) {
            if (!empty($this->params['requested'])) {
                return true;
            } else {
                return $this->redirect('/');
            }
        } elseif (!empty($this->request['data']['Member']['username'])) {
            $loginPath = TMP . 'login' . DS . $this->request['data']['Member']['username'];
            $baseTime = date('YmdHi');
            $baseTime -= $baseTime % 5;
            $loginTimerFile = $loginPath . DS . $baseTime;
            $loginTimer = 0;
            if(!file_exists($loginPath)) {
                mkdir($loginPath, 0777, true);
            }
            if(file_exists($loginTimerFile)) {
                $loginTimer = filesize($loginTimerFile);
            } else {
                $loginTimer = 1;
            }
            if($loginTimer > 3) {
                $this->Session->setFlash('帳號或密碼錯誤超過三次，請稍候再試！');
            } elseif ($this->Auth->login()) {
                $this->loginMember = $this->getLoginMember();
                $schedules = $this->Session->read('Guest.Schedules');
                if (!empty($schedules)) {
                    $this->loadModel('Schedule');
                    if (!empty($this->loginMember['nickname'])) {
                        $memberName = $this->loginMember['nickname'];
                    } else {
                        $memberName = $this->loginMember['username'];
                    }
                    $memberName = addslashes($memberName);
                    $this->Schedule->updateAll(array(
                        'Schedule.member_id' => $this->loginMember['id'],
                        'Schedule.member_name' => '\'' . $memberName . '\'',
                            ), array(
                        'Schedule.id' => array_keys($schedules),
                    ));
                    $this->Session->delete('Guest.Schedules');
                }
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('帳號或密碼有錯誤！');
            }
            file_put_contents($loginTimerFile, str_repeat('0', ++$loginTimer));
        }
        $referer = $this->referer();
        if (!in_array($referer, array(
                    Router::url('/', true),
                    Router::url('/members/login', true)
                ))) {
            $this->Session->write('Auth.redirect', $referer);
        }
        $this->set('title_for_layout', '登入');
    }

    function logout() {
        $this->Auth->logout();
        $this->redirect('/');
    }

    function signup() {
        if (!empty($this->loginMember['id'])) {
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            $this->request->data = Sanitize::clean($this->request->data);
            if (isset($this->request->data['Area'][1][0])) {
                $this->request->data['Member']['area_id'] = $this->request->data['Area'][1][0];
                unset($this->request->data['Area']);
            }
            if ($this->request->data['Member']['agree'] != 'agree') {
                $this->set('agreementFail', true);
                $this->Member->invalidate('id');
            }
            $this->request->data['Member']['group_id'] = 2;
            $this->request->data['Member']['user_status'] = 'N';
            $this->Member->create();
            $this->Member->skipPasswordCheck = true;
            if ($this->Member->save($this->request->data)) {
                $memberId = $this->Member->getInsertID();
                if ($memberId > 0) {
                    $this->Session->setFlash('註冊快要完成！系統會在短時間內寄送帳號啟用的程序到您註冊的信箱，請依照信中內容指示來啟用您的帳號。');
                    $emailObj = new CakeEmail('gmail');
                    $emailObj->emailFormat('both');
                    $emailObj->viewVars(array(
                        'uid' => $memberId,
                        'code' => Security::hash(Configure::read('Security.salt') . $this->request->data['Member']['email']),
                    ));
                    $emailObj->template($this->locale . DS . 'active');
                    $emailObj->from(array('service@olc.tw' => '就愛玩'));
                    $emailObj->to($this->request->data['Member']['email']);
                    $emailObj->subject('就愛玩網站註冊通知信');
                    $emailObj->send();
                } else {
                    $this->Session->setFlash('抱歉，註冊程序發生技術錯誤，請與網站管理員聯繫');
                }
                $this->redirect('/');
            }
        }
        $this->set('title_for_layout', '註冊');
    }

    function active($uid = 0, $verify = '') {
        $uid = intval($uid);
        if ($uid <= 0 || empty($verify)) {
            $this->Session->setFlash('請依照網頁指示操作！');
        } elseif (!$email = $this->Member->field('email', array(
            'id' => $uid,
            'group_id' => 2,
            'user_status' => 'N',
                ))) {
            $this->Session->setFlash('您的帳號也許已經啟用，或是尚未註冊！');
        } else {
            $code = Security::hash(Configure::read('Security.salt') . $email);
            if ($code === $verify) {
                if ($this->Member->save(array('Member' => array(
                                'id' => $uid,
                                'group_id' => 2,
                                'user_status' => 'Y',
                                )))) {
                    $this->Session->setFlash('帳號已經啟用，請透過您註冊的帳號、密碼登入！');
                    $this->redirect('/members/login');
                } else {
                    $this->Session->setFlash('帳號啟用過程發生錯誤，請連絡管理員！');
                }
            } else {
                $this->Session->setFlash('驗證碼有錯誤！');
            }
        }
        $this->redirect('/');
    }

    function passwordForgotten() {
        if (!empty($this->request->data['Member']['email'])) {
            $email = trim(Sanitize::clean($this->request->data['Member']['email']));
            if (!empty($email) && $member = $this->Member->find('first', array(
                'fields' => array('id', 'username', 'group_id'),
                'conditions' => array(
                    'email' => $email,
                    'user_status' => 'Y',
                ),
                    ))) {
                /*
                 * 產生一個 8 字元的密碼
                 * 33 ~ 45 符號
                 * 48 ~ 57 數字
                 * 65 ~ 90 大寫英文
                 * 97 ~ 122 小寫英文
                 */
                $ranges = array(
                    array(97, 122),
                    array(33, 45),
                    array(48, 57),
                    array(65, 90),
                );
                $password = '';
                foreach ($ranges AS $range) {
                    $password .= chr(rand($range[0], $range[1])) . chr(rand($range[0], $range[1]));
                }
                $emailObj = new CakeEmail('gmail');
                $emailObj->viewVars(array(
                    'username' => $member['Member']['username'],
                    'password' => $password,
                ));
                $emailObj->template($this->locale . DS . 'password');
                $emailObj->from(array('service@olc.tw' => '就愛玩'));
                $emailObj->to($email);
                $emailObj->emailFormat('both');
                $emailObj->subject('就愛玩網站密碼通知信');
                $emailObj->send();
                $this->Member->skipPasswordCheck = true;
                $this->Member->save(array('Member' => array(
                        'id' => $member['Member']['id'],
                        'group_id' => $member['Member']['group_id'],
                        'password' => $password,
                        )));
            }
            $this->Session->setFlash('如果您輸入的信箱正確，新的密碼應該已經寄出！');
            $this->redirect('/');
        }
    }

    function view($id = 0) {
        $id = intval($id);
        if (empty($id)) {
            if (empty($this->loginMember['id'])) {
                $this->redirect('/members/login');
            }
            $id = $this->loginMember['id'];
        }
        if($id > 0) {
            $this->request->data = $this->Member->find('first', array(
                'conditions' => array('Member.id' => $id),
                'contain' => array('Oauth'),
            ));
        }
        if (empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } else {
            if ($this->loginMember['id'] != $id) {
                $this->Member->updateAll(
                        array('Member.count_views' => 'Member.count_views + 1'), array('Member.id' => $id)
                );
            }
            $this->set('title_for_layout', $this->request->data['Member']['nickname']);
            if ($this->request->data['Member']['area_id']) {
                $this->set('areas', $this->Member->Area->getPath($this->request->data['Member']['area_id'], array('id', 'name')));
            }
            $oauths = array();
            foreach($this->request->data['Oauth'] AS $oauth) {
                $oauths[$oauth['provider']] = true;
            }
            $this->set('oauths', $oauths);
        }
    }

    /**
     * Rebuild the Acl based on the current controllers in the application
     *
     * @link          http://book.cakephp.org/view/647/An-Automated-tool-for-creating-ACOs
     * @return void
     */
    function buildAcl() {
        $log = array();

        $aco = $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => 0, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }
        App::uses('Configure', 'Core');
        App::uses('File', 'Utility');
        $Controllers = App::objects('Controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $ctrlNameNew = str_replace('Controller', '', $ctrlName);
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            // Do all Plugins First
            if ($this->_isPlugin($ctrlName)) {
                $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                if (!$pluginNode) {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
                    $pluginNode = $aco->save();
                    $pluginNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
                }
            }
            // find / make controller node
            $controllerNode = $aco->node('controllers/' . $ctrlNameNew);
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)) {
                    $pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
                    $aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlNameNew)));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                } else {
                    $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlNameNew));
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                }
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/' . $ctrlNameNew . '/' . $method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                }
            }
        }
    }

    function _getClassMethods($ctrlName = null) {
        if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
            $num = strpos($ctrlName, '.');
            $ctrlName = substr($ctrlName, $num + 1);
        }
        App::uses($ctrlName, 'Controller');
        $methods = get_class_methods($ctrlName);

        // Add scaffold defaults if scaffolds are being used
        $properties = get_class_vars($ctrlName);
        if (array_key_exists('scaffold', $properties)) {
            if ($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
            } else {
                $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
            }
        }
        return $methods;
    }

    function _isPlugin($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) > 1) {
            return true;
        } else {
            return false;
        }
    }

    function _getPluginControllerPath($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0] . '.' . $arr[1];
        } else {
            return $arr[0];
        }
    }

    function _getPluginName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0];
        } else {
            return false;
        }
    }

    function _getPluginControllerName($ctrlName = null) {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[1];
        } else {
            return false;
        }
    }

    /**
     * Get the names of the plugin controllers ...
     * 
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the 
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names.
     *
     */
    function _getPluginControllerNames() {
        App::uses('Folder', 'Utility');
        $folder = new Folder();
        $folder->cd(APP . 'plugins');

        // Get the list of plugins
        $Plugins = $folder->read();
        $Plugins = $Plugins[0];
        $arr = array();

        // Loop through the plugins
        foreach ($Plugins as $pluginName) {
            // Change directory to the plugin
            $didCD = $folder->cd(APP . 'plugins' . DS . $pluginName . DS . 'controllers');
            // Get a list of the files that have a file name that ends
            // with controller.php
            $files = $folder->findRecursive('.*_controller\.php');

            // Loop through the controllers we found in the plugins directory
            foreach ($files as $fileName) {
                // Get the base file name
                $file = basename($fileName);

                // Get the controller name
                $file = Inflector::camelize(substr($file, 0, strlen($file) - strlen('_controller.php')));
                if (!preg_match('/^' . Inflector::humanize($pluginName) . 'App/', $file)) {
                    if (!App::import('Controller', $pluginName . '.' . $file)) {
                        debug('Error importing ' . $file . ' for plugin ' . $pluginName);
                    } else {
                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = Inflector::humanize($pluginName) . "/" . $file;
                    }
                }
            }
        }
        return $arr;
    }

    function area($areaId = 0, $offset = 0) {
        $areaId = intval($areaId);
        $offset = intval($offset);
        if ($offset < 0 || $offset % 18 != 0) {
            $offset = 0;
        }
        $scope = array('Member.user_status' => 'Y');
        $contain = array();
        if ($areaId > 0 && $area = $this->Member->Area->find('first', array(
            'fields' => array('lft', 'rght'),
            'conditions' => array(
                'Area.id' => $areaId,
            ),
                ))) {
            if ($area['Area']['rght'] - $area['Area']['lft'] == 1) {
                $scope['Member.area_id'] = $areaId;
            } else {
                $contain = array(
                    'Area' => array(
                        'fields' => array('id'),
                        ));
                $scope['Area.lft >='] = $area['Area']['lft'];
                $scope['Area.rght <='] = $area['Area']['rght'];
            }
        }
        $this->set('url', array($areaId));
        //area($areaId = 0, $offset = 0) {
        $key = "/members/area/{$areaId}/{$offset}";
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Member->find('all', array(
                'contain' => $contain,
                'conditions' => $scope,
                'offset' => $offset,
                'limit' => 18,
                'order' => array(
                    'created' => 'desc'
                ),
                'fields' => array(
                    'id', 'nickname', 'dirname', 'basename', 'created',
                    'intro', 'gender',
                )
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    /**
     * 會員編輯自己的帳號
     */
    function edit() {
        if (!empty($this->request->data)) {
            $this->request->data['Member']['id'] = $this->loginMember['id'];
            $this->request->data['Member']['group_id'] = $this->loginMember['group_id'];
            if ($this->Member->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存！');
                $this->redirect(array('action' => 'view'));
            } else {
                $this->Session->setFlash('資料無法儲存，請重試！');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Member->read(null, $this->loginMember['id']);
            $this->set('areaPath', $this->Member->Area->getPath($this->request->data['Member']['area_id'], array('name')));
        }
    }

    function edit_permission() {
        
    }

    public function token() {
        $_POST = Sanitize::clean($_POST);
        if (isset($_POST['u1']) && isset($_POST['u2'])) {
            $member = $this->Member->find('first', array(
                'conditions' => array(
                    'Member.username' => $_POST['u1'],
                    'Member.password' => $this->Auth->password($_POST['u2']),
                    'Member.user_status' => 'Y',
                )
                    ));
            if (!empty($member)) {
                App::uses('CakeText', 'Utility');
                $token = CakeText::uuid();
                if ($this->Member->save(array('Member' => array(
                                'id' => $member['Member']['id'],
                                'group_id' => $member['Member']['group_id'],
                                'access_token' => $token,
                                )))) {
                    echo $token;
                } else {
                    echo '儲存 token 時發生錯誤';
                }
            } else {
                echo '帳號或密碼有誤，請重新輸入';
            }
        }
        exit();
    }

    public function l($lang = '') {
        if (in_array($lang, array('zh-tw', 'en-us'))) {
            $this->Session->write('Config.language', $lang);
        }
        $this->redirect('/');
    }

    public function app_init() {
        if (isset($_GET['token']) && (36 === strlen($_GET['token']))) {
            $member = $this->Member->find('first', array(
                'conditions' => array('Member.access_token' => $_GET['token'])
                    ));
            $sqlContent = file_get_contents(APP . 'Config' . DS . 'sql' . DS . 'app.sql');
            $sqlContent .= "\nINSERT INTO members VALUES ('{$member['Member']['id']}', 0, 0, 'm', 'user@app', '{$member['Member']['username']}', '', 'Y', '{$member['Member']['nickname']}', '', '', '', '{$member['Member']['created']}', '{$member['Member']['modified']}', 0, 0, 0, '', '');\n";
            Configure::write('loginMember', $member['Member']);
            $this->loadModel('Schedule');
            $schedules = $this->Schedule->find('list', array(
                'fields' => array('Schedule.id', 'Schedule.id'),
                'conditions' => array(
                    'member_id' => $member['Member']['id'],
                ),
                'order' => 'Schedule.id DESC',
                'limit' => 10,
                    ));
            $scheduleDays = $this->Schedule->ScheduleDay->find('list', array(
                'fields' => array('ScheduleDay.id', 'ScheduleDay.id'),
                'conditions' => array(
                    'ScheduleDay.schedule_id' => $schedules,
                ),
                    ));
            $points = $this->Schedule->ScheduleDay->ScheduleLine->find('list', array(
                'fields' => array('ScheduleLine.foreign_key', 'ScheduleLine.foreign_key'),
                'conditions' => array(
                    'ScheduleLine.schedule_day_id' => $scheduleDays,
                    'ScheduleLine.foreign_key > 0'
                ),
                    ));
            $sqlContent .= $this->Schedule->dumpSql(array('Schedule.id' => $schedules));
            $sqlContent .= $this->Schedule->ScheduleDay->dumpSql(array('ScheduleDay.id' => $scheduleDays));
            $sqlContent .= $this->Schedule->ScheduleDay->ScheduleLine->dumpSql(array('ScheduleLine.schedule_day_id' => $scheduleDays));
            $sqlContent .= $this->Schedule->ScheduleDay->ScheduleLine->Activity->dumpSql();
            $sqlContent .= $this->Schedule->ScheduleDay->ScheduleLine->Transport->dumpSql();
            $sqlContent .= $this->Schedule->Point->PointType->dumpSql();
            $sqlContent .= $this->Schedule->Point->dumpSql(array('Point.id' => $points));
            echo $sqlContent;
        }
        exit();
    }

    function go($token = '') {
        if (!empty($token) && file_exists(TMP . $token)) {
            $memberCreated = false;
            $tokenData = unserialize(file_get_contents(TMP . $token));
            unlink(TMP . $token);
            if (isset($tokenData['expire'])
                    && (mktime() - 5 < $tokenData['expire'])
                    && !empty($tokenData['auth']['provider'])
                    && !empty($tokenData['auth']['uid'])) {
                $oauth = $this->Member->Oauth->find('first', array(
                    'conditions' => array(
                        'provider' => $tokenData['auth']['provider'],
                        'uid' => $tokenData['auth']['uid'],
                    ),
                    'contain' => array('Member')
                        ));
                if (empty($oauth)) {
                    if (!empty($this->loginMember['id'])) {
                        $this->Member->Oauth->create();
                        $this->Member->Oauth->save(array('Oauth' => array(
                                'member_id' => $this->loginMember['id'],
                                'provider' => $tokenData['auth']['provider'],
                                'uid' => $tokenData['auth']['uid'],
                                )));
                    } else {
                        $email = md5($tokenData['auth']['provider'] . $tokenData['auth']['uid']) . '@travel.olc.tw';
                        switch ($tokenData['auth']['provider']) {
                            case 'Facebook':
                                $nickname = $tokenData['auth']['info']['nickname'];
                                break;
                            case 'Flickr':
                                $nickname = $tokenData['auth']['info']['nickname'];
                                break;
                            case 'GitHub':
                                $nickname = $tokenData['auth']['info']['nickname'];
                                break;
                            case 'Google':
                                $nickname = $tokenData['auth']['info']['name'];
                                $email = $tokenData['auth']['info']['email'];
                                break;
                            case 'LinkedIn':
                                $nickname = $tokenData['auth']['info']['name'];
                                break;
                        }
                        $this->Member->create();
                        if ($this->Member->save(array('Member' => array(
                                        'group_id' => 2,
                                        'area_id' => 0,
                                        'gender' => 'f',
                                        'email' => $email,
                                        'username' => $email,
                                        'bypass' => md5($email . 'e'),
                                        'user_status' => 'Y',
                                        'nickname' => $nickname,
                                        )))) {
                            $memberCreated = true;
                            $memberId = $this->Member->getInsertID();
                            $this->Member->Oauth->create();
                            $this->Member->Oauth->save(array('Oauth' => array(
                                    'member_id' => $memberId,
                                    'provider' => $tokenData['auth']['provider'],
                                    'uid' => $tokenData['auth']['uid'],
                                    )));
                            $member = $this->Member->read(null, $memberId);
                            $this->Session->write('Auth.User', $member['Member']);
                        } else {
                            if(!empty($this->Member->validationErrors['email'])) {
                                $this->Session->setFlash('您使用的信箱已經存在，也許可以先試著登入看看，或是透過遺忘密碼功能重設密碼');
                            }
                        }
                    }
                } else {
                    //login the user
                    if (empty($this->loginMember['id'])) {
                        $this->Session->write('Auth.User', $oauth['Member']);
                    }
                }
            }
        }
        if($memberCreated) {
            $this->redirect('/members/edit');
        } else {
            $this->redirect('/');
        }
    }

    function oauth() {
        //generate a 5 seconds token to store received data
        $tokenString = serialize(array_merge($this->data, array('expire' => mktime() + 5)));
        $token = md5($tokenString);
        file_put_contents(TMP . $token, $tokenString);
        $this->redirect('/members/go/' . $token);
        exit();
    }

    function admin_index() {
        $this->Paginator->settings['Member'] = array(
            'order' => array('modified' => 'desc'),
        );
        $this->set('members', $this->Paginator->paginate($this->Member));
    }

    function admin_add() {
        if (!empty($this->request->data)) {
            $this->Member->create();
            if ($this->Member->save($this->request->data)) {
                $this->Acl->Aro->saveField('alias', 'Member/' . $this->Member->getInsertID());
                $this->Session->setFlash('資料已經儲存！');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Member.data', $this->request->data);
                $this->Session->write('form.Member.validationErrors', $this->Member->validationErrors);
                $this->Session->setFlash('資料無法儲存，請重試！');
            }
        }
        $this->set('groups', $this->Member->Group->find('list'));
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            $oldgroupid = $this->Member->field('group_id', array('Member.id' => $this->request->data['Member']['id']));
            $this->Member->skipPasswordCheck = true;
            if ($this->Member->save($this->request->data)) {
                if ($oldgroupid !== $this->request->data['Member']['group_id']) {
                    $aro = & $this->Acl->Aro;
                    $member = $aro->findByForeignKeyAndModel($this->request->data['Member']['id'], 'Member');
                    $group = $aro->findByForeignKeyAndModel($this->request->data['Member']['group_id'], 'Group');
                    $aro->id = $member['Aro']['id'];
                    $aro->save(array('parent_id' => $group['Aro']['id']));
                }
                $this->Session->setFlash('資料已經儲存！');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Member.data', $this->request->data);
                $this->Session->write('form.Member.validationErrors', $this->Member->validationErrors);
                $this->Session->setFlash('資料無法儲存，請重試！');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Member->read(null, $id);
        }
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Member.data')) {
            $this->Member->validationErrors = $this->Session->read('form.Member.validationErrors');
            $this->Session->delete('form.Member');
        }
        if ($id > 0) {
            $this->request->data = $this->Member->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Member'][$key])) {
                        $this->request->data['Member'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }
        if (!empty($this->request->data['Member']['area_id'])) {
            $this->set('areaPath', $this->Member->Area->getPath($this->request->data['Member']['area_id'], array('name')));
        }
        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        $this->set('groups', $this->Member->Group->find('list'));
    }

    function admin_delete($id = 0) {
        $id = intval($id);
        if ($this->Member->delete($id)) {
            $this->Session->setFlash('資料刪除了！');
        } else {
            $this->Session->setFlash('資料刪除失敗！');
        }
        $this->redirect(array('action' => 'index'));
    }

    function admin_acos() {
        $this->buildAcl();
        $this->redirect($this->referer());
    }

    function admin_active($memberId = 0) {
        $memberId = intval($memberId);
        if ($memberId > 0) {
            $member = $this->Member->read(null, $memberId);
        }
        if (!empty($member)) {
            $emailObj = new CakeEmail('gmail');
            $emailObj->emailFormat('both');
            $emailObj->viewVars(array(
                'uid' => $memberId,
                'code' => Security::hash(Configure::read('Security.salt') . $member['Member']['email']),
            ));
            $emailObj->template($this->locale . DS . 'active');
            $emailObj->from(array('service@olc.tw' => '就愛玩'));
            $emailObj->to($member['Member']['email']);
            $emailObj->subject('就愛玩網站註冊通知信');
            $emailObj->send();
        }
        $this->Session->setFlash('操作完成');
        $this->redirect(array('action' => 'index'));
    }

    function admin_message() {
        if (!empty($this->request->data['Member']['message'])) {
            $members = $this->Member->find('list', array(
                'fields' => array('nickname', 'email'),
                'conditions' => array(
                    'Member.user_status' => 'Y',
                    'Member.email NOT LIKE \'%@travel.olc.tw\''
                ),
                    ));
            $emailObj = new CakeEmail('gmail');
            $emailObj->emailFormat('both');
            $emailObj->template($this->locale . DS . 'default');
            $emailObj->viewVars(array(
                'message' => $this->request->data['Member']['message'],
            ));
            $emailObj->from(array('service@olc.tw' => '就愛玩'));
            $subject = '就愛玩的來信';
            if (!empty($this->request->data['Member']['subject'])) {
                $subject .= ' | ' . $this->request->data['Member']['subject'];
            }
            $emailObj->subject($subject);
            /*
              $emailObj->addBcc(array(
              'kiang@osobiz.com',
              ));
              $emailObj->send();
             * 
             */
            $bcc = array();
            foreach ($members AS $nickname => $email) {
                if (count($bcc) == 60) {
                    $emailObj->addBcc($bcc);
                    $emailObj->send();
                    $bcc = array();
                }
                $bcc[] = $email;
            }
            if (!empty($bcc)) {
                $emailObj->addBcc($bcc);
                $emailObj->send();
            }
            $this->Session->setFlash('訊息已經送出');
        }
    }

}