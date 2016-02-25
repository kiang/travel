<?php
class ScheduleDaySummaryShell extends AppShell {
    public $uses = array('ScheduleDay');
    public function main() {
        $scheduleDays = $this->ScheduleDay->find('list', array(
            'fields' => array('id', 'id')
        ));
        foreach($scheduleDays AS $scheduleDay) {
            $this->ScheduleDay->ScheduleLine->scheduleDayId = $scheduleDay;
            $this->ScheduleDay->ScheduleLine->updateScheduleDay();
        }
    }
}
