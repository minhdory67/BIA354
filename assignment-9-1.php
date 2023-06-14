<?php
// set blank options and message variables
$invoice = $customer = $message = "";

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

//a drop down that contains all customers
$query = "SELECT customer_id, first_name, last_name FROM customer ORDER BY last_name";
$stmt = $conn->query($query);
	//loop through each query row and add each dropdown option to the product options
	foreach ($stmt as $row){
		$customer .= "<option value='{$row["customer_id"]}'>{$row["first_name"]} {$row["last_name"]}</option>";
	}

//when the form is submitted, check condition
if(isset($_POST["submit"])){
  
  //store value to use with execute
  $invoice = $_POST["invoice-id"];
  $customer = $_POST["customer-id"];
  $quantity = $_POST["quantity"];
  
  //prepares the query to query whether invoice exist using customer
  //the php should check if an invoice already exists today for the selected customer, then: 
  $query = "SELECT customer_id FROM invoice WHERE invoice_id=? AND customer_id=? AND date_in=Curdate();";
  $stmt = $conn->prepare($query);
  
  //execute the query
  $stmt->execute([$customer,$invoice]);
  
  //check if item exists and store the newly inserted record in a PHP variable
  if ($stmt->rowCount () > 0){
	
	//add message show invoice already exist
	$message .="Customer_id <b>$customer</b> with invoice_id <b>$invoice</b>is already existed today";
	
  //run if the invoice doesn't exist 
  } else {
	//prepare the query to insert the invoice, customer, and date
	//if the item does not exist on the invoice, the code should insert the item and quantity for the selected invoice
  	$query = "INSERT INTO invoice (customer_id, date_in, invoice_id) VALUES (?,CURDATE(),?)";
	$stmt = $conn->prepare($query);
	
	//execute the query
	$stmt->execute([$customer, $quantity, $invoice]);
	
	//add message
	$message .= "<h5> Inserted invoice and customer </h5> Successfully inserted 
	invoice_id <b>$invoice</b> for customer_id <b>$customer</b> today.";
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
      <h1>Customer and Invoice</h1>
    </div>
    <div class="container">
      <form method="post" class="mb-3">	
        <div class="form-group">
          <label>Invoice ID: </label>
          <select class="form-control" name="invoice-id"><?php echo $invoice; ?></select>
        </div>
		<div class="form-group">
          <label>Customer: </label>
          <select class="form-control" name="customer-id"><?php echo $customer; ?></select>
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