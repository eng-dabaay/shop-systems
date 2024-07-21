<?php
include "./database/db.php";
session_start();

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT itemname, des, qty, price, file FROM additem WHERE itemname LIKE '%$search%'";
} else {
    $query = "SELECT itemname, des, qty, price, file FROM additem";
}

$result = mysqli_query($conn, $query);

if (isset($_POST['add_to_cart'])) {
    $itemname = $_POST['itemname'];
    $qty = $_POST['qty'];

    $query = "SELECT qty FROM additem WHERE itemname = '$itemname'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($qty > $row['qty']) {
        $error_message = "Requested quantity exceeds available stock.";
    } else {
        $item = [
            'itemname' => $_POST['itemname'],
            'des' => $_POST['des'],
            'qty' => $qty,
            'price' => $_POST['price'],
            'file' => $_POST['file']
        ];

        $item_exists = false;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        foreach ($_SESSION['cart'] as &$cart_item) {
            if ($cart_item['itemname'] === $item['itemname']) {
                $new_qty = $cart_item['qty'] + $item['qty'];
                if ($new_qty > $row['qty']) {
                    $error_message = "Requested quantity exceeds available stock.";
                } else {
                    $cart_item['qty'] = $new_qty;
                }
                $item_exists = true;
                break;
            }
        }

        if (!$item_exists) {
            $_SESSION['cart'][] = $item;
        }

        if (!isset($error_message)) {
            header("Location: orderList.php"); 
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "header.php"; ?>

  <body class="fix-header card-no-border fix-sidebar">

    <div id="main-wrapper">

      <?php include "topnavbar.php"; ?>

      <?php include "sidebar.php"; ?>

      <div class="page-wrapper">

        <div class="container-fluid">

          <div class="row">
            <!-- column -->
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Order Form</h4>
                  <h6 class="card-subtitle">Add Order <code>.Form</code></h6>

                  <?php if (isset($error_message)) { ?>
                      <div class="alert alert-danger">
                          <?php echo $error_message; ?>
                      </div>
                  <?php } ?>

                  
                  <form action="order.php" method="GET" class="mb-4">
                    <div class="form-group">
                      <input type="text" name="search" class="form-control" placeholder="Search by item name">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                  </form>

                 <div class="container">

                 <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?php echo $row['file']; ?>" class="card-img-top" alt="<?php echo $row['file']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['itemname']; ?></h5>
                                    <p class="card-text"><?php echo $row['des']; ?></p>
                                    <p class="card-text">Available Quantity: <?php echo $row['qty']; ?></p>
                                    <p class="card-text">Price: <?php echo $row['price']; ?></p>
                                    
                                    <form method="POST" action="order.php">
                                        <input type="hidden" name="itemname" value="<?php echo $row['itemname']; ?>">
                                        <input type="hidden" name="des" value="<?php echo $row['des']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                                        <input type="hidden" name="file" value="<?php echo $row['file']; ?>">
                                        <input type="number" name="qty" value="1" min="1" max="<?php echo $row['qty']; ?>" required>
                                        <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                </div>
              </div>
            </div>
          </div>

        </div>
       
      </div>
      
    </div>

  </body>
</html>
