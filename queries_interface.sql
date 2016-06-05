#Queries

#1) need two insert the Dep ID or Name in the php parser, 
# Params: department name or id

SELECT  E.ID, E.Name, E.SIN, E.Position, E.Address, E.Phone, E.DateOfBirth, Count(D.SIN) NumberOfDependants, 
	COALESCE((P.HourlyRate * P.HoursWorked * 4), (F.Salary / 12)) MonthlyWage, CASE WHEN P.HourlyRate IS NOT NULL THEN 'Part Time' ELSE 'Full Time' END AS Status
FROM ((WorksFor W, Department Dep, (Employee E LEFT OUTER JOIN Dependant D ON (E.ID = D.EID))) 
	LEFT OUTER JOIN PartTime P ON E.ID = P.ID) LEFT OUTER JOIN FullTime F ON E.ID = F.ID
WHERE (Dep.ID = 'Sales' OR Dep.Name = 'Sales') AND			# The requested Department
	Dep.ID = W.DID AND										# There is a worksFor tuple between Emp and Dep
	W.EID = E.ID AND
	CURDATE() BETWEEN W.StartDate AND W.EndDate				# Currently working
GROUP BY E.ID;

#2)
SELECT I.ID, I.Name, SUM(OD.Price) TotalSales, COUNT(OD.Price) NbOrders
FROM Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND
	O.OrderDate <= CURDATE() AND O.Orderdate >= DATE_ADD(CURDATE(), INTERVAL - 12 MONTH)
GROUP BY I.ID
ORDER BY TotalSales DESC
LIMIT 3;

#3)
# Params: date needed 4 times!

SELECT I.ID, I.Name, TRUNCATE( SUM( CurCount.Ct * Inv.UnitPrice ) / SUM( CurCount.Ct) , 2 ) AvgPrice
FROM Item I, Inventory Inv, InColor SK, Orders O, OrderDetail OD, 
	(SELECT IQ.LotNb LotNb, (SumI - COALESCE(SumO, 0)) Ct
	FROM(SELECT Inv.LotNb, SUM(Inv.NbItems) SumI
		FROM Inventory Inv
		WHERE MONTH("2016-02-19") <= MONTH(Inv.DateOfManufacture) AND YEAR("2016-02-19") <= YEAR(Inv.DateOfManufacture)
		GROUP BY Inv.LotNb) IQ LEFT OUTER JOIN 
		(SELECT OD.LotNb, SUM(OD.Quantity) SumO
		FROM OrderDetail OD, Orders O
		WHERE O.ID = OD.OID AND
			MONTH("2016-02-19") < MONTH(O.OrderDate) AND YEAR("2016-02-19") <= YEAR(O.OrderDate)
		GROUP BY OD.LotNb) OQ  ON IQ.LotNb = OQ.LotNb) CurCount
WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND Inv.LotNb = CurCount.LotNb AND CurCount.Ct > 0
GROUP BY I.ID
ORDER BY AvgPrice DESC;


#4) Every tuple will include Customer AND Order information, needs to be parsed for each customer

SELECT C.Name, C.Address, C.Phone, I.Name Item, OD.Price OrderPrice, OD.Quantity OrderQuantity
FROM Customer C, Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
WHERE C.Name = O.CustName AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND I.ID = SK.IID AND SK.SKU = Inv.SKU AND
	C.Name IN (SELECT Name 
				FROM (SELECT C.Name Name, SUM(OD.Price) TotalSales
					FROM Customer C,Item I, Orders O, OrderDetail OD, Inventory Inv, InColor SK
					WHERE I.ID = SK.IID AND SK.SKU = Inv.SKU AND Inv.LotNb = OD.LotNb AND OD.OID = O.ID AND C.Name = O.CustName AND
						O.OrderDate <= CURDATE() AND O.Orderdate >= DATE_ADD(CURDATE(), INTERVAL - 12 MONTH)
					GROUP BY Name
					ORDER BY TotalSales DESC
					LIMIT 3
					) Best
				)
ORDER BY C.Name DESC;

#5)

SELECT C.Name, C.Address, A.InvoiceNb, A.OID OrderID, OD.DetailNb, S.DateShipped, COALESCE(R.PaymentDue, A.TotalAmount) AmountOwed
FROM (Customer C, Orders O, OrderDetail OD, Shipment S, Account A) LEFT OUTER JOIN ReceivablesInstallment R ON R.InvoiceNb = A.InvoiceNb
WHERE C.Name = O.CustName AND O.ID = OD.OID AND OD.DetailNb = S.OID AND O.ID = A.OID AND A.Paid = 0
ORDER BY C.Name;

#6) Needs an invoice number as a parameter (last AND in where clause). Converts all nulls to readable data
# Param: Invoice Nb

SELECT A.InvoiceNb, C.Name, C.Address, C.Phone, O.ID OrderID, O.OrderDate, I.Name Item, SK.CName Color, Inv.UnitPrice, OD.Quantity, OD.Price ItemTotal, A.TotalAmount, COALESCE(CEIL(A.TotalAmount / R.Installments), "Due In Full") NbPayments, COALESCE(R.Installments, "N/A") PaymentAmt, COALESCE(S.DateShipped, "Not Shipped") DateShipped
FROM (Customer C, Item I, Inventory Inv, Orders O, InColor SK),  Account A LEFT OUTER JOIN ReceivablesInstallment R ON R.InvoiceNb = A.InvoiceNb, OrderDetail OD LEFT OUTER JOIN Shipment S ON OD.DetailNb = S.OID 
WHERE C.Name = O.CustName AND O.ID = A.OID AND O.ID = OD.OID AND OD.LotNb = Inv.LotNb AND Inv.SKU = SK.SKU AND SK.IID = I.ID AND A.InvoiceNb = 1;

