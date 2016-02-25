<?php

class PhpBB3Component extends Component {

    var $controller;
    var $model;

    function startup(&$controller) {

        $this->controller = &$controller;

        define('IN_PHPBB', true);

        global $phpbb_root_path, $phpEx, $db, $config, $user, $auth, $cache, $template;

        $phpbb_root_path = WWW_ROOT . 'talks/';
        $phpEx = substr(strrchr(__FILE__, '.'), 1);
        require_once($phpbb_root_path . 'common.' . $phpEx);

        $this->table_prefix = $table_prefix;
        $this->auth = $auth;
        $this->user = $user;

        // Start session management
        $this->user->session_begin(false);
        $this->auth->acl($user->data);
        $this->user->setup();

        require_once($phpbb_root_path . 'includes/functions_user.php');
    }

    private function checkUserExists($username, $isFalse = false) {

        if (user_get_id_name($isFalse, $username) == 'NO_USERS') {
            return false;
        } else {
            return true;
        }
    }

    public function logout() {
        $this->user->session_kill();
        $this->user->session_begin();
    }

}