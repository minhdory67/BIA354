<?php
$content ="";
if (isset($_POST['submit'])){
  require_once("db.php");
  
  $total_DC = $_POST['total'];
  $query =
	  "SELECT c.first_name, c.last_name, SUM(it.price * ii.quantity) AS total_dry_cleaning
	  FROM customer c
	  INNER JOIN invoice i ON i.customer_id = c.customer_id
	  INNER JOIN invoice_item ii ON ii.invoice_id = i.invoice_id
	  INNER JOIN item it ON it.item_id = ii.item_id
	  GROUP BY c.customer_id
	  HAVING total_dry_cleaning > ?;";
  $stmt = $conn->prepare($query);
  $stmt->execute([$total_DC]);

  $total = 0;
  foreach ($stmt as $row){
  $content .= 
	  " <tr>
		<td>{$row["first_name"]}</td>
		<td>{$row["last_name"]}</td>
		<td>$".number_format($row["total_dry_cleaning"], 2)."</td>
		</tr>";
  $total += $row["total_dry_cleaning"];
  }

  $content = 
	  "<h3> List of all customers whose total dry cleaning exceeds the value entered into the form: 	</h3>
	   <table>
	   <tr>
	   <th> First Name </th>
	   <th> Last Name </th>
	   <th> Total Dry Cleaning </th>
	   </tr>
	   {$content}
	   <tr>
	   <th colspan = '2'> Total </th>
	   <th>$" .number_format($total,2) ."</th>
	   </tr>
	   </table>";
  $conn = null;
}
?>

<!doctype html>
<html>
  	<head>
<link href="main.css" rel="stylesheet">
	</head>

   <div class="container">
	 <h3>
	    Enter total dry cleaning amount to see whose orders exceed the amount provided:
	 </h3>
	 <form method='post'>
	   <input type='number' placeholder="Total dry cleaning amount" name='total'>
	   <input type='submit' name='submit'>
	 </form>
	 <div>
	   <?php echo $content; ?>
	 </div>
  </div>