<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class TransactionController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for transaction
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Transaction", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $transaction = Transaction::find($parameters);
        if (count($transaction) == 0) {
            $this->flash->notice("The search did not find any transaction");

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $transaction,
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
     * Edits a transaction
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $transaction = Transaction::findFirstByid($id);
            if (!$transaction) {
                $this->flash->error("transaction was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "transaction",
                    "action" => "index"
                ));
            }

            $this->view->id = $transaction->id;

            $this->tag->setDefault("id", $transaction->id);
            $this->tag->setDefault("student_id", $transaction->student_id);
            $this->tag->setDefault("librarian_id", $transaction->librarian_id);
            $this->tag->setDefault("book_id", $transaction->book_id);
            $this->tag->setDefault("action", $transaction->action);
            $this->tag->setDefault("time", $transaction->time);
            
        }
    }

    /**
     * Creates a new transaction
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "index"
            ));
        }

        $transaction = new Transaction();

        $transaction->id = $this->request->getPost("id");
        $transaction->student_id = $this->request->getPost("student_id");
        $transaction->librarian_id = $this->request->getPost("librarian_id");
        $transaction->book_id = $this->request->getPost("book_id");
        $transaction->action = $this->request->getPost("action");
        $transaction->time = $this->request->getPost("time");
        

        if (!$transaction->save()) {
            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "new"
            ));
        }

        $this->flash->success("transaction was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "transaction",
            "action" => "index"
        ));

    }

    /**
     * Saves a transaction edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $transaction = Transaction::findFirstByid($id);
        if (!$transaction) {
            $this->flash->error("transaction does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "index"
            ));
        }

        $transaction->id = $this->request->getPost("id");
        $transaction->student_id = $this->request->getPost("student_id");
        $transaction->librarian_id = $this->request->getPost("librarian_id");
        $transaction->book_id = $this->request->getPost("book_id");
        $transaction->action = $this->request->getPost("action");
        $transaction->time = $this->request->getPost("time");
        

        if (!$transaction->save()) {

            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "edit",
                "params" => array($transaction->id)
            ));
        }

        $this->flash->success("transaction was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "transaction",
            "action" => "index"
        ));

    }

    /**
     * Deletes a transaction
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $transaction = Transaction::findFirstByid($id);
        if (!$transaction) {
            $this->flash->error("transaction was not found");

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "index"
            ));
        }

        if (!$transaction->delete()) {

            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "transaction",
                "action" => "search"
            ));
        }

        $this->flash->success("transaction was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "transaction",
            "action" => "index"
        ));
    }

}
