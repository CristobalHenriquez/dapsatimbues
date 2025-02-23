<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//INCLUYO HEAD
include 'includes/head.php';

?>

<body class="index-page login-page">

  <?php
  //INCLUYO NAV-DAPSA
  include 'includes/nav-dapsa.php';
  ?>

  <main class="main" style="font-family: --nav-font;">

    <?php
    //INCLUYO LOGIN
    include 'templates/login-main.php';
    ?>
  </main>

  <?php
  //INCLUYO FOOTER
  include 'includes/footer.php';

  ?>

</body>

</html>