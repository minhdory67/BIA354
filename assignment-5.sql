/*question 2 */

INSERT INTO `customer` 
VALUES (51, 'Minh', 'Nguyen', '2500 California Plaza', 'Omaha', 'NE', '68178','531-266-7675','mnn49815@creighton.edu');

INSERT INTO `item`(`desciption`,`price`)
VALUES ('gloves' ,3.00);

INSERT INTO `invoice` (`date_in`,`customer_id`)
VALUES ('2023-02-26', 51);

INSERT INTO `invoice_item` 
VALUES (14, 201, 5);

/*using a query, show the structure of the customer table*/
DESCRIBE `customer`;

/*Change Jedidiah Bugbee's phone to 712-883-6006*/
UPDATE `customer`
SET `phone` = '712-883-6006'
WHERE `first_name` = 'Jedidiah' AND `last_name`='Bugbee';

/* Increase the price for dry cleaning a Blouse by 14%.*/ 
UPDATE `item`
SET `price` = `price` * 1.14
WHERE `desciption` IN ('Blouse');

/*Show all items that cost between $2.50 and $5 to dry clean.*/
SELECT * 
FROM `item`
WHERE `price` BETWEEN 2.5 AND 5.00;

/*List the first name, last name, and phone for all customers whose second and third numbers of their phone number are 13 and their last name doesn't start with a G.*/
SELECT `first_name`,`last_name`,`phone`
FROM `customer`
WHERE `phone` LIKE '_13%'
AND `last_name` <> 'G';

/*Show all information for customers who have an email address. Sort customers by last name in ascending order and then by first name in descending order.*/
SELECT *
FROM `customer`
WHERE `email` IS NOT NULL
ORDER BY `last_name`, `first_name` DESC;

/* In one query, show the total number of items and the maximum, minimum, and average unit price (round the average to two decimal places) for all items. */
SELECT COUNT(desciption), MAX(price), MIN(price), ROUND(AVG(price), 2)
FROM `item`;

/* Show the customer with the longest email address.*/
SELECT MAX(LENGTH(`email`))
FROM `customer`;

SELECT first_name,last_name
FROM `customer`
WHERE LENGTH(`email`) = 27;

/*Using the DateDiff function, show the difference between today and the date each invoice went out.*/
SELECT invoice_id, DATEDIFF(CURDATE(), date_out)
FROM `invoice`;

/*Show the total number of invoices received on each date where the date in is after June 1, 2019. */
SELECT COUNT(invoice_id)
FROM `invoice`
WHERE date_in > '2019-06-01';#YYYY-MM-DD


/*For each item, show the total quantity included on each invoice where the total quantity is greater than or equal to 200. */
SELECT item_id, sum(quantity)
FROM `invoice_item`
GROUP BY `item_id`
HAVING SUM(quantity)>200;

/* Remove Formal Gown from the item table. */
DELETE FROM `item` 
WHERE `desciption` = 'Formal Gown';

