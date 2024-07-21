<?php
include "./database/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delivery'])) {
    $customer = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $pay = $_POST['pay'];
    $type = $_POST['type'];

    // Validation
    if (empty($customer) || empty($phone) || empty($address) || empty($product) || empty($qty) || empty($price)) {
        $info = "<font color='red'>Please fill all required fields</font>";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $customer)) {
        $info = "<font color='red'>Only letters and white space allowed in Customer Name</font>";
    } elseif (!preg_match("/^\d+$/", $phone)) {
        $info = "<font color='red'>Only digits allowed in Phone Number</font>";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $address)) {
        $info = "<font color='red'>Only letters and white space allowed in Address</font>";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $product)) {
        $info = "<font color='red'>Only letters and white space allowed in Product Name</font>";
    } elseif (!is_numeric($qty) || $qty <= 0) {
        $info = "<font color='red'>Quantity must be a positive number</font>";
    } elseif (!is_numeric($price) || $price <= 0) {
        $info = "<font color='red'>Price must be a positive number</font>";
    } elseif ($pay == "0") {
        $info = "<font color='red'>Please select a payment method</font>";
    } elseif ($type == "0") {
        $info = "<font color='red'>Please select a delivery type</font>";
    } else {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Update existing record
            $id = $_POST['id'];
            $query = "UPDATE delivery SET name=?, phone=?, address=?, product=?, qty=?, price=?, method=?, type=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssssi", $customer, $phone, $address, $product, $qty, $price, $pay, $type, $id);
            if ($stmt->execute()) {
                echo "<script>alert('Record updated successfully'); window.location.href='Delivery.php';</script>";
            } else {
                $info = "<font color='red'>Error updating record: " . $stmt->error . "</font>";
            }
            $stmt->close();
        } else {
            // Insert new record
            $query = "INSERT INTO delivery (name, phone, address, product, qty, price, method, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssss", $customer, $phone, $address, $product, $qty, $price, $pay, $type);
            if ($stmt->execute()) {
                echo "<script>alert('Delivery added successfully'); window.location.href='Delivery.php';</script>";
            } else {
                $info = "<font color='red'>Error adding delivery: " . $stmt->error . "</font>";
            }
            $stmt->close();
        }
    }
}

// Fetch existing data for editing
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM delivery WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer = $row['name'];
        $phone = $row['phone'];
        $address = $row['address'];
        $product = $row['product'];
        $qty = $row['qty'];
        $price = $row['price'];
        $pay = $row['method'];
        $type = $row['type'];
    } else {
        echo "<script>alert('Delivery with ID $id not found');</script>";
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
                                <h4 class="card-title">Delivery Form</h4>
                                <h6 class="card-subtitle">Add Delivery</h6>

                                <div class="container">
                                    <div id="info"><?php if (isset($info)) echo $info; ?></div>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                                        <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="customer" class="form-label">Customer Name</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter customer" id="name" name="name" required value="<?php if (isset($customer)) echo $customer; ?>">
                                                </div>
                                            </div>
                                            <label for="phone" class="form-label">Phone No</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter phone" id="phone" name="phone" required value="<?php if (isset($phone)) echo $phone; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter address" id="address" name="address" required value="<?php if (isset($address)) echo $address; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="product" class="form-label">Product Name</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter product name" id="product" name="product" required value="<?php if (isset($product)) echo $product; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter quantity" id="qty" name="qty" required value="<?php if (isset($qty)) echo $qty; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter price" id="price" name="price" required value="<?php if (isset($price)) echo $price; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label>Method Payment</label>
                                            <select class="form-control" id="pay" name="pay">
                                                <option value="0">Method Payment</option>
                                                <option value="evcplus" <?php if(isset($pay) && $pay == "evcplus") echo "selected"; ?>>EVC-Plus</option>
                                                <option value="edahap" <?php if(isset($pay) && $pay == "edahap") echo "selected"; ?>>Edahap</option>
                                                <option value="mastercard" <?php if(isset($pay) && $pay == "mastercard") echo "selected"; ?>>Master-Card</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Delivery Type</label>
                                            <select class="form-control" id="type" name="type">
                                                <option value="0">Delivery Type</option>
                                                <option value="vekon" <?php if(isset($type) && $type == "vekon") echo "selected"; ?>>Vekon</option>
                                                <option value="bajaj" <?php if(isset($type) && $type == "bajaj") echo "selected"; ?>>Bajaaj</option>
                                                <option value="gaari" <?php if(isset($type) && $type == "gaari") echo "selected"; ?>>Gaari</option>
                                                <option value="diyarad" <?php if(isset($type) && $type == "diyarad") echo "selected"; ?>>Diyaarad</option>
                                            </select>
                                        </div>
                                        <div class="col text-right">
                                            <input type="submit" id="delivery" name="delivery" class="btn btn-success btn-lg btn-fw" value="<?php if (isset($id)) echo "Update"; else echo "Save"; ?>">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function validateForm() {
                var customer = document.getElementById('name').value;
                var regex = /^[a-zA-Z\s]+$/;
                if (!regex.test(customer)) {
                    alert('Only letters and white space allowed in Customer Name');
                    return false;
                }
                return true;
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleExtraField();
            });
        </script>
    </body>
</html>
