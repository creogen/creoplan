<?php

/**
 * @throws Zend_Controller_Action_Exception
 * @property Zend_Controller_Request_Http $_request
 *
 */
class TasksController extends Planner_Controller
{

    public function indexAction()
    {
        $projectId = $this->_request->getParam('project_id');
        $filter = $this->_request->getParam('filter');
        $assignedTo = $this->_request->getParam('assigned');

        $this->view->filter = $filter ? $filter : 'all';
        $this->view->assigned = $assignedTo ? $assignedTo : 'all';

        $taskMapper = new Application_Model_TaskMapper();
        if (!$projectId && !$filter && !$assignedTo) {
            $tasks = $taskMapper->findAll();
        } else {
            $projectMapper = new Application_Model_ProjectMapper();
            $project = $projectMapper->find($projectId);
            $this->view->project = $project;
            $tasks = $taskMapper->filterByProjectFilterAndAssignedTo(
                $projectId,
                $filter != 'all' ? $filter : null,
                $assignedTo == 'me' ? $this->_helper->auth()->getId() : null
            );
        }

        $this->view->tasks = $tasks;
    }

    public function viewAction()
    {
        if (!$this->_request->getParam('id')) {
            $this->_redirect('/');
        }

        $taskId = $this->_request->getParam('id');
        $taskMapper = new Application_Model_TaskMapper();
        $task = $taskMapper->find($taskId);

        if (!$task) {
            throw new Zend_Controller_Action_Exception('Задание не найдено');
        }

        $commentForm = new Application_Form_Comment();
        $commentMapper = new Application_Model_CommentMapper();
        $comment = $commentMapper->findOrCreate(0);
        if ($this->_request->isPost()) {
            if ($commentForm->isValid($this->_request->getParams())) {
                $comment->setTaskId($task->getId());
                $comment->setUserId($this->_helper->auth()->getId());
                $comment->setPropertyArray($commentForm->getValues());
                $commentMapper->save($comment);
                $this->_redirect('/tasks/view/id/' . $task->getId());
            }
        } else {
            $commentForm->populate($comment->getPropertyArray());
        }
        $this->view->commentForm = $commentForm;

        $comments = $commentMapper->findByTask($task->getId());
        $this->view->comments = $comments;

        $fileMapper = new Application_Model_FileMapper();
        $files = $fileMapper->findByTask($task->getId());
        $this->view->files = $files;

        $uploadForm = new Application_Form_File();
        $uploadForm->populate(array('id' => $task->getId()));
        $this->view->uploadForm = $uploadForm;

        $this->view->task = $task;
    }

    public function editAction()
    {
        $form = new Application_Form_Task();

        $id = $this->_request->getParam('id');

        $taskMapper = new Application_Model_TaskMapper();
        /**
         * @var Application_Model_Task $task
         */
        $task = $taskMapper->findOrCreate($id);

        $projectId = $this->_request->getParam('project_id');
        if (!$task->getId() && $projectId) {

            $form->populate(array(
                'project_id' => $projectId,
            ));
        } elseif ($task->getId()) {
            $projectId = $task->getProjectId();
        }
        $this->view->projectId = $projectId;

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $task->setPropertyArray($form->getValues());
                if (!$task->getId()) {
                    $task->setCreateDate(time());
                    $task->setStatus('waiting');
                    $task->setCreatedBy($this->_helper->auth()->getId());
                }
                $taskMapper->save($task);
                $this->_redirect('/tasks/view/id/' . $task->getId());
            }
        } else {
            $form->populate($task->getPropertyArray());
        }

        $this->view->form = $form;
    }

    public function changeStatusAction()
    {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');

        if (!$this->_request->isPost()) {
            $this->_redirect('/projects/');
        }

        $id = $this->_request->getPost('id');
        $action = $this->_request->getPost('action');

        $taskMapper = new Application_Model_TaskMapper();
        /**
         * @var Application_Model_Task $task
         */
        $task = $taskMapper->find($id);

        if (!$task) {
            $this->_redirect('/projects/');
        }

        switch ($action) {
            case 'start' :
                $task->setStatus('processing');
                if (!$task->getStartDate()) {
                    $task->setStartDate(time());
                }
                break;
            case 'end' :
                $task->setStatus('completed');
                $task->setEndDate(time());
                break;
            case 'wait' :
                $task->setStatus('waiting');
                break;
        }
        $taskMapper->save($task);

        $this->_redirect(sprintf('/tasks/view/id/%d/', $task->getId()));
    }

    public function uploadFileAction()
    {
        Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
        if (!$this->_request->isPost()) {
            $this->_redirect('/projects/');
        }

        $id = $this->_request->getPost('id');

        $taskMapper = new Application_Model_TaskMapper();
        /**
         * @var Application_Model_Task $task
         */
        $task = $taskMapper->find($id);

        if (!$task) {
            $this->_redirect('/projects/');
        }

        $uploadForm = new Application_Form_File();
        $uploadForm->populate(array('id' => $task->getId()));

        if ($uploadForm->isValid($this->_request->getPost())) {
            $uploadForm->filename->receive();

            if ($uploadForm->filename->isUploaded()) {


                $fileMapper = new Application_Model_FileMapper();
                $file = $fileMapper->findOrCreate(0);

                $fileProperties = array(
                    'task_id' => $task->getId(),
                    'file_path' => $uploadForm->filename->getFileName(null, false),
                    'user_id' => $this->_helper->auth()->getId(),
                    'upload_date' => time(),
                );
                $file->setPropertyArray($fileProperties);
                $fileMapper->save($file);
            } else {
                throw new Zend_Controller_Action_Exception('Файл не был загружен');
            }
        }
        $this->_redirect(sprintf('/tasks/view/id/%d/', $task->getId()));
    }
}