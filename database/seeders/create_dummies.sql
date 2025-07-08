SELECT *, SUM(supa.amount)
        FROM supplier_accounts as supa
        LEFT JOIN
        (SELECT item.container_id as cont_id, SUM(sel.quantity) as total_quantity, SUM(sel.sell_price) as total_sells_amount, SUM((sel.sell_price * sel.quantity )) as total_price, 
        SUM(sel.discount_price) as total_discount, SUM(sel.received_amount) as total_amount, SUM(sel.balance) as total_balance
        FROM items as item
        JOIN sells as sel
        ON sel.item_id = item.id 
        WHERE sel.status = 'sold'
        GROUP BY item.container_id) as it
        ON it.cont_id = supa.container_id
        GROUP BY supa.supplier_id
        ORDER BY supa.supplier_id ASC




SELECT *,
CASE
WHEN (supa.type = 'credit' AND supa.container_id IS NULL) THEN supa.amount
WHEN (supa.type = 'credit' AND supa.container_id IS NOT NULL) THEN it.total_amount
ELSE (-1 * supa.amount)
END AS new_amount
        FROM supplier_accounts as supa
        LEFT JOIN
        (SELECT item.container_id as cont_id, SUM(sel.quantity) as total_quantity, SUM(sel.sell_price) as total_sells_amount, SUM((sel.sell_price * sel.quantity )) as total_price, 
        SUM(sel.discount_price) as total_discount, SUM(sel.received_amount) as total_amount, SUM(sel.balance) as total_balance
        FROM items as item
        JOIN sells as sel
        ON sel.item_id = item.id 
        WHERE sel.status = 'sold'
        GROUP BY item.container_id) as it
        ON it.cont_id = supa.container_id
        GROUP BY supa.supplier_id
        ORDER BY supa.supplier_id ASC




//done
CREATE VIEW alldata AS
SELECT *,
CASE
WHEN (supa.type = 'credit' AND supa.container_id IS NULL) THEN supa.amount
WHEN (supa.type = 'credit' AND supa.container_id IS NOT NULL) THEN it.total_amount
ELSE (-1 * supa.amount)
END AS new_amount
        FROM supplier_accounts as supa
        LEFT JOIN
        (SELECT item.container_id as cont_id, SUM(sel.quantity) as total_quantity, SUM(sel.sell_price) as total_sells_amount, SUM((sel.sell_price * sel.quantity )) as total_price, 
        SUM(sel.discount_price) as total_discount, SUM(sel.received_amount) as total_amount, SUM(sel.balance) as total_balance
        FROM items as item
        JOIN sells as sel
        ON sel.item_id = item.id 
        WHERE sel.status = 'sold'
        GROUP BY item.container_id) as it
        ON it.cont_id = supa.container_id 
        ORDER BY supa.supplier_id ASC



SELECT usr.id, usr.name, usd.account_type, ad.*
FROM users as usr
LEFT JOIN user_details as usd
ON usd.user_id = usr.id
LEFT JOIN 
(
SELECT *, SUM(ad.new_amount) 
FROM `alldata` as ad
WHERE ad.created_at BETWEEN '2023-03-03 19:46:37' AND '2023-03-16 08:25:10'
GROUP BY ad.supplier_id
) AS ad 
ON ad.supplier_id = usr.id
WHERE usd.account_type = 'Supplier'
ORDER BY usr.id ASC 




SELECT tr.id, tr.customer_id, tr.bill_id, tr.payment_amount AS tran_amount, pym.amount as pay_amount, sl.container_id, cont.supplier_id
FROM transections as tr 
LEFT JOIN
(SELECT py.transection_id, SUM(py.received_amount) AS amount 
FROM payments as py
WHERE py.created_at BETWEEN '2023-03-26 10:03:02' AND '2023-03-26 12:07:25'
GROUP BY py.transection_id) AS pym
ON pym.transection_id = tr.id
RIGHT JOIN
(
    SELECT sls.bill_id, sls.item_id, itm.container_id
    FROM sells as sls
    LEFT JOIN items as itm
    ON itm.id = sls.item_id
) as sl
ON sl.bill_id = tr.bill_id
RIGHT JOIN containers as cont 
ON cont.id = sl.container_id




SELECT supa.supplier_id,
SUM(CASE
WHEN (supa.type = 'credit' AND supa.container_id IS NULL) THEN supa.amount 
ELSE (-1 * supa.amount)
END ) AS direct_amount, "other transections" as description
FROM supplier_accounts as supa
WHERE supa.bill_id IS NULL AND supa.container_id IS NULL
AND supa.created_at BETWEEN '2023-03-26 10:03:02' AND '2023-03-26 12:07:25'
GROUP BY supa.supplier_id








// all suppliers information of earning based on register

SELECT us.id, us.name, ud.phone_no_one, ud.account_type, acc.newamount
FROM users as us 
LEFT JOIN user_details as ud
ON ud.user_id = us.id
LEFT JOIN 
(SELECT *, 
SUM((CASE
	WHEN sl.type = 'credit' THEN sl.amount
 	ELSE sl.amount * -1
END)) AS newamount
FROM supplier_lines as sl 
WHERE sl.register_id = 3
GROUP BY sl.supplier_id) as acc 
ON acc.supplier_id = ud.user_id
WHERE ud.account_type = 'Supplier'




// supplier account list with balance

SELECT *, SUM(sl.amount), 
SUM((CASE
	WHEN sl.type = 'credit' THEN sl.amount
 	ELSE sl.amount * -1
END)) AS finalamount
FROM supplier_lines as sl 
WHERE sl.supplier_id = 5
GROUP BY IFNULL(sl.container_id, sl.id)