<?php

session_start();

$user = $_SESSION["user"];

extract($_POST);

require_once "./db.php";
if (isset($listName)) {

  if (strlen(trim($_POST["listName"])) === 0)
    $errMsg = "FAILED TO INSERT";

  else {

    try {
      $sql = "insert into list (name, userID) values (?, ?)";
      $rs = $db->prepare($sql);
      $rs->execute([$listName, $user["id"]]);

      $sql = "select max(id) from list";
      $list = $db->prepare($sql);
      $list-> execute();

      foreach($list as $l)
      {
        $id = $l["max(id)"];
        var_dump($id);
        header("Location: main.php?listID=$id");}

      
    } catch (PDOException $ex) {
      $errMsg = "Insert Fail";
    }
  }
}


if (isset($_GET)) {



  extract($_GET);

  if (isset($addTask)) {
    if (strlen(trim($addTask)) === 0) {
      $errMsg = "FAILED TO INSERT";
    } else {

      try {
        $sql = "insert into note (userID, title, listID) values (?, ?,?)";
        $rs = $db->prepare($sql);
        $rs->execute([$user["id"], $addTask, $listID]);
        header("Location: main.php?listID=$listID");
      } catch (PDOException $ex) {
        $errMsg = "Insert Fail";
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Title of the document</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js">



  </script>
  <style>
    .container {
      margin-top: 50px;
    }

    .circle {
      vertical-align: middle;
    }

    #leftPanel {
      height: 100vh;
      background-color: #212121;

    }

    #rightPanel {

      background-color: #f57c00;
      height: 100vh;
    }

    .modal {
      width: 40%;
    }

    .strike {
      text-decoration: line-through;
    }

  </style>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col s1">

      </div>
      <div id="leftPanel" class="col s3">
        <ul class="collection">
          <li class="collection-item avatar">
            <?php
            $profile = $user["profile"] ?? "avatar.png";
            echo "<img src='images/$profile' width='40' class='circle' > ";
            echo filter_var($user["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ;
            echo "<br>";
            echo filter_var($user["email"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            ?>
            <a href="logout.php" class="secondary-content"><i class="material-icons orange-text">exit_to_app</i></a>
          </li>
        </ul>


        <div class="divider"></div>

        <div class="collection">
          <a  onclick="openImportant()" class="important-item collection-item orange-text" style="font-weight: bold;"><i class="material-icons orange-text secondary-content">star</i>IMPORTANT</a>
          <?php
          require_once "./db.php";

          $rs = $db->prepare("select * from list where userID = ?");
          $rs->execute([$user["id"]]);

          ?>

          <?php foreach ($rs as $list) : ?>
            <a href="?listID=<?= $list["id"] ?>" class="collection-item black-text
                
                <?php
                if (isset($_GET["listID"])) {
                  if ($list["id"] == $_GET["listID"]) {
                    echo "active";
                  }
                }
                ?>
               
                "> <i class="material-icons black-text secondary-content">list</i> <span id="badge<?= $list["id"] ?>" class="badge"></span> <?= filter_var($list["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)  ?></a>
          <?php endforeach ?>

                
          <a  class="collection-item modal-trigger orange-text" data-target="modal1" style="font-weight: bold;"><i class="material-icons orange-text secondary-content">add</i>New List</a>
        </div>

      </div>

      <div id="rightPanel" class="rightPanel col s7"" height=" 100px">
      <h3 class= 'list-title white-text'>

        <?php
        require_once "./db.php";

        if (isset($listID)) {
          $tasks = $db->prepare("select * from note where listID = ?");
          $tasks->execute([$listID]);

          $listName = $db->prepare("select name from list where id = $listID");
          $listName->execute();

          foreach($listName as $l)
          {
            $name = $l["name"];
            echo "$name";
          }

          
        
        }

        ?>
 </h3><br>

        <?php if (isset($tasks)) foreach ($tasks as $t) : ?>

          
          <div class="default-loader card-panel" id="loader<?= $t["id"] ?>">
            <p>
              <label>
                <input id="<?= $t["id"] ?>" type="checkbox" onclick=" updateCounts()" onchange="statusUpdate(<?= $t["id"] ?>)" <?php
                                                                                                                                if ($t["status"] == 1)
                                                                                                                                  echo "checked";
                                                                                                                                ?> />
                <span id="span<?= $t["id"] ?>" <?php

                                                if ($t["status"] == 1)
                                                  echo 'style="text-decoration: line-through;"';
                                                ?>><?= filter_var($t["title"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)  ?></span>
                <a href="<?= $t["id"] ?>" class="bms-delete secondary-content" id="deleteButton" onclick="updateCounts();"><i class="material-icons orange-text">delete</i></a>

                <a href="" class="favorite secondary-content" onClick="setImportant(<?= $t["id"] ?>,<?= $t["important"] ?>);"><i class="material-icons orange-text" id="favorite<?= $t["id"] ?>">

                    <?php
                    if ($t["important"] == 0)
                      echo "star_border";
                    else
                      echo "star";
                    ?>


                  </i></a>


              </label>
            </p>
          </div>

        <?php endforeach ?>


        <form action="" method="POST">
          <div class="default-loader card-panel">
            <div class="input-field">
              <i class="material-icons prefix orange-text active">add</i>
              <input name="addTask" id="addTask" type="text" class="validate active">
              <label for="addTask" class="active">Enter a task</label>
            </div>
          </div>
        </form>

      </div>
    </div>


    <div id="modal1" class="modal container ">
      <form action="" method="POST">
        <div class="modal-content">
          <div class="input-field col s12">
            <i class="material-icons prefix orange-text">add</i>
            <input name="listName" id="listName" type="text" class="validate">
            <label for="listName">New List</label>
          </div>
      </form>
    </div>
  </div>


  </div>
  <?php

  if (isset($errMsg)) {
    echo "<script> M.toast({html: '$errMsg', classes: 'red white-text'}) ; </script>";
  }

  ?>


  <script>
    <?php

    if ($_SERVER["PHP_SELF"] ==  $_SERVER['REQUEST_URI'])
      echo "openImportant();";

    ?>


   
    $(document).ready(function(){
    $('.modal').modal({
        onOpenEnd: function() {
            $('#listName').focus();

          
        }
    });
});

    $(function() {
      // page is loaded

      $(".bms-delete").click(function(e) {
        e.preventDefault();
        // alert("Delete Clicked") ;
        let id = $(this).attr("href");

        loaderName = "#loader" + id;

        //alert( id + " clicked");
        $(loaderName).toggleClass("hide");

        $.get("deleteTask.php", {
          "id": id
        }); // show loader.
        "json"
      });
    });


    $("span").click(function() {
      $(this).toggleClass("strike");

    });

    $("#addTask").focus();

    $("#listName").focus();

    function setImportant(id, value) {


      $.get("importantTask.php", {
        "id": id
      });
      "json"



      dummy = "#favorite" + id;
      if (value == 0) {
        $(dummy).text("star");

      }
      if (value == 1) {
        $(dummy).text("star_border");
      }


    }

    function statusUpdate(id) {

      $.get("taskStatus.php", {
        "id": id
      });
      "json"
    }


    function openImportant() {

      $('.default-loader').hide();
      $('.active').removeClass('active');
      $('.important-item').addClass('active');
      $('.list-title').html('Important');


      $.get("getImportantList.php", function(data) {


        size = Object.keys(data).length;

        for (const items in data) {


          $(".rightPanel").append("<div class='card-panel'>  <label> <i class='material-icons circle' style='vertical-align: middle;'>star_border</i> <span> </span> " + items + "  <span  style='font-weight: bold; font-style: italic;' ><br>{ " + data[items] + "}</span>  </label> </div>");

        }

      })

    }


    function updateCounts() {


      $.get("countTask.php", function(data) {

        size = Object.keys(data).length;


        for (const items in data) {
          dummy = "#badge" + items;
          $(dummy).html(data[items]);
        }
      })


    };

    function firstTime() {

      $('.important-item').addClass('active');
      $('.list-title').html('Important');
    }


    setInterval(updateCounts, 100);
  </script>

</body>

</html>