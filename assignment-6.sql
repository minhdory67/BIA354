# show item information for all items that have been included on an invoice. Show common column only once 
select *
from invoice_item
inner join item USING (`item_id`);

# Show customers and their invoices. Include all customers whether or not they have an invoice. 
SELECT *
FROM customer c
LEFT OUTER JOIN invoice i 
ON c.customer_id = i.customer_id;

# Show customers (first and last name) that picked up (date out) their dry cleaning between September 1, 2019 and September 30, 2019.
SELECT first_name, last_name
FROM customer c
LEFT OUTER JOIN invoice i 
ON c.customer_id = i.customer_id
WHERE date_out BETWEEN '2019-09-01' AND '2019-09-30';

# Using subqueries only, show the first name and last name of all customers who have had an invoice with an item named Dress Shirt. Present the results sorted by last name in ascending order and then first name in descending order.
SELECT last_name, first_name 
FROM customer 
WHERE customer_id IN (SELECT customer_id 
					  FROM invoice
					  WHERE invoice_id IN (SELECT invoice_id
										   FROM invoice_item
										   WHERE item_id IN (SELECT item_id
															 FROM item
															 WHERE `desciption` = 'Dress Shirt')))
ORDER BY last_name, first_name DESC;
															
# Without entering table IDs except to connect the tables, use subqueries to change Jedidiah Bugbee's quantity of Dress Shirts included on his March 21, 2020 invoice from 6 to 3.
UPDATE invoice_item 
SET quantity = 3 
WHERE quantity = 6 
AND invoice_id IN (SELECT invoice_id
				   FROM invoice
				   WHERE date_in = '2020-03-21' 
				   AND customer_id IN (SELECT customer_id
									   FROM customer
									   WHERE first_name = 'Jedidiah' AND last_name = 'Bugbee' )); 

#Show customers (first and last name) and their total number of invoices. Give the total column an alias of total_invoices.
SELECT first_name, last_name, COUNT(invoice_id) AS total_invoices
FROM customer c
LEFT OUTER JOIN invoice i
ON c.customer_id = i.customer_id
GROUP BY first_name;

#Show customers (first and last name) that have had more than $500 worth of dry cleaning done. Give the total cost an alias of total_dry_cleaning.
SELECT c.first_name, c.last_name, sum(i.price * ii.quantity) AS total_dry_cleaning
FROM customer c
INNER JOIN invoice iv on c.customer_id = iv.customer_id
INNER JOIN invoice_item ii on iv.invoice_id = ii.invoice_id
INNER JOIN item i on ii.item_id = i.item_id
GROUP BY c.first_name
HAVING total_dry_cleaning >= 500;

# Show the invoice id, subtotal (price times quantity), tax (subtotal times 7.5% tax rate), and total (subtotal plus tax) for all invoices where the subtotal is greater than $150. Set column aliases for subtotal, tax, and total. Sort by subtotal in descending order.
SELECT iv.invoice_id, SUM(i.price*ii.quantity) AS subtotal, SUM(i.price*ii.quantity*0.075) AS tax, SUM(i.price*ii.quantity + i.price*ii.quantity*0.075) AS total 
FROM invoice iv 
INNER JOIN invoice_item ii on iv.invoice_id = ii.invoice_id
INNER JOIN item i on ii.item_id = i.item_id
GROUP BY iv.invoice_id
HAVING subtotal > 150 
ORDER BY subtotal DESC;

#Create a view called no_invoices. This view should display all information for customers who have no invoices. After creating this view, select from it and show only a list of customer emails.
CREATE VIEW `no_invoices` AS
SELECT *
FROM customer c
LEFT OUTER JOIN invoice i 
USING (`customer_id`)
WHERE invoice_id IS NULL;

SELECT email
FROM `no_invoices`;

#Create a view called invoice_summary. This view should display the invoice ID, date in, date out, description, quantity, and price. After creating this view, select from it while showing invoice summaries for those containing men's shirts where the date out was on or after October 1, 2019.
CREATE VIEW `invoice_summary` AS
SELECT invoice_id, date_in, date_out, desciption, quantity, price
FROM invoice iv 
INNER JOIN invoice_item ii USING (`invoice_id`)
INNER JOIN item i USING (`item_id`);

SELECT *
FROM `invoice_summary`
WHERE desciption = 'Men\'s shirt' AND
      date_out >= '2019-10-01';


