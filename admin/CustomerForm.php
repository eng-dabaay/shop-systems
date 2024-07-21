<?php
include "./database/db.php";


if (isset($_POST['customer'])) {
    $customer = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $desc = $_POST['desc'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $fee = $_POST['fee'];
    $extraField = isset($_POST['extra_field']) ? $_POST['extra_field'] : '';


    if (empty($customer) || empty($phone) || empty($address) || empty($product) || empty($desc) || empty($qty) || empty($price)) {
        $info = "<font color='red'>Please fill all required fields</font>";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $customer)) {
        $info = "<font color='red'>Only letters and white space allowed in CustomerName</font>";
    } 
    elseif (!preg_match("/^\d+$/", $phone)) {
        $info = "<font color='red'>Only digits allowed in Phone Number</font>";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $address)) {
        $info = "<font color='red'>Only letters and white space allowed in Address</font>";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $product)) {
        $info = "<font color='red'>Only letters and white space allowed in Product Name</font>";
    } 
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $desc)) {
        $info = "<font color='red'>Only letters and white space allowed in Description</font>";
    } 
    elseif (!is_numeric($qty) || $qty <= 0) {
        $info = "<font color='red'>Quantity must be a positive number</font>";
    } 
    elseif (!is_numeric($price) || $price <= 0) {
        $info = "<font color='red'>Price must be a positive number</font>";
    } 
    elseif ($fee == "0") {
        $info = "<font color='red'>Please select customer fee</font>";
    }
    else {
        $date = date("Y-m-d");

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
            $query = "UPDATE customer SET name=?, phone=?, address=?, product=?, des=?, qty=?, price=?, fee=?, extra_field=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssssi", $customer, $phone, $address, $product, $desc, $qty, $price, $fee, $extraField, $id);
            if ($stmt->execute()) {
                echo "<script>alert('Record updated successfully'); window.location.href='Customer.php';</script>";
            } else {
                echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            $query = "INSERT INTO customer (name, phone, address, product, des, qty, price, fee, extra_field) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssss", $customer, $phone, $address, $product, $desc, $qty, $price, $fee, $extraField);
            if ($stmt->execute()) {
                echo "<script>alert('Customer added successfully'); window.location.href='Customer.php';</script>";
            } else {
                echo "<script>alert('Error adding customer: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM customer WHERE id = ?";
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
        $desc = $row['des'];
        $qty = $row['qty'];
        $price = $row['price'];
        $fee = $row['fee'];
        $extraField = $row['extra_field'];
    } else {
        echo "<script>alert('Customer with ID $id not found');</script>";
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
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h3 class="text-themecolor">Customer Form</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Form Customer</li>
                            </ol>
                        </div>
                        <div class="col-md-7 align-self-center">
                            <a href="Customer.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Review Table</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer Form</h4>
                                    <h6 class="card-subtitle">Add Customer</h6>

                                    <div class="container">
                                        <div id="info"><?php if (isset($info)) echo $info; ?></div>
                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                                            <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
                                            <div class="mb-3">
                                                <label for="customer" class="form-label">Customer Name</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter customer" id="name" name="name" required value="<?php if (isset($customer)) echo $customer; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
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
                                                    <input type="text" class="form-control" placeholder="Enter productname" id="product" name="product" required value="<?php if (isset($product)) echo $product; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter description" id="desc" name="desc" required value="<?php if (isset($desc)) echo $desc; ?>">
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
                                                <label>Customer Fee</label>
                                                <select class="form-control" id="fee" name="fee" onchange="toggleExtraField()">
                                                    <option value="0">Customer Fee</option>
                                                    <option value="bixid" <?php if(isset($fee) && $fee == "bixid") echo "selected"; ?>>Bixid</option>
                                                    <option value="deen" <?php if(isset($fee) && $fee == "deen") echo "selected"; ?>>Deen</option>
                                                    <option value="qeeb" <?php if(isset($fee) && $fee == "qeeb") echo "selected"; ?>>Qeeb Bixid</option>
                                                </select>
                                            </div>
                                            <div class="mb-3" id="extraFieldContainer" style="display: none;">
                                                <label for="extra_field" class="form-label">Extra Field for Qeeb Bixid</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter extra field" id="extra_field" name="extra_field" value="<?php if (isset($extraField)) echo $extraField; ?>">
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <input type="submit" id="customer" name="customer" class="btn btn-success btn-lg btn-fw" value="<?php if (isset($id)) echo "Update"; else echo "Save"; ?>">
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
                var customer = document.getElementById('name').value;
                var regex = /^[a-zA-Z\s]+$/;
                if (!regex.test(customer)) {
                    alert('Only letters and white space allowed in Customer Name');
                    return false;
                }
                return true;
            }

            function toggleExtraField() {
                var fee = document.getElementById('fee').value;
                var extraFieldContainer = document.getElementById('extraFieldContainer');
                if (fee === 'qeeb') {
                    extraFieldContainer.style.display = 'block';
                } else {
                    extraFieldContainer.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleExtraField();
            });
        </script>
    </body>
</html>
