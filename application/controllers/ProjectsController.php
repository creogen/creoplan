<?php

class ProjectsController extends Planner_Controller
{
    public function indexAction()
    {
        $projectMapper = new Application_Model_ProjectMapper();
        $projects = $projectMapper->fetchAll();
        $this->view->projects = $projects;
    }

    public function viewAction()
    {
        // action body
        $this->_redirect(sprintf('/projects/edit/id/%d', $this->_request->getParam('id')));
    }

    public function editAction()
    {
        $form = new Application_Form_Project();
        $id = $this->_request->getParam('id');

        $projectMapper = new Application_Model_ProjectMapper();
        $project = $projectMapper->findOrCreate($id);

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $project->setTitle($form->getValue('title'));
                $project->setManagerId($form->getValue('manager_id'));
                $projectMapper->save($project);
                $this->_redirect('/projects/');
            }
        } else {
            $form->populate($project->getPropertyArray());
        }

        $this->view->form = $form;
    }

    public function dropAction()
    {
        $projectMapper = new Application_Model_ProjectMapper();
        $project = $projectMapper->find($this->_request->getParam('id'));
        if ($project) {
            $taskMapper = new Application_Model_TaskMapper();
            $taskMapper->dropByProject($project->getId());
            $projectMapper->drop($project);
        }
        $this->_redirect('/projects/');
    }
}





