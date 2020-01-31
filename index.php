<!DOCTYPE html>

<!-- Comment -->
<html>

<head>
<link rel="stylesheet" href="phpstyles.css?v=<?php echo time(); ?>">
</head>
<body>
<?php include 'navbar.php'; //inclusion of navbar file as per specifications ?>
<h1>Product-Lines</h1>
  <p><span class="refine">Refine your search by product type</span>
  <!-- Creation of HTML form for submission of data to be processes by PHP code -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post"> 
      <select id="f01" name="formProd">
        <option value="redundant">Select...</option>
        <option name="Classic Cars" value="Classic Cars">Classic Cars</option>
        <option name="Motorcycles" value="Motorcycles">Motorcycles</option>
        <option name="Trucks and Buses" value="Trucks and Buses">Trucks and Buses</option>
        <option name="Vintage Cars" value="Vintage Cars">Vintage Cars</option>
        <option name="Planes" value="Planes">Planes</option>
      </select>
      <input type="submit" value="Refine" />
 
     
  </form>
  <hr>
  </p>
 
  <?php
  $sql1 = "SELECT * FROM productlines"; // store sql query
  $stmt1 = get_sql($sql1); // call function to make sql sonnection, with sql query string supplied as parameter
  echo "<table class='indexPLs'>";
  foreach($stmt1 as $productline) { // loop through object returned by sql query, echoing results in table form
    $coco = $productline['productLine'];
    echo "<tr><td class='pl'>".$productline['productLine']."</td><td class='textd'>".$productline['textDescription']."</td></tr>";
  }
  echo "</table>";

  if(isset($_POST['formProd'])) { // check if form has been submitted

    $pl = $_POST['formProd'];
    if($pl == 'redundant') // if no valid data has been submitted, do nothing
      ;
    else { // otherwise process data on server side
      
      echo "<script>document.getElementsByTagName('body')[0].innerHTML = '';</script>"; // clear the page
      include 'navbar.php';
      echo "<h1>$pl</h1>";
      echo "<button onclick='go_home();'>Back to Overview</button><br><br>";

      $sql2 = "SELECT * FROM products WHERE productLine = \"$pl\""; // store second sql query
      $stmt2 = get_sql($sql2); // make second sql connection, with supplied query string

      echo "<table id='tp01'>";
      echo "<tr><th class='dex'>Code</th><th class='dex'>Product</th><th class='dex'>Line</th><th class='dex'>Scale</th><th class='dex'>Vendor</th><th class='dex'>Description</th><th class='dex1'>Quantity</th><th class='dex'>Price</th><th class='dex'>MSRP</th></tr>";
      foreach($stmt2 as $product) { // loop through object returned by sql, echoing results in table form
        echo "<tr><td class='prd'>".$product['productCode']."</td><td class='prd'>".$product['productName']."</td>".
        "<td class='prd'>".$product['productLine']."</td>".
        "<td class='prd'>".$product['productScale']."</td>".
        "<td class='prd'>".$product['productVendor']."</td>".
        "<td class='prd_desc'>".$product['productDescription']."</td>".
        "<td class='prd'>".$product['quantityInStock']."</td>".
        "<td class='prd'>".$product['buyPrice']."</td>".
        "<td class='prd'>".$product['MSRP']."</td>"."</tr>";
      }

      echo "</table>";
    }
    
  }
  include 'footer.php';
  function get_sql($sql) { // function for making sql connection, with supplied query
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
    $conn = null; // terminate sql connection
    return $stmt;
  }
  ?>
</body>
<script>
  function go_home() {
    window.location.href = 'index.php'; // js function called whenever 'Back to overview' button is clicked
  }
</script>
</html>
