<?php
include "./database/db.php";

$info = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buyer'])) {
        $buyer = $_POST['name'];
        $qty = $_POST['qty'];
        $price = $_POST['price'];
        $company = $_POST['company'];
        $type = $_POST['type'];
        $kg = $_POST['kg'];

        
        if (empty($buyer) || empty($company) || empty($qty) || empty($price) || empty($kg)) {
            $info = "<font color='red'>Please fill all required fields</font>";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $buyer)) {
            $info = "<font color='red'>Only letters and white space allowed in Buyer Name</font>";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $company)) {
            $info = "<font color='red'>Only letters and white space allowed in Company Name</font>";
        } elseif (!is_numeric($qty) || $qty <= 0) {
            $info = "<font color='red'>Quantity must be a positive number</font>";
        } elseif (!is_numeric($price) || $price <= 0) {
            $info = "<font color='red'>Price must be a positive number</font>";
        } elseif (!is_numeric($kg) || $kg <= 0) {
            $info = "<font color='red'>Weight must be a positive number</font>";
        } elseif ($type == "0") {
            $info = "<font color='red'>Please select buyer type</font>";
        } else {
            
            $date = date("Y-m-d");

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                
                $id = $_POST['id'];
                $query = "UPDATE buy SET name=?, qty=?, price=?, company=?, type=?, kg=? WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sissssi", $buyer, $qty, $price, $company, $type, $kg, $id);
            } else {
                
                $query = "INSERT INTO buy (name, qty, price, company, type, kg) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sisssi", $buyer, $qty, $price, $company, $type, $kg);
            }

            if ($stmt->execute()) {
                if (isset($_POST['id'])) {
                    echo "<script>alert('Record updated successfully'); window.location.href='Buyer.php';</script>";
                } else {
                    echo "<script>alert('Buyer added successfully'); window.location.href='Buyer.php';</script>";
                }
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    }
}


if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM buy WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $buyer = $row['name'];
        $qty = $row['qty'];
        $price = $row['price'];
        $company = $row['company'];
        $type = $row['type'];
        $kg = $row['kg'];
    } else {
        echo "<script>alert('Buyer with ID $id not found');</script>";
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
                        <h3 class="text-themecolor">Buyer Form</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Form Buyer</li>
                        </ol>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <a href="Buyer.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Review Table</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Buyer Form</h4>
                                <h6 class="card-subtitle">Add Buyer</h6>

                                <div class="container">
                                    <div id="info"><?php if (isset($info)) echo $info; ?></div>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
                                        <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter product name" id="name" name="name" required value="<?php if (isset($buyer)) echo $buyer; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="qty" class="form-label">Quantity</label>
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
                                            <label for="company" class="form-label">Company</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter company" id="company" name="company" required value="<?php if (isset($company)) echo $company; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kg" class="form-label">Weight (kg)</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter weight in kg" id="kg" name="kg" required value="<?php if (isset($kg)) echo $kg; ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Targo Type</label>
                                            <select class="form-control" id="type" name="type">
                                                <option value="0">Targo Type</option>
                                                <option value="shibis" <?php if(isset($type) && $type == "shibis") echo "selected"; ?>>Shibis</option>
                                                <option value="diyaarad" <?php if(isset($type) && $type == "diyaarad") echo "selected"; ?>>Diyaarad</option>
                                                <option value="gaari" <?php if(isset($type) && $type == "gaari") echo "selected"; ?>>Gaari</option>
                                            </select>
                                        </div>
                                        <div class="col text-right">
                                            <input type="submit" id="buyer" name="buyer" class="btn btn-success btn-lg btn-fw" value="<?php if (isset($id)) echo "Update"; else echo "Save"; ?>">
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
                    alert('Only letters and white space allowed in Buyer Name');
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>
