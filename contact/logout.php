<?php
session_start();
session_unset();
session_destroy();
if(isset($_SESSION['user_name'])){
    unset($_SESSION['user_name']);
}
header ("location:../main/index.php");

?>