<?php
$content ="";
require_once("db.php");
$query =
	"SELECT c.first_name, c.last_name, c.phone, c.email, i.invoice_id, i.date_in, i.date_out, it.desciption, it.price, ii.quantity
	FROM customer c
	INNER JOIN invoice i ON i.customer_id = c.customer_id
	INNER JOIN invoice_item ii ON ii.invoice_id = i.invoice_id
	INNER JOIN item it ON it.item_id = ii.item_id
	WHERE date_in >'2020-01-01'
	ORDER BY last_name DESC;";
$stmt = $conn->prepare($query);
$stmt->execute();
foreach ($stmt as $row){
	$content .= 
	" <tr>
	  <td>{$row["first_name"]}</td>
	  <td>{$row["last_name"]}</td>
	  <td>{$row["phone"]}</td>
	  <td>{$row["email"]}</td>
	  <td>{$row["invoice_id"]}</td>
	  <td>{$row["date_in"]}</td>
	  <td>{$row["date_out"]}</td>
	  <td>{$row["desciption"]}</td>
	  <td>{$row["price"]}</td>
	  <td>{$row["quantity"]}</td>
	  </tr>";
}

$content = 
 	"<table>
  	 <tr>
	 <th> First Name </th>
	 <th> Last Name </th>
	 <th> phone number </th>
	 <th> email </th>
	 <th> invoice_id </th>
	 <th> date_in </th>
	 <th> date_out </th>
	 <th> description </th>
	 <th> price </th>
	 <th> quantity </th>
	 </tr>
	 {$content}
	 </table>";
$conn = null;
?>

<!doctype html>
<html>
  	<head>
<link href="main.css" rel="stylesheet">
	</head>

   <div class="container">
	 <h3>
	   Customer with id #1:
	 </h3>
	 <div>
	   <?php echo $content; ?>
	 </div>
  </div>
</html>