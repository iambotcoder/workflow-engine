<?php
require_once '../rb-mysql.php';

class Book
{
    public function __construct()
    {
        R::setup('mysql:host=localhost;dbname=workflow_engine', 'root', '');
    }

    public function insert($title, $author)
    {
        $book = R::dispense('book');
        $book->title = $title;
        $book->author = $author;
        return R::store($book);
    }

    public function update($id, $title, $author)
    {
        $book = R::load('book', $id);
        if ($book->id) {
            $book->title = $title;
            $book->author = $author;
            return R::store($book);
        }
        return false;
    }

    public function delete($id)
    {
        $book = R::load('book', $id);
        if ($book->id) {
            R::trash($book);
            return true;
        }
        return false;
    }

    public function get($id)
    {
        return R::load('book', $id);
    }

    public function getAll()
    {
        return R::findAll('book');
    }
}
?>
