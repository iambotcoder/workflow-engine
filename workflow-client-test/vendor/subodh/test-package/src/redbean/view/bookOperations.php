<?php
require_once '../controller/BookController.php';

$controller = new BookController();

// $controller->insert("Book-1","Author-1");
// $controller->insert("Book-2","Author-2");
// $controller->insert("Book-3","Author-3");
// $controller->insert("Book-4","Author-4");
// $controller->insert("Book-5","Author-5");
// $controller->update('1', "Book_1","Author_1");
// $controller->delete("5");

// Function to handle insert, update, and delete operations
// function handleOperation($controller) {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $action = $_POST['action'];
//         $id = $_POST['id'] ?? null;
//         $title = $_POST['title'] ?? '';
//         $author = $_POST['author'] ?? '';

//         if ($action === 'insert') {
//             $controller->insert($title, $author);
//             echo "Inserted successfully.";
//         } elseif ($action === 'update') {
//             $controller->update($id, $title, $author);
//             echo "Updated successfully.";
//         } elseif ($action === 'delete') {
//             $controller->delete($id);
//             echo "Deleted successfully.";
//         }
//     } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
//         $id = $_GET['id'];
//         $book = $controller->get($id);
//         if ($book) {
//             echo "ID: " . $book->id . "<br>";
//             echo "Title: " . $book->title . "<br>";
//             echo "Author: " . $book->author . "<br>";
//         } else {
//             echo "No book found with ID $id.";
//         }
//     } else {
//         echo "No valid action provided.";
//     }
// }

// handleOperation($controller);
?>
