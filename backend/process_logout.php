<?php
        
session_start();
session_unset(); 
session_destroy(); 

header("Location: ../index.html?status=success&message=" . urlencode("Logged out successfully."));
exit();
?>