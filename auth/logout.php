<?php
include "../includes/functions.php"; // Include functions File

session_start();
session_destroy();

redirect('login.php');
