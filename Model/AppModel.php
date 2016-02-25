<?php

class AppModel extends Model {

    var $actsAs = array('Containable');
    var $recursive = -1;

    function beforeValidate($options = array()) {
        /*
         * 過濾所有的 html 標籤
         */
        foreach ($this->data AS $model => $data) {
            foreach ($data AS $key => $val) {
                if (!is_array($val)) {
                    $this->data[$model][$key] = strip_tags($val);
                }
            }
        }
        return true;
    }

    function paginateCount($conditions = array(), $recursive = -1, $extra = array()) {
        $parameters = compact('conditions');
        if ($recursive != $this->recursive) {
            $parameters['recursive'] = $recursive;
        }
        if (!empty($extra['group'])) {
            $this->find('count', array_merge($parameters, $extra));
            return $this->getAffectedRows();
        } else {
            return $this->find('count', array_merge($parameters, $extra));
        }
    }

    public function dumpSql($conditions = array()) {
        $records = $this->find('all', array(
            'conditions' => $conditions
                ));
        $stack = array();
        foreach ($records AS $record) {
            foreach ($record[$this->name] AS $key => $val) {
                $record[$this->name][$key] = addslashes($val);
                $record[$this->name][$key] = str_replace('\\\'', '\'', $val);
            }
            $stack[] = 'INSERT INTO ' . $this->table . ' VALUES ("' . implode('","', $record[$this->name]) . '");';
        }
        return implode("\n", $stack);
    }

}