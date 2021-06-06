<?php

setcookie("PHPSESSID", "", 1,"/"); //logically invalidate the session id.
session_destroy(); 

header("Location: index.php");