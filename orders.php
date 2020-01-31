<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="phpstyles.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include 'navbar.php'; // inclusion of navbar file as per specifications ?>
<!-- create hidden HTML form, to be submitted in the background every time an order no. is clicked -->
<form id="f0rm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"
           method="post">
    <input type="hidden" id="hidden_form" name="ord_num" value="unset">

 </form>


<?php
    echo "<h1 class='ord'>Orders</h1>";
    $sql1 = "SELECT * FROM orders WHERE status = 'In process'"; // store three separate sql queries
    $sql2 = "SELECT * FROM orders WHERE status = 'cancelled'";
    $sql3 = "SELECT * FROM orders ORDER BY orderDate DESC LIMIT 20";

    $servername = "localhost";
    $username = "root";
    $password = "";
    try { // make a single connection to specified database, and issue three different queries; store results in appropriate variables
        $conn = new PDO("mysql:host=$servername;dbname=classicmodels", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $stmt1 = $conn->query($sql1)->fetchALL();
        $stmt2 = $conn->query($sql2)->fetchALL();
        $stmt3 = $conn->query($sql3)->fetchALL();
    }
    catch(PDOException $e) { // error handling for failed (or faulty) sql connection
        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null; // terminate sql connection
    echo "<h2>In Process</h2><hr><br><table>";

    echo "<tr><th class='ord'>Order No.</th><th class='ord'>Order Date</th><th class='ord'>Order Status</th><tr>";
    foreach($stmt1 as $order1) { // loop through first object returned from sql, echoing results in table form
        echo "<tr><td class='ord'><a href='#' onclick='form_submit(".$order1['orderNumber'].")'>".$order1['orderNumber']."</a></td>".
             "<td class='ord'>".$order1['orderDate']."</td>".
             "<td class='ord'>".$order1['status']."</td></tr>";
     }
     echo "</table>";
     echo "<h2>Cancelled</h2><hr><table>";
     echo "<tr><th class='ord'>Order No.</th><th class='ord'>Order Date</th><th class='ord'>Order Status</th><tr>";
     foreach($stmt2 as $order2) { // loop through second object returned from sql, echoing results in table form
         echo "<tr><td class='ord'><a href='#' onclick='form_submit(".$order2['orderNumber'].")'>".$order2['orderNumber']."</a></td>".
              "<td class='ord'>".$order2['orderDate']."</td>".
              "<td class='ord'>".$order2['status']."</td></tr>";
      }
      echo "</table>";
      echo "<h2>20 most recent</h2><hr><table>";
      echo "<tr><th class='ord'>Order No.</th><th class='ord'>Order Date</th><th class='ord'>Order Status</th><tr>";
      foreach($stmt3 as $order3) { // loop through third object returned from sql, echoing results in table form
          echo "<tr><td class='ord'><a href='#' onclick='form_submit(".$order3['orderNumber'].")'>".$order3['orderNumber']."</a></td>".
               "<td class='ord'>".$order3['orderDate']."</td>".
               "<td class='ord'>".$order3['status']."</td></tr>";
       }
       echo "</table>";
       if(isset($_POST['ord_num'])) { // check if hidden form has been submitted
           if($_POST['ord_num'] == 'unset') // if no valid data submitted, then do nothing
               ;
           else { // otherwise process submitted data
                $ord_num = $_POST['ord_num'];
                echo "<script>document.getElementsByTagName('body')[0].innerHTML = '';</script>"; // clear the page
                include 'navbar.php';
                echo "<h1 class='ord'>Order No: ".$ord_num."</h1>";
                echo "<button onclick='go_home();'>Back to Overview</button><br><br>";
            }

                // make fourth sql query based on the value of the order no. clicked
                $sql4 = "SELECT * FROM orders o JOIN orderdetails od ON od.orderNumber = o.orderNumber JOIN products p ON od.productCode = p.productCode WHERE o.orderNumber = \"$ord_num\"";
                try { // make new sql connection
                    $conn = new PDO("mysql:host=$servername;dbname=classicmodels", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                    $stmt4 = $conn->query($sql4)->fetchALL();
                }
                catch(PDOException $e) { // error handling
                    echo "Connection failed: " . $e->getMessage();
                }
                $conn = null; // terminate sql connection
                echo "<table>";
                echo "<tr><th class='ord'>Product Code</th><th class='ord'>Product Line</th><th class='ord'>Product Name</th></tr>";
                $comment = "";
                foreach($stmt4 as $ord_det) { // loop through returned object, echoing results in table form
                    $comment = $ord_det['comments'];
                    echo "<tr><td class='ord'>".$ord_det['productCode']."</td>".
                         "<td class='ord'>".$ord_det['productLine']."</td>".
                         "<td class='ord'>".$ord_det['productName']."</td></tr>";

                }
                
                echo "</table><br><br>";

                echo "<i>Order Comment: ".$comment."</i>";
                echo "<br><br><br><br>";
       }
    include 'footer.php'; //inclusion of footer file, as per specifications
    
?>

</body>
<script>
    function form_submit(ord_no) { // js function to be called whenever a given order no. is clicked on; changes the value of hidden form and submits it
        document.getElementsByTagName("input")[0].value = ord_no;
        document.getElementById("f0rm").submit();
    }

    function go_home() {
        window.location.href = 'orders.php'; // js function to return to orders.php
  }
</script>
</html>


