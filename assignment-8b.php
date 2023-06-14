<?php
$results = $customer = "";
require_once("db.php");

$query = 
"SELECT customer_id, first_name, last_name
 FROM `customer`
 ORDER BY last_name";

$stmt = $conn->prepare($query);
$stmt->execute();

foreach ($stmt as $row) {
  $customer .= "<option value='{$row["customer_id"]}'>{$row["first_name"]} {$row["last_name"]}</option>";
}

if (isset($_POST["submit"]) && isset($_POST["customer"])) {
  $delete = 
  "DELETE
  FROM `customer`
  WHERE `customer_id` = ?";

  $stmt = $conn->prepare($delete);
  $stmt->execute([$_POST["customer"]]);

  foreach ($stmt as $row) {
    $results .= 
    "<tr>
       <td>{$row["customer_id"]}</td>
       <td>{$row["first_name"]}</td>
       <td>{$row["last_name"]}</td>
     </tr>";
  }

  $results = 
  "<h5>Delete customer:</h5>
   <table class='table table-bordered table-striped'>
     <tr>
       <th>Customer ID</th>
       <th>First Name</th>
       <th>Last Name</th>
     </tr>
     {$results}
   </table>";
}
$conn = null;
?> 

<!doctype html>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.css">
  </head>
  <body>
    <div class="jumbotron text-center">
      <h1>Customers</h1>
    </div>
    <div class="container">
      <form method="post" class="mb-3">
        <div class="form-group">
          <label>Select a customer to delete: </label>
          <select class="form-control" name="customer">
            <option value="" selected disabled>Delete customer...</option>
            <?php echo $customer; ?>
          </select>
        </div>
        <button class="btn btn-primary" name="submit">Submit</button>
      </form>
      <?php echo $results; ?>
    </div>
  </body>
</html>