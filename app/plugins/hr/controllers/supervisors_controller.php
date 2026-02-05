<?php

class SupervisorsController extends HrAppController {

    var $name = 'Supervisors';

    function Edit($id = null) {
        if (!empty($this->data)) {
            if (!isset($this->data['Supervisor']['id'])) {
                //Give Employee ESS Permission
                $this->loadModel('Employee');
                $employee = $this->Employee->read(null, $this->data['Supervisor']['sup_emp_id']);
                $this->loadModel('GroupsUser');
                $this->GroupsUser->create();
                $this->data['GroupsUser']['group_id'] = '26';
                $this->data['GroupsUser']['user_id'] = $employee['Employee']['user_id'];
                $this->GroupsUser->save($this->data);
                //-------------------

                $this->Supervisor->create();
            }
            $this->autoRender = false;
            if ($this->Supervisor->save($this->data)) {
                $this->Session->setFlash(__('The supervisor has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The supervisor could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->Supervisor->recursive = 3;
        $this->set('supervisor', $sup = $this->Supervisor->findByemp_id($id));
        $this->loadModel('Employee');
        $this->Employee->recursive = 3;
        $this->set('employee', $this->Employee->read(null, $id));
    }

}

?>
