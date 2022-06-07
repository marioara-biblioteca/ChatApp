<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
  header("location: login.php");
}
?>
<?php include_once "header.php"; ?>

<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php
          $all_docs = $conn->getDocuments('chatapp');

          foreach ($all_docs['rows'] as $row) { // o sa avem useri si mesaje
            if (!is_numeric($row['id'])) { //mesajele vor avea id-urile numere si userii au id-uri usernameul
              $user = $conn->getDocument('chatapp', $row['id']);
              if ($user['unique_id'] == $_SESSION['unique_id']) {
                $current = $user;
              }
            }
          }
          ?>
          <div class="details">
            <span><?php echo $current['fname'] . " " . $current['lname']; ?></span>
            <p><?php echo $current['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $current['unique_id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">

      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>

</html>
