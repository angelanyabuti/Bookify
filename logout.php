<?php
include 'config.php'; //database connection

session_start(); //start session
session_unset(); //clear all data stored in the session
session_destroy(); //end the session

//redirects to login page
header('location:login.php');
?>