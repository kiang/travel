<?php

App::uses('AppController', 'Controller');

/**
 * ScheduleNotes Controller
 *
 * @property ScheduleNote $ScheduleNote
 */
class ScheduleNotesController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('schedule');
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($scheduleId = 0, $dayId = 0, $lineId = 0) {
        $scheduleId = intval($scheduleId);
        $dayId = intval($dayId);
        $lineId = intval($lineId);
        if ($scheduleId > 0 && $this->ScheduleNote->Schedule->find('count', array(
                    'conditions' => array(
                        'Schedule.member_id' => $this->loginMember['id'],
                        'Schedule.id' => $scheduleId
                    ),
                )) == 0) {
            //loginMember doesn't own the schedule
            $scheduleId = 0;
        }
        if ($scheduleId <= 0) {
            $this->Session->setFlash('請依據網頁指示操作！');
            $this->redirect('/');
            exit();
        }
        if ($dayId > 0 && $this->ScheduleNote->ScheduleDay->find('count', array(
                    'conditions' => array(
                        'ScheduleDay.id' => $dayId,
                        'ScheduleDay.schedule_id' => $scheduleId,
                    ),
                )) == 0) {
            $dayId = 0;
            $lineId = 0;
        }
        if ($lineId > 0 && $this->ScheduleNote->ScheduleLine->find('count', array(
                    'conditions' => array(
                        'ScheduleLine.id' => $lineId,
                        'ScheduleLine.schedule_day_id' => $dayId,
                    ),
                )) == 0) {
            $lineId = 0;
        }
        if ($this->request->is('post')) {
            $this->request->data['ScheduleNote']['schedule_id'] = $scheduleId;
            $this->request->data['ScheduleNote']['schedule_day_id'] = $dayId;
            $this->request->data['ScheduleNote']['schedule_line_id'] = $lineId;
            $this->request->data['ScheduleNote']['member_id'] = $this->loginMember['id'];
            $this->request->data['ScheduleNote']['sort'] = $this->ScheduleNote->field('sort', array(
                'schedule_id' => $scheduleId,
                'schedule_day_id' => $dayId,
                'schedule_line_id' => $lineId,
                    ), array(
                'ScheduleNote.sort DESC'
                    ));
            if (empty($this->request->data['ScheduleNote']['sort'])) {
                $this->request->data['ScheduleNote']['sort'] = 1;
            } else {
                $this->request->data['ScheduleNote']['sort'] +=1;
            }
            $this->ScheduleNote->create();
            if ($this->ScheduleNote->save($this->request->data)) {
                echo 'ok';
                exit();
            }
        }
        $this->set('formAction', array($scheduleId, $dayId, $lineId));
        $this->set('scheduleId', $scheduleId);
        $this->set('dayId', $dayId);
    }

    public function delete($noteId = 0) {
        $noteId = intval($noteId);
        if ($noteId > 0) {
            $note = $this->ScheduleNote->find('first', array(
                'fields' => array('schedule_id', 'schedule_day_id'),
                'conditions' => array(
                    'id' => $noteId,
                    'member_id' => $this->loginMember['id'],
                    )));
        }
        if (!empty($note) && $this->ScheduleNote->delete($noteId)) {
            echo 'ok';
        }
        exit();
    }

    public function schedule($scheduleId = 0) {
        $scheduleId = intval($scheduleId);
        if ($scheduleId > 0) {
            $schedule = $this->ScheduleNote->Schedule->find('first', array(
                'fields' => array('id', 'member_id'),
                'conditions' => array(
                    'Schedule.id' => $scheduleId,
                ),
                    ));
        }
        if (empty($schedule)) {
            exit();
        } else {
            $this->set('notes', $this->ScheduleNote->find('all', array(
                        'conditions' => array(
                            'schedule_id' => $scheduleId,
                            'schedule_day_id' => 0,
                            'schedule_line_id' => 0,
                        ),
                        'order' => array('sort ASC'),
                    )));
            $this->set('schedule', $schedule);
        }
    }

    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
        $this->paginate = array(
            'order' => 'ScheduleNote.id DESC',
            'contain' => array('Schedule', 'ScheduleDay', 'ScheduleLine'),
        );
        $this->set('scheduleNotes', $this->paginate($this->ScheduleNote));
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->ScheduleNote->id = $id;
        if (!$this->ScheduleNote->exists()) {
            throw new NotFoundException(__('Invalid schedule note'));
        }
        if ($this->ScheduleNote->delete()) {
            $this->Session->setFlash(__('Schedule note deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Schedule note was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}