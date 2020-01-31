<!DOCTYPE html>

<html>

<head>
<link rel="stylesheet" href="phpstyles.css?v=<?php echo time(); ?>"> <!-- Prevent CSS caching -->
</head>
<body>
<?php include 'navbar.php'; // inclusion of navbar file as per specifications ?> 
<h1 class='cust'>Customers: <span class="header_det" style="font-weight:lighter;font-size:30px;"> Name | City | Phone no.</span></h1>
<h2>Customers</h2>
</body>
<?php
  echo "<table class='cust'>";
  $sql1 = "SELECT * FROM customers ORDER BY country";
  $stmt1 = get_sql($sql1); // call function to make sql sonnection, with sql query string supplied as parameter
  $banner_country = "";
  foreach($stmt1 as $customer) { // loop through object returned by sql query, echoing results in table form
     
     if($customer['country'] != $banner_country) { // logic to display each customer as grouped by country
      $i  = 1;
      echo "<tr class='banner'><td class='banner'>".$customer['country']."</td></tr>";
      $banner_country = $customer['country'];
     }
     echo "<tr><td class='cust'>".$i.". ".$customer['customerName']."</td>".
          "<td class='cust'>".$customer['city']."</td>".
          "<td class='cust'>".$customer['phone']."</td></tr>";
     $i++;
  }
  echo "</table>";
  include 'footer.php'; // inclusion of footer file as per specifications

  function get_sql($sql) { // function for making sql connection, with supplied query, and specified database
      $servername = "localhost";
      $username = "root";
      $password = "";
      try {
        $conn = new PDO("mysql:host=$servername;dbname=classicmodels", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $stmt = $conn->query($sql)->fetchALL();
      }
      catch(PDOException $e) { // error handling for failed (or faulty) sql connection
        echo "Connection failed: " . $e->getMessage();
      }
      $conn = null; // close sql connection
      return $stmt;
    }
?>
</html>
