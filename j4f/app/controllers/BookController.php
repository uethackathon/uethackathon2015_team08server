<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class BookController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for book
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Book", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $book = Book::find($parameters);
        if (count($book) == 0) {
            $this->flash->notice("The search did not find any book");

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $book,
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
     * Edits a book
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $book = Book::findFirstByid($id);
            if (!$book) {
                $this->flash->error("book was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "book",
                    "action" => "index"
                ));
            }

            $this->view->id = $book->id;

            $this->tag->setDefault("id", $book->id);
            $this->tag->setDefault("name", $book->name);
            $this->tag->setDefault("ISBN", $book->ISBN);
            $this->tag->setDefault("author", $book->author);
            $this->tag->setDefault("short_description", $book->short_description);
            $this->tag->setDefault("page_number", $book->page_number);
            $this->tag->setDefault("publisher", $book->publisher);
            
        }
    }

    /**
     * Creates a new book
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "index"
            ));
        }

        $book = new Book();

        $book->id = $this->request->getPost("id");
        $book->name = $this->request->getPost("name");
        $book->ISBN = $this->request->getPost("ISBN");
        $book->author = $this->request->getPost("author");
        $book->short_description = $this->request->getPost("short_description");
        $book->page_number = $this->request->getPost("page_number");
        $book->publisher = $this->request->getPost("publisher");
        

        if (!$book->save()) {
            foreach ($book->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "new"
            ));
        }

        $this->flash->success("book was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "book",
            "action" => "index"
        ));

    }

    /**
     * Saves a book edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $book = Book::findFirstByid($id);
        if (!$book) {
            $this->flash->error("book does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "index"
            ));
        }

        $book->id = $this->request->getPost("id");
        $book->name = $this->request->getPost("name");
        $book->ISBN = $this->request->getPost("ISBN");
        $book->author = $this->request->getPost("author");
        $book->short_description = $this->request->getPost("short_description");
        $book->page_number = $this->request->getPost("page_number");
        $book->publisher = $this->request->getPost("publisher");
        

        if (!$book->save()) {

            foreach ($book->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "edit",
                "params" => array($book->id)
            ));
        }

        $this->flash->success("book was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "book",
            "action" => "index"
        ));

    }

    /**
     * Deletes a book
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $book = Book::findFirstByid($id);
        if (!$book) {
            $this->flash->error("book was not found");

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "index"
            ));
        }

        if (!$book->delete()) {

            foreach ($book->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "book",
                "action" => "search"
            ));
        }

        $this->flash->success("book was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "book",
            "action" => "index"
        ));
    }

}
