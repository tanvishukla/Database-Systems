<?php 
include('config.php');

//Get the POST Data
$Description = ".........";//$_POST['Description'];
$SeatsSelected = array ("1" =>"A","2" =>"C" , "3" => "S"); //$_POST['SeatsSelected'];


//Get the SESSION Data
$TravelDate = "2016-05-12 00:00:00";//$_SESSION['traveldate'];
$CustEmail = "amason5@zimbio.com";//$_SESSION['login_user'];
$BusId = 1;//$_SESSION['BusId'];
$StopId = 1;//$_SESSION['StopId'];"

//Get the Current Date
date_default_timezone_set("America/Los_Angeles");
$BookingDate = date("Y-0m-d")." 00:00:00";

//Set Current Status to Pending
$BookingStatus = "Pending";	


//Define Class for Customer
class Customer{

private $CustID;

public function getCustID(){
	return $this->CustID;
}
}


//Define Class for Bus
class Bus{

	private $TicketFare;

	public function getTicketFare(){
		return $this->TicketFare;
	}

}

//Function which gets the Customer class object and calls its getCustID method
function getCustomerId(Customer $c){

return $c-> getCustID();

}

function getPrice(Bus $b){

	return $b->getTicketFare();
}

try{

//Get the connection object
$dbconnection = getDB();

/**************************************GET CUSTOMER ID FOR CURRENT CUSTOMER BASED ON HIS/HER EMAIL************************/
$query = "SELECT CustID as CustID from CUSTOMER WHERE CustEmail= :CustEmail";
$ps = $dbconnection->prepare($query);
$ps->bindParam(':CustEmail', $CustEmail);
$ps->execute();
$ps->setFetchMode(PDO::FETCH_CLASS, "Customer");
$q=$ps->fetch();

//Call a function by sending the object as a parameter
$CustId = getCustomerId($q);


/***************************************** GET BUS TICKET FARE FOR EACH SEAT********************************************/

static $Amount=0.00;
foreach ($SeatsSelected as $SeatNo => $PassengerType){

if($PassengerType == "C")
{
		$query1 = "SELECT ChildFare as TicketFare from Bus WHERE BusID = :BusID";
		
}

else if($PassengerType =="A"){
		$query1 = "SELECT AdultFare as TicketFare from Bus WHERE BusID = :BusID";
		


}else if($PassengerType == "S"){
		$query1 ="SELECT SeniorFare as TicketFare from Bus WHERE BusID = :BusID";
		
}


	$ps1 = $dbconnection->prepare($query1);
		$ps1->bindParam(':BusID',$BusId);
		$ps1->execute();
		$ps1->setFetchMode(PDO::FETCH_CLASS,"Bus");
		$q1=$ps1->fetch();

		//Call a function by sending bus object as a parameter
		$Amount = $Amount + getPrice($q1);
		

}


echo $Amount;


/***************************************INSERT ALL THE DATA INTO TICKET******************************************/

$insert = "INSERT INTO TICKET(BookingDate, BookingStatus, TravelDate, CustID, BusID, StopID, Amount) values ('$BookingDate','$BookingStatus','$TravelDate','$CustId','$BusId','$StopId','$Amount' )";
$link = mysql_connect('localhost','root','tanvi');
mysql_select_db('mavericks');
mysql_query($insert);
echo "Inserted into TICKET successfully !!";
$generatedTicketId= mysql_insert_id();
echo $generatedTicketId;

//store the generated ticket id value in session
$_SESSION['TicketId'] = $generatedTicketId;


/***************Insert the data into ticket seat num table*************************************************/
foreach($SeatsSelected as $SeatNo => $PassengerType){
$insert_into_seatNum = "INSERT INTO TICKET_SEATNUM(SeatNum,Type,TicketID) values ('$SeatNo','$PassengerType','$generatedTicketId')";
mysql_query($insert_into_seatNum);
echo "Inserted into TICKET_SEATNUM successfully !";
}

}catch(Exception $e){

	echo "<br>";
	echo $e->getMessage();

}

?>