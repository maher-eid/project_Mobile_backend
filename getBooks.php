<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

/* PostgreSQL connection (Railway) */
$conn = pg_connect(
    "host=" . getenv("PGHOST") .
    " dbname=" . getenv("PGDATABASE") .
    " user=" . getenv("PGUSER") .
    " password=" . getenv("PGPASSWORD") .
    " port=" . getenv("PGPORT")
);

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

/* SQL Query */
$sql = "
SELECT 
  books.book_id,
  books.title,
  books.genre,
  books.stock,
  books.price,
  authors.author
FROM books
INNER JOIN authors ON books.author_id = authors.id
";

/* Execute query */
$result = pg_query($conn, $sql);

$books = [];

if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $books[] = $row;
    }
}

/* Output JSON */
echo json_encode($books);

/* Close connection */
pg_close($conn);
