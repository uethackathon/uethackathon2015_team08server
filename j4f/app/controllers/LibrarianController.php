<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class LibrarianController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for librarian
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Librarian", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $librarian = Librarian::find($parameters);
        if (count($librarian) == 0) {
            $this->flash->notice("The search did not find any librarian");

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $librarian,
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
     * Edits a librarian
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $librarian = Librarian::findFirstByid($id);
            if (!$librarian) {
                $this->flash->error("librarian was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "librarian",
                    "action" => "index"
                ));
            }

            $this->view->id = $librarian->id;

            $this->tag->setDefault("id", $librarian->id);
            $this->tag->setDefault("first_name", $librarian->first_name);
            $this->tag->setDefault("last_name", $librarian->last_name);
            $this->tag->setDefault("email", $librarian->email);
            $this->tag->setDefault("user_name", $librarian->user_name);
            $this->tag->setDefault("password", $librarian->password);
            
        }
    }

    /**
     * Creates a new librarian
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "index"
            ));
        }

        $librarian = new Librarian();

        $librarian->id = $this->request->getPost("id");
        $librarian->first_name = $this->request->getPost("first_name");
        $librarian->last_name = $this->request->getPost("last_name");
        $librarian->email = $this->request->getPost("email", "email");
        $librarian->user_name = $this->request->getPost("user_name");
        $librarian->password = $this->request->getPost("password");
        

        if (!$librarian->save()) {
            foreach ($librarian->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "new"
            ));
        }

        $this->flash->success("librarian was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "librarian",
            "action" => "index"
        ));

    }

    /**
     * Saves a librarian edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $librarian = Librarian::findFirstByid($id);
        if (!$librarian) {
            $this->flash->error("librarian does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "index"
            ));
        }

        $librarian->id = $this->request->getPost("id");
        $librarian->first_name = $this->request->getPost("first_name");
        $librarian->last_name = $this->request->getPost("last_name");
        $librarian->email = $this->request->getPost("email", "email");
        $librarian->user_name = $this->request->getPost("user_name");
        $librarian->password = $this->request->getPost("password");
        

        if (!$librarian->save()) {

            foreach ($librarian->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "edit",
                "params" => array($librarian->id)
            ));
        }

        $this->flash->success("librarian was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "librarian",
            "action" => "index"
        ));

    }

    /**
     * Deletes a librarian
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $librarian = Librarian::findFirstByid($id);
        if (!$librarian) {
            $this->flash->error("librarian was not found");

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "index"
            ));
        }

        if (!$librarian->delete()) {

            foreach ($librarian->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "librarian",
                "action" => "search"
            ));
        }

        $this->flash->success("librarian was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "librarian",
            "action" => "index"
        ));
    }

}
