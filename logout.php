<?php
require_once('inc/core.php');
session_destroy();
header("Location: login.php");
?>