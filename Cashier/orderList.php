<?php
include "./database/db.php";
session_start();

if (isset($_POST['update_qty'])) {
    $index = $_POST['index'];
    $new_qty = $_POST['new_qty'];
    $itemname = $_SESSION['cart'][$index]['itemname'];

    $query = "SELECT qty FROM additem WHERE itemname = '$itemname'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($new_qty > $row['qty']) {
        $error_message = "Requested quantity exceeds available stock.";
    } else {
        $_SESSION['cart'][$index]['qty'] = $new_qty;
        header("Location: orderList.php"); 
        exit();
    }
}

if (isset($_POST['delete_item'])) {
    $index = $_POST['index'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: orderList.php"); 
    exit();
}

if (isset($_POST['save_cart'])) {
    foreach ($_SESSION['cart'] as $cart_item) {
        $query = "INSERT INTO orders (itemname, des, qty, price, file) VALUES ('{$cart_item['itemname']}', '{$cart_item['des']}', {$cart_item['qty']}, {$cart_item['price']}, '{$cart_item['file']}')";
        mysqli_query($conn, $query);
    }

    unset($_SESSION['cart']);
    header("Location: order.php"); 
    exit();
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

          <div class="row page-titles">
            <div class="col-md-5 align-self-center">
              <h3 class="text-themecolor">Cart Items</h3>
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active">Cart Items</li>
              </ol>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cart Items</h4>
                  <h5 class="card-subtitle">Company Name: Iqlaas Collection</h5>

                  <?php if (isset($error_message)) { ?>
                      <div class="alert alert-danger">
                          <?php echo $error_message; ?>
                      </div>
                  <?php } ?>

                  <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>Item Name</th>
                                  <th>Description</th>
                                  <th>Quantity</th>
                                  <th>Price</th>
                                  <th>Total</th>
                                  <th>Image</th>
                                  <th>Update Quantity</th>
                                  <th>Action</th>
                                  <th>Print</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php 
                              $total_qty = 0;
                              $total_price = 0;
                              $grand_total = 0;
                              foreach ($_SESSION['cart'] as $index => $cart_item) { 
                                  $query = "SELECT qty FROM additem WHERE itemname = '{$cart_item['itemname']}'";
                                  $result = mysqli_query($conn, $query);
                                  $row = mysqli_fetch_assoc($result);
                                  $available_stock = $row['qty'];
                                  $subtotal = $cart_item['qty'] * $cart_item['price'];
                                  $grand_total += $subtotal;
                                  $total_qty += $cart_item['qty'];
                                  $total_price += $cart_item['price'];
                              ?>
                                  <tr>
                                      <td><?php echo $cart_item['itemname']; ?></td>
                                      <td><?php echo $cart_item['des']; ?></td>
                                      <td><?php echo $cart_item['qty']; ?></td>
                                      <td><?php echo $cart_item['price']; ?></td>
                                      <td><?php echo $subtotal; ?></td>
                                      <td><img src="<?php echo $cart_item['file']; ?>" width="50" height="50" alt="<?php echo $cart_item['file']; ?>"></td>
                                      <td>
                                          <form method="POST" action="orderList.php">
                                              <input type="hidden" name="index" value="<?php echo $index; ?>">
                                              <input type="number" name="new_qty" value="<?php echo $cart_item['qty']; ?>" min="1" max="<?php echo $available_stock; ?>" required>
                                              <button type="submit" name="update_qty" class="btn btn-secondary">Update</button>
                                          </form>
                                      </td>
                                      <td>
                                          <form method="POST" action="orderList.php" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                              <input type="hidden" name="index" value="<?php echo $index; ?>">
                                              <button type="submit" name="delete_item" class="btn btn-danger">Delete</button>
                                          </form>
                                      </td>
                                      <td>
                                          <button class="btn btn-info" onclick="printItem('<?php echo $cart_item['itemname']; ?>', '<?php echo $cart_item['des']; ?>', '<?php echo $cart_item['qty']; ?>', '<?php echo $cart_item['price']; ?>', '<?php echo $subtotal; ?>')">Print</button>
                                      </td>
                                  </tr>
                              <?php } ?>
                          </tbody>
                          <tfoot>
                              <tr>
                                  <th colspan="4">Grand Total</th>
                                  <th><?php echo $grand_total; ?></th>
                                  <th colspan="4"></th>
                              </tr>
                              <tr>
                                  <th colspan="2">Totals</th>
                                  <td><?php echo $total_qty; ?></td>
                                  <td><?php echo $total_price; ?></td>
                                  <th colspan="4"></th>
                              </tr>
                          </tfoot>
                      </table>
                      <button class="btn btn-danger" onclick="printAllItems()">Printered</button><br>
                  <?php } else { ?>
                      <p>No items in the cart.</p>
                  <?php } ?><br>
                  
                  <form id="saveCartForm" method="POST" action="orderList.php" onsubmit="return confirm('Are you sure you want to save the cart?');">
                      <button type="submit" name="save_cart" class="btn btn-primary" id="saveCartButton" <?php echo (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? '' : 'disabled'; ?>>Save Cart</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
       
      </div>
      
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cartCount = <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>;
            var saveCartButton = document.getElementById('saveCartButton');
            if (cartCount === 0) {
                saveCartButton.disabled = true;
            }
        });

        function printItem(itemname, des, qty, price, subtotal) {
            var printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Print Item</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Item Details</h1>');
            printWindow.document.write('<p><strong>Company Name:</strong> XYZ Corporation</p>');
            printWindow.document.write('<p><strong>Item Name:</strong> ' + itemname + '</p>');
            printWindow.document.write('<p><strong>Description:</strong> ' + des + '</p>');
            printWindow.document.write('<p><strong>Quantity:</strong> ' + qty + '</p>');
            printWindow.document.write('<p><strong>Price:</strong> ' + price + '</p>');
            printWindow.document.write('<p><strong>Subtotal:</strong> ' + subtotal + '</p>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function printAllItems() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print All Items</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Cart Items</h1>');
            printWindow.document.write('<p><strong>Company Name:</strong> Iqlaas Collection</p>');
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
                var cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
                var grandTotal = <?php echo $grand_total; ?>;
                var totalQty = <?php echo $total_qty; ?>;
                var totalPrice = <?php echo $total_price; ?>;
                printWindow.document.write('<table border="1" width="100%">');
                printWindow.document.write('<thead><tr><th>Item Name</th><th>Description</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>');
                cartItems.forEach(function(item) {
                    var subtotal = item.qty * item.price;
                    printWindow.document.write('<tr><td>' + item.itemname + '</td><td>' + item.des + '</td><td>' + item.qty + '</td><td>' + item.price + '</td><td>' + subtotal + '</td></tr>');
                });
                printWindow.document.write('</tbody>');
                printWindow.document.write('<tfoot><tr><th colspan="4">Grand Total</th><th>' + grandTotal + '</th></tr>');
                printWindow.document.write('<tr><th colspan="2">Totals</th><td>' + totalQty + '</td><td>' + totalPrice + '</td></tr></tfoot>');
                printWindow.document.write('</table>');
            <?php } else { ?>
                printWindow.document.write('<p>No items in the cart.</p>');
            <?php } ?>
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

  </body>
</html>
