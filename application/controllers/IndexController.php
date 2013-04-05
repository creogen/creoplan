<?php

class IndexController extends Planner_Controller
{
    public function indexAction()
    {
        $taskMapper = new Application_Model_TaskMapper();
        $tasks = $taskMapper->findActualTasksForUser($this->_helper->auth()->getId());
        $this->view->tasks = $tasks;
    }


}

