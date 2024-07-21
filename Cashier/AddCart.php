<?php
include "./database/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order'])) {
    $name = $_POST['name'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $cid = $_POST['cid'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    
    $errors = [];
    if (empty($name) || empty($cid) || empty($qty) || empty($price)) {
        $errors[] = 'Please fill in all fields.';
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = 'Only letters and white space allowed in Item Name.';
    }
    if (!preg_match("/^\d+(\.\d{1,2})?$/", $qty)) {
        $errors[] = 'Only digits are allowed in Quantity.';
    }
    if (!preg_match("/^\d+(\.\d{1,2})?$/", $price)) {
        $errors[] = 'Only digits are allowed in Price.';
    }

    if (empty($errors)) {
        if ($id) {
            
            $stmt = $conn->prepare("UPDATE orders SET ordername = ?, qty = ?, cid = ?, price = ? WHERE orderid = ?");
            $stmt->bind_param("ssdii", $name, $qty, $cid, $price, $id);

            if ($stmt->execute()) {
                echo "<script>alert('Order updated successfully'); window.location.href='Order.php';</script>";
            } else {
                echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
            }
        } else {
            
            $stmt = $conn->prepare("INSERT INTO orders (ordername, qty, cid, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssdi", $name, $qty, $cid, $price);

            if ($stmt->execute()) {
                echo "<script>alert('Order added successfully'); window.location.href='Order.php';</script>";
            } else {
                echo "<script>alert('Error adding order: " . $stmt->error . "');</script>";
            }
        }
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE orderid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['ordername'];
        $qty = $row['qty'];
        $cid = $row['cid'];
        $price = $row['price'];
    } else {
        echo "Order with ID $id not found";
    }
    $stmt->close();
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
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Order Form</h4>
                  <h6 class="card-subtitle">Order <code>.List</code></h6>
                 <div class="container">
                 <div id="info"><?php if(isset($info)) echo $info; ?></div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : ''; ?>">
                        <div class="mb-3">
                            <label for="order" class="form-label">Item Name</label>
                            <div class="input-field">
                                <input type="text" class="form-control" placeholder="Enter item name" id="name" name="name" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Item Price</label>
                            <div class="input-field">
                                <input type="text" class="form-control" placeholder="Enter price" id="price" name="price" required value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="qty" class="form-label">Quantity</label>
                            <div class="input-field">
                                <input type="text" class="form-control" placeholder="Enter quantity" id="qty" name="qty" required value="<?php echo isset($qty) ? htmlspecialchars($qty) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cid" class="form-label">Category Name</label>
                            <select name="cid" id="cid" class="form-select" required>
                                <?php
                                    $categories = $conn->query("SELECT * FROM category");
                                    while($c = $categories->fetch_assoc()){
                                        echo "<option value='".$c['id']."'".(isset($cid) && $cid == $c['id'] ? " selected" : "").">".$c['category']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col text-right">
                        <input type="submit" id="order" name="order" class="btn btn-success btn-lg btn-fw" value="<?php echo isset($id) ? 'Edit' : 'Add' ?>">
                            <input type="reset" class="btn btn-danger btn-lg btn-fw" value="Clear">
                        </div>
                    </form>
                 </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
        function validateForm() {
            var name = document.getElementById('name').value;
            var qty = document.getElementById('qty').value;
            var price = document.getElementById('price').value;

            var lettersRegex = /^[a-zA-Z\s]+$/;
            var priceQtyRegex = /^\d+(\.\d{1,2})?$/;

            if (!lettersRegex.test(name)) {
                alert('Only letters and white space allowed in Item Name.');
                return false;
            }

            if (!priceQtyRegex.test(qty)) {
                alert('Only digits are allowed in Quantity.');
                return false;
            }

            if (!priceQtyRegex.test(price)) {
                alert('Only digits are allowed in Price.');
                return false;
            }

            return true;
        }
    </script>
  </body>
</html>
