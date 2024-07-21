<?php

include "./database/db.php";

?>


<!DOCTYPE html>
<html lang="en">
    <?php include "header.php"; ?>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/count.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

  <body class="fix-header card-no-border fix-sidebar">

    <div id="main-wrapper">

      <?php include "topnavbar.php"; ?>

      <?php include "sidebar.php"; ?>

      <div class="page-wrapper">

        <div class="container-fluid">

          <div class="row page-titles">
            <div class="col-md-5 align-self-center">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active">Table Dashboard</li>
              </ol>
            </div>
          </div>

          <div class="row">
            <!-- column -->
            <div class="col-12">
              <div class="card">
                <div class="card-body">

                    <div class="container">
                        <div class="row">
                        
                        <div class="col-md-3">
                        <div class="card-counter primary">
                            <i class="fa fa-list"></i>
                            <span class="count-numbers">
                            <?php
                                $counter = "SELECT * FROM orders";
                                $counter_run = mysqli_query($conn, $counter);

                                if($counter_total = mysqli_num_rows($counter_run)){
                                    echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                }else{
                                    echo '<h4 class="mb-0"> No Data </h4>';
                                }
                                ?>
                            </span>
                            <span class="count-name">Order List</span>
                        </div>
                        </div>

                        <div class="col-md-3">
                        <div class="card-counter success">
                            <i class="fa fa-list"></i>
                            <span class="count-numbers">
                            <?php
                                $counter = "SELECT * FROM additem";
                                $counter_run = mysqli_query($conn, $counter);

                                if($counter_total = mysqli_num_rows($counter_run)){
                                    echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                }else{
                                    echo '<h4 class="mb-0"> No Data </h4>';
                                }
                                ?>
                            </span>
                            <span class="count-name">AddItems</span>
                        </div>
                        </div>

                        <div class="col-md-3">
                        <div class="card-counter info">
                            <i class="fa fa-list"></i>
                            <span class="count-numbers">
                            <?php
                                $counter = "SELECT * FROM additem";
                                $counter_run = mysqli_query($conn, $counter);

                                if($counter_total = mysqli_num_rows($counter_run)){
                                    echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                }else{
                                    echo '<h4 class="mb-0"> No Data </h4>';
                                }
                                ?>
                            </span>
                            <span class="count-name">Buyer Product</span>
                        </div>
                        </div>

                        <div class="col-md-3">
                        <div class="card-counter dart">
                            <i class="fa fa-users"></i>
                            <span class="count-numbers">
                            <?php
                                $counter = "SELECT * FROM users";
                                $counter_run = mysqli_query($conn, $counter);

                                if($counter_total = mysqli_num_rows($counter_run)){
                                    echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                }else{
                                    echo '<h4 class="mb-0"> No Data </h4>';
                                }
                                ?>
                            </span>
                            <span class="count-name">Users</span>
                        </div><br><br>
                        </div>

                        <div class="col-md-3">
                        <div class="card-counter danger">
                            <i class="fa fa-user"></i>
                            <span class="count-numbers">
                            <?php
                                $counter = "SELECT * FROM customer";
                                $counter_run = mysqli_query($conn, $counter);

                                if($counter_total = mysqli_num_rows($counter_run)){
                                    echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                }else{
                                    echo '<h4 class="mb-0"> No Data </h4>';
                                }
                                ?>
                            </span>
                            <span class="count-name">Customer</span>
                        </div><br><br>
                        </div>

                        <div class="col-md-3">
                            <div class="card-counter info">
                                <i class="fa fa-truck"></i>
                                <span class="count-numbers">
                                <?php
                                    $counter = "SELECT * FROM delivery";
                                    $counter_run = mysqli_query($conn, $counter);

                                    if($counter_total = mysqli_num_rows($counter_run)){
                                        echo '<h1 class="mb-0"> '.$counter_total.'</h1>';
                                    }else{
                                        echo '<h4 class="mb-0"> No Data </h4>';
                                    }
                                    ?>
                                </span>
                                <span class="count-name">Delivery</span>
                            </div><br><br>
                        </div>

                        <div class="col-md-3">
                            <div class="card-counter dart">
                                <i class="fa fa-money"></i>
                                <span class="count-numbers">
                                <?php
                                    
                                    $sql = "SELECT COUNT(*) AS customer_count FROM customer WHERE name = 'Deen'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $customerCount = $row['customer_count'];
                                        echo '<h1 class="mb-0">' . $customerCount . '</h1>';
                                    } else {
                                        echo '<h4 class="mb-0">No Data</h4>';
                                    }
                                ?>
                                </span>
                                <span class="count-name">Customer Deen</span>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card-counter primary">
                                <i class="fa fa-money"></i>
                                <span class="count-numbers">
                                <?php
                                    $sql = "SELECT SUM(price * qty) AS total_sales FROM customer";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $totalSales = $row['total_sales'];
                                        echo '<h1 class="mb-0">$' . number_format($totalSales, 2) . '</h1>';
                                    } else {
                                        echo '<h4 class="mb-0">No Data</h4>';
                                    }
                                ?>
                                </span>
                                <span class="count-name">Customer Report</span>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="card-counter danger">
                                <i class="fa fa-money"></i>
                                <span class="count-numbers">
                                <?php
                                    $sql = "SELECT SUM(price * qty) AS total_sales FROM orders";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $totalSales = $row['total_sales'];
                                        echo '<h1 class="mb-0">$' . number_format($totalSales, 2) . '</h1>';
                                    } else {
                                        echo '<h4 class="mb-0">No Data</h4>';
                                    }
                                ?>
                                </span>
                                <span class="count-name">Sales Report</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card-counter success">
                                <i class="fa fa-money"></i>
                                <span class="count-numbers">
                                <?php
                                    
                                    $sql = "SELECT SUM(extra_field) AS total_extra_field FROM customer";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $totalExtraField = $row['total_extra_field'];
                                        echo '<h1 class="mb-0">$' . number_format($totalExtraField, 2) . '</h1>';
                                    } else {
                                        echo '<h4 class="mb-0">No Data</h4>';
                                    }
                                ?>
                                </span>
                                <span class="count-name">Customer Extra Field</span>
                            </div>
                        </div>

                    </div>
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