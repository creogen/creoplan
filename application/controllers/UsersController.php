<?php

class UsersController extends Planner_Controller
{
    public function indexAction()
    {
        $userMapper = new Application_Model_UserMapper();
        $users = $userMapper->fetchAll();
        $this->view->users = $users;
    }

    public function editAction()
    {
        $form = new Application_Form_User();
        $id = $this->_request->getParam('id');

        $userMapper = new Application_Model_UserMapper();
        /**
         * @var Application_Model_User $user
         */
        $user = $userMapper->findOrCreate($id);

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $user->setLogin($form->getValue('login'));
                $user->setRole($form->getValue('role'));
                if ($form->getValue('password')) {
                    $user->setPassword($form->getValue('password'));
                }
                $userMapper->save($user);
                $this->_redirect('/users/');
            }
        } else {
            $values = $user->getPropertyArray();
            unset($values['password']);
            $form->populate($values);
        }

        $this->view->form = $form;
    }

    public function viewAction()
    {
        $this->_redirect(sprintf('/users/edit/id/%d/', $this->_request->getParam('id')));
    }

    public function dropAction()
    {
        $userMapper = new Application_Model_UserMapper();
        $user = $userMapper->find($this->_request->getParam('id'));

        if ($user && $this->mayBeDeleted($user)) {
            $userMapper->drop($user);
        }

        $this->_redirect('/users/');
    }

    private function mayBeDeleted(Application_Model_User $user)
    {
        $taskMapper = new Application_Model_TaskMapper();
        $totalTasks = $taskMapper->countTotalForUser($user->getId());
        if ($totalTasks) {
            return false;
        }
        $projectMapper = new Application_Model_ProjectMapper();
        $totalProjects = $projectMapper->countManagedByUser($user->getId());
        if ($totalProjects) {
            return false;
        }

        return true;
    }

}





