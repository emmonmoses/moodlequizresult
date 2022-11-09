<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

require_once 'connection.php';
require_once('activity.php');

$sql = "SELECT c.id, c.quiz, b.name activityname, c.userid, cg.idnumber idnumber, cg.firstname firstname, cg.lastname lastname, c.grade, c.timemodified
FROM mdl_quiz_grades c
    INNER JOIN mdl_quiz b ON c.quiz = b.id
    INNER JOIN mdl_user cg ON c.userid  = cg.id 
    WHERE   cg.idnumber != ''
    ORDER BY idnumber";

$result = dbQuery($sql);
$rows = dbNumRows($result);

$pageSize = 10;
$pages = ceil($rows / $pageSize);

// the current page number and default row_list
if (!isset($_GET['page']) || !isset($_GET['pageSize'])) {
    $page = 1;
    $pageSize = 10;
}
else {
    $page = $_GET['page'];
    $pageSize = $_GET['pageSize'];
}

// LIMIT starting number for the records on the current page
$this_page_first_result = ($page - 1) * $pageSize;

$sql = "SELECT c.id, c.quiz, b.name activityname, c.userid, cg.idnumber idnumber, cg.firstname firstname, cg.lastname lastname, c.grade, c.timemodified
FROM mdl_quiz_grades c
    INNER JOIN mdl_quiz b ON c.quiz = b.id
    INNER JOIN mdl_user cg ON c.userid  = cg.id 
    WHERE   cg.idnumber != ''
    ORDER BY idnumber LIMIT " . $this_page_first_result . "," . $pageSize;

$result = dbQuery($sql);
$i = 0;
$response = array();
$errorResponse = array();

if (dbNumRows($result) > 0) {
    while ($row = dbFetchAssoc($result)) {
        $activityResult = new Activity();
        $activityResult->dbId = $row['id'];
        $activityResult->activityId = $row['quiz'];
        $activityResult->activityName = ucwords($row['activityname']);       
        $activityResult->studentId = ucwords($row['idnumber']);
        $activityResult->studentName = ucwords($row['firstname'] . ' ' . $row['lastname']);          
        $activityResult->finalResult = $row['grade'];
        $activityResult->timeModified = $row['timemodified'];

        $response['page'] = $page;
        $response['pages'] = $pages;
        $response['pageSize'] = $pageSize;
        $response['rows'] = $rows;
        $response['data'][$i] = $activityResult;

        $i++;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}
else {

    $errorResponse['page'] = $page;
    $errorResponse['pages'] = 0;
    $errorResponse['pageSize'] = $pageSize;
    $errorResponse['rows'] = 0;
    $errorResponse['data'] = [];

    echo json_encode($errorResponse, JSON_PRETTY_PRINT);
}

?>