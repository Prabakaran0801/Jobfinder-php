<?php

$config = require basePath("config/db.php");
$db =  new Database($config);

$listings = $db->query("SELECT * FROM users ") ->fetchAll();

inspect($listings);

 loadView("home");