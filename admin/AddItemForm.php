<?php
include "./database/db.php";

$info = '';


function sanitizeInput($input) {
    global $conn;
    return htmlspecialchars(trim(mysqli_real_escape_string($conn, $input)));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['additem'])) {
        
        if (!isset($_POST["id"])) {
            $name = sanitizeInput($_POST['name']);
            $des = sanitizeInput($_POST['des']);
            $qty = sanitizeInput($_POST['qty']);
            $price = sanitizeInput($_POST['price']);

            
            $file_name = $_FILES['image']['name'];
            $temp = $_FILES['image']['tmp_name'];
            $folder = 'images/' . $file_name;

            
            if ($name == "" || $des == "" || $qty == "" || $price == "" || $file_name == "") {
                $info = "<font color='red'>Please fill all required fields</font>";
            } else {
                
                $date = date("Y-m-d");
                $query = "INSERT INTO additem(`itemname`, `des`, `qty`, `price`, `file`, `date`) VALUES ('$name', '$des', '$qty', '$price', '$file_name', '$date')";

                if ($conn->query($query) && move_uploaded_file($temp, $folder)) {
                    $info = "<script>alert('Item added successfully')</script>";
                    header("Location: AddItem.php");
                    exit();
                } else {
                    $info = "<font color='red'>Item registration failed: " . $conn->error . "</font>";
                }
            }
        } else {
            
            $id = $_POST["id"];
            $name = sanitizeInput($_POST['name']);
            $des = sanitizeInput($_POST['des']);
            $qty = sanitizeInput($_POST['qty']);
            $price = sanitizeInput($_POST['price']);

            
            if ($name == "" || $des == "" || $qty == "" || $price == "") {
                $info = "<font color='red'>Please fill all required fields</font>";
            } else {
                
                $query = "UPDATE additem SET `itemname` = '$name', `des` = '$des', `qty` = '$qty', `price` = '$price' WHERE id = $id";

                if ($conn->query($query)) {
                    $info = "<script>alert('Item updated successfully')</script>";
                    header("Location: AddItem.php");
                    exit();
                } else {
                    $info = "<font color='red'>Item update failed: " . $conn->error . "</font>";
                }
            }
        }
    }
}


if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM additem WHERE id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row["itemname"]);
        $des = htmlspecialchars($row["des"]);
        $qty = htmlspecialchars($row["qty"]);
        $price = htmlspecialchars($row["price"]);
        $file_name = htmlspecialchars($row["file"]);
    } else {
        $info = "<font color='red'>Item ID does not exist</font>";
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
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">AddItem Form</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Form AddItem</li>
                        </ol>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <a href="AddItem.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Review Table</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">AddItem Form</h4>
                                <h6 class="card-subtitle">Add or Update Item</h6>

                                <div class="container">
                                    <div id="info"><?php if(isset($info)) echo $info; ?></div>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                                        <div class="mb-3">
                                            <label for="item" class="form-label">ItemName</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter itemname" id="name" name="name" required value="<?php if(isset($name)) echo htmlspecialchars($name); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="desc" class="form-label">Description</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter description" id="des" name="des" required value="<?php if(isset($des)) echo htmlspecialchars($des); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="qty" class="form-label">Quantity</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter quantity" id="qty" name="qty" required value="<?php if(isset($qty)) echo htmlspecialchars($qty); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <div class="input-field">
                                                <input type="text" class="form-control" placeholder="Enter price" id="price" name="price" required value="<?php if(isset($price)) echo htmlspecialchars($price); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image">Choose Image:</label>
                                            <div class="form-control-file">
                                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" <?php if(isset($file_name)) echo 'enabled'; ?>>
                                                <?php if(isset($file_name)) echo $file_name; ?>
                                            </div>
                                        </div>
                                        <div class="col text-right">
                                            <input type="submit" id="additem" name="additem" class="btn btn-success btn-lg btn-fw" value="<?php echo isset($_GET["id"]) ? "Update" : "Save"; ?>">
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
</body>

</html>
