<?php
// set blank options and message variables
$invoice = $item = $message = "";

//import the php connection file
require_once("db.php");

//query all invoice id
//a drop down that contains all invoice ids
$query = "SELECT invoice_id FROM invoice ORDER BY invoice_id";
$stmt = $conn->query($query);
	//loop through each query row and add each dropdown option to the customer option
	foreach ($stmt as $row){
		$invoice .= "<option value='{$row["invoice_id"]}'>{$row["invoice_id"]}</option>";
	}

//query all item description
//a drop down that contains all item description
$query = "SELECT item_id, description FROM item ORDER BY description";
$stmt = $conn->query($query);
	//loop through each query row and add each dropdown option to the product options
	foreach ($stmt as $row){
		$item .= "<option value='{$row["item_id"]}'>{$row["description"]}</option>";
	}

//when the form is submitted, check condition
if(isset($_POST["submit"])){
  
  //store value to use with execute
  $invoice = $_POST["invoice-id"];
  $item = $_POST["item-id"];
  $quantity = $_POST["quantity"];
  
  //prepares the query to query whether the item already exist by using invoice
  //the php should check if the invoice already has the item on it and then: 
  $query = "SELECT invoice_id FROM invoice_item WHERE item_id=? AND invoice_id=?;";
  $stmt = $conn->prepare($query);
  
  //execute the query
  $stmt->execute([$item,$invoice]);
  
  //check if item exists and runs if the item does exist
  if ($stmt->rowCount () > 0){
	$query = "UPDATE invoice_item SET quantity = quantity + ? WHERE item_id=? AND invoice_id=?";
	$stmt = $conn->prepare($query);
	
	//execute the query
	$stmt->execute([$quantity,$item, $invoice]);
	
	//add message show quantity has been updated
	$message .="<h5>Update Invoice Item </h5> The Item <b>$item</b> 
	has its quantity increase by <b>$quantity</b> where the invoice ID is <b> $invoice_id </b>.";
	
  //run if the item doesn't exist 
  } else {
	//prepare the query to insert the item and quantity
	//if the item does not exist on the invoice, the code should insert the item and quantity for the selected invoice
  	$query = "INSERT INTO invoice_item (item_id, quantity, invoice_id) VALUES (?,?,?)";
	$stmt = $conn->prepare($query);
	
	//execute the query
	$stmt->execute([$item, $quantity, $invoice]);
	
	//add message
	$message .= "<h5> Inserted item and quantity </h5> Successfully inserted 
	item id <b>$item</b> and quantity <b>$quantity </b> as new item.";
  }
} 
  
//close the connection
$conn = null;
?>
<!--begins the HTML code -->

<!doctype html>
<html>
  <head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>
  <body>
	  <body>
    <div class="jumbotron text-center">
      <h1>Item and Invoice</h1>
    </div>
    <div class="container">
      <form method="post" class="mb-3">	
        <div class="form-group">
          <label>Invoice ID: </label>
          <select class="form-control" name="invoice-id"><?php echo $invoice; ?></select>
        </div>
		<div class="form-group">
          <label>Item Description: </label>
          <select class="form-control" name="item-id"><?php echo $item; ?></select>
        </div> 
		<div>
		  <label>Quantity: </label>
		  <input class="form-control" type="text" name="quantity">
		</div>
        <button class="btn btn-primary" name="submit">Submit</button>
      </form>
      <?php echo $message; ?>
    </div>
  </body>
</html>



