 <?php
    require_once "./db.php";

    session_start();
    header("Content-Type: application/json");

    $user = $_SESSION["user"];
    $important = $db->prepare("select note.title, note.status, list.name from note inner join list on list.id = note.listID where note.important = 1 and note.status = 0 and note.userID = ?");
    $important->execute([$user["id"]]);

    $data;

    $data = array();

    foreach ($important as $task) {

        $listName = ($task["name"]);

        $data[$task["title"]] = $listName;
    }

    echo json_encode($data);
