<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class StudentController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for student
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Student", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $student = Student::find($parameters);
        if (count($student) == 0) {
            $this->flash->notice("The search did not find any student");

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $student,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a student
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $student = Student::findFirstByid($id);
            if (!$student) {
                $this->flash->error("student was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "student",
                    "action" => "index"
                ));
            }

            $this->view->id = $student->id;

            $this->tag->setDefault("id", $student->id);
            $this->tag->setDefault("first_name", $student->first_name);
            $this->tag->setDefault("last_name", $student->last_name);
            $this->tag->setDefault("user_name", $student->user_name);
            $this->tag->setDefault("email", $student->email);
            $this->tag->setDefault("password", $student->password);
            
        }
    }

    /**
     * Creates a new student
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "index"
            ));
        }

        $student = new Student();

        $student->id = $this->request->getPost("id");
        $student->first_name = $this->request->getPost("first_name");
        $student->last_name = $this->request->getPost("last_name");
        $student->user_name = $this->request->getPost("user_name");
        $student->email = $this->request->getPost("email", "email");
        $student->password = $this->request->getPost("password");
        

        if (!$student->save()) {
            foreach ($student->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "new"
            ));
        }

        $this->flash->success("student was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "student",
            "action" => "index"
        ));

    }

    /**
     * Saves a student edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $student = Student::findFirstByid($id);
        if (!$student) {
            $this->flash->error("student does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "index"
            ));
        }

        $student->id = $this->request->getPost("id");
        $student->first_name = $this->request->getPost("first_name");
        $student->last_name = $this->request->getPost("last_name");
        $student->user_name = $this->request->getPost("user_name");
        $student->email = $this->request->getPost("email", "email");
        $student->password = $this->request->getPost("password");
        

        if (!$student->save()) {

            foreach ($student->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "edit",
                "params" => array($student->id)
            ));
        }

        $this->flash->success("student was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "student",
            "action" => "index"
        ));

    }

    /**
     * Deletes a student
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $student = Student::findFirstByid($id);
        if (!$student) {
            $this->flash->error("student was not found");

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "index"
            ));
        }

        if (!$student->delete()) {

            foreach ($student->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "student",
                "action" => "search"
            ));
        }

        $this->flash->success("student was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "student",
            "action" => "index"
        ));
    }

}
