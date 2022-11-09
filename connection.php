<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database_name = "moodle";

// Start a connection
$conn = mysqli_connect($host, $username, $password, $database_name);

//Check that connection exists
if (!$conn) {
  die(mysqli_error($conn));
}

// Perform query against the database
function dbQuery($sql)
{
  global $conn;
  $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  return $result;
}

// Return the number of rows in a result set
function dbNumRows($result)
{
  return mysqli_num_rows($result);
}

// Fetch the result row as an associative array
function dbFetchAssoc($result)
{
  return mysqli_fetch_assoc($result);
}

// Close the connection
function closeConn()
{
  global $conn;
  mysqli_close($conn);
}
?>