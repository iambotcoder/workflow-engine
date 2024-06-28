<?php
require_once '../models/Book.php';

class BookController
{
    private $bookModel;

    public function __construct()
    {
        $this->bookModel = new Book();
    }

    public function insert($title, $author)
    {
        return $this->bookModel->insert($title, $author);
    }

    public function update($id, $title, $author)
    {
        return $this->bookModel->update($id, $title, $author);
    }

    public function delete($id)
    {
        return $this->bookModel->delete($id);
    }

    public function get($id)
    {
        return $this->bookModel->get($id);
    }

    public function getAll()
    {
        return $this->bookModel->getAll();
    }
}
?>
