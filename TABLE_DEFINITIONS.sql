CREATE TABLE Employee (
	ID INT AUTO_INCREMENT PRIMARY KEY,
	Name VARCHAR(50),
	SIN VARCHAR(50),
	Position VARCHAR(50),
	Phone VARCHAR(50),
	Address VARCHAR(50),
	DateOfBirth DATE
);
CREATE TABLE FullTime (
	ID INT,
	Salary FLOAT,
	PRIMARY KEY (ID),
	FOREIGN KEY (ID) REFERENCES Employee(ID)
);
CREATE TABLE PartTime (
	ID INT,
	HourlyRate FLOAT,
	HoursWorked INT,
	PRIMARY KEY (ID),
	FOREIGN KEY (ID) REFERENCES Employee(ID)
);
CREATE TABLE Dependant (
	EID INT,
	Name VARCHAR(20),
	SIN VARCHAR(50),
	DateOfBirth DATE,
	PRIMARY KEY (SIN),
	FOREIGN KEY (EID) REFERENCES Employee(ID)
);
CREATE TABLE Department (
	ID INT AUTO_INCREMENT PRIMARY KEY,
	Name VARCHAR(20),
	Room VARCHAR(10),
	Phone1 VARCHAR(50),
	Phone2 VARCHAR(50),
	Fax VARCHAR(50)
);
CREATE TABLE ManagerOf (
	EID INT,
	DID INT,
	StartDate DATE,
	EndDate DATE,
	PRIMARY KEY (EID,StartDate),
	FOREIGN KEY (EID) REFERENCES Employee(ID)
);
CREATE TABLE WorksFor (
	EID INT,
	StartDate DATE,
	DID INT,
	EndDate DATE,
	PRIMARY KEY (EID,StartDate),
	FOREIGN KEY (EID) REFERENCES Employee(ID)
);
CREATE TABLE Customer (
	Name VARCHAR(20) PRIMARY KEY,
	Address VARCHAR(50),
	Phone VARCHAR(15)
);
CREATE TABLE Account(
	InvoiceNb INT AUTO_INCREMENT PRIMARY KEY,
	OID INT FOREIGN KEY REFERENCES Orders(ID),
	TotalAmount FLOAT,
	Paid BOOLEAN
);
CREATE TABLE ReceivablesInstallment (
	InvoiceNb INT PRIMARY KEY,
	PaymentDue BOOLEAN,
	Installments FLOAT,
	FOREIGN KEY (InvoiceNb) REFERENCES Account(InvoiceNb)
);
CREATE TABLE Item (
	ID INT AUTO_INCREMENT PRIMARY KEY,
	Name VARCHAR(20)
);
CREATE TABLE Colors(
	Name VARCHAR(20) PRIMARY KEY
);
CREATE TABLE InColor(
	SKU INT
	IID INT FOREIGN KEY REFERENCES Item(ID),
	CName FOREIGN KEY REFERENCES Colors(Name),
	PRIMARY KEY (SKU)
);
CREATE TABLE Inventory(
	LotNb INT AUTO_INCREMENT PRIMARY KEY,
	SKU INT FOREIGN KEY REFERENCES InColor(SKU),
	DateOfManufacture DATE,
	NbItems INT,
	UnitPrice FLOAT
);
CREATE TABLE OrderDetail(
	DetailNb INT AUTO_INCREMENT PRIMARY KEY,
	OID INT FOREIGN KEY REFERENCES Orders(ID),
	LotNb INT FOREIGN KEY REFERENCES Inventory(LotNb),
	Quantity INT,
	Price FLOAT,
);
CREATE TABLE Orders (
	ID INT PRIMARY KEY AUTO_INCREMENT,
	CustName VARCHAR(20) FOREIGN KEY REFERENCES Customer(Name),
	OrderDate DATE
);
CREATE TABLE Shipment(
	OID INT PRIMARY KEY,
	DateShipped DATE,
	FOREIGN KEY(OID) REFERENCES OrderDetail(DetailNb)
);
Triggers:
## Trigger replaces order quantity with max stock available
CREATE TRIGGER Out_Of_Stock
BEFORE INSERT ON OrderDetail
FOR EACH ROW
BEGIN
	IF (NEW.Quantity > (SELECT Remaining.Ct Ct
			FROM (SELECT IQ.LotNb LotNb, (SumI - COALESCE(SumO, 0)) Ct
				FROM(SELECT Inv.LotNb, SUM(Inv.NbItems) SumI
					FROM Inventory Inv
					GROUP BY Inv.LotNb) IQ LEFT OUTER JOIN
			(SELECT OD.LotNb, SUM(OD.Quantity) SumO
			FROM OrderDetail OD, Orders O
			WHERE O.ID = OD.OID
			GROUP BY OD.LotNb) OQ ON IQ.LotNb = OQ.LotNb) Remaining
			WHERE Remaining.LotNb = NEW.LotNb)) THEN
			SET NEW.Quantity = Remaining.Ct;
	END IF;
END;