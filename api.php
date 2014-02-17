<?php
ini_set('html_errors', 1); 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once 'medoo.min.php';

$database = new medoo([
    'database_type' => 'sqlite',
    'database_file' => 'database.db'
]);

// Create database if necessary 
$database->query("CREATE TABLE IF NOT EXISTS quotes (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   quote TEXT NOT NULL,
   attribution TEXT NULL
    );");




switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':

    // Get the page index and page size
    $page = empty($_GET["page"]) ? 0 : $_GET["page"];
    $pageSize = empty($_GET["pageSize"]) ? 10 : $_GET["pageSize"];
    $offset = ($page == 0 ? 0 : 1);

    // Request data
    $datas = $database->select("quotes", [
        "id", "quote", "attribution", "timestamp"
    ], [
        "ORDER" => "quotes.id DESC",
        "LIMIT" => [$page * $pageSize - $offset, $pageSize + 1 + $offset],
    ]);

    $response = array();
    $previous = -1;
    if ($offset == 1)
    {
        $previous = $datas[0]["id"];
        array_shift($datas);
    }
    $hasMore = (count($datas) == ($pageSize + 1));
    if ($hasMore)
    {
        array_pop($datas);
    }
    $response["quotes"] = $datas;
    $response["more"] = $hasMore;
    $response["previous"] = $previous;

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);

    break;
  case 'POST':
    $postString = file_get_contents("php://input");
    $postData = json_decode($postString, true);
    $quote = trim($postData["quote"]);
    if (empty($quote))
    {
        exit("Quote required");
    }

    $quoteId = $database->insert("quotes", [
        "quote" => $quote,
        "attribution" => !empty($postData["attribution"]) ? $postData["attribution"] : null
    ]);

    $response = array();
    $response["quote_id"] = $quoteId;
    header('Content-Type: application/json');
    echo json_encode($response);

    break;

  default:
    exit("Request method " . $_SERVER['REQUEST_METHOD'] . "is not supported.");
    break;
}


?>