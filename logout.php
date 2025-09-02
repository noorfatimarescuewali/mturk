<?php
require 'db.php';
session_destroy();
echo "<script>window.location.href='index.php';</script>";
