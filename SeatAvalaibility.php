<?php
include('config.php');
class SelectedBus{

	private $capacity;
	public function getCapacity(){

		echo $this->capacity;
	}
}

class UnavailableSeat{

private $unavailableSeatNum;

public function getSeatNum(){

	echo $this->unavailableSeatNum;
	echo '<BR>' ;
}


}

try{

$busID = 1;//$_POST['busID'];
$stopID = 1; //$_POST['stopID'];
$departureTime = '10:13:00'; //$_POST['departureTime'];
$travelDate = "12/4/2016"; //$_SESSION['traveldate'];


//Set Session variables BusID and departure Time
$_SESSION['BusId'] = $busID;
$_SESSION['departureTime'] = $departureTime;

$dbconnection =getDB();
$query1 = "SELECT B.BusCapacity as capacity FROM Bus B WHERE BusId =:id";
$ps = $dbconnection->prepare($query1);
$ps->bindParam(':id',$busID);

$ps->execute();
$ps->setFetchMode(PDO::FETCH_CLASS,"SelectedBus");

$q=$ps->fetch();
$q->getCapacity();


$query2 = "SELECT TS.SeatNum 
FROM Ticket_SeatNum TS
INNER JOIN TICKET T ON (T.TicketID = TS.TicketID)
WHERE T.TravelDate = $travelDate AND T.BusId = $busID AND T.StartTime < '$travelDate'AND T.EndTime > '$travelDate'AND T.Status ='CONFIRMED'";

$prepared = $dbconnection->query($query2);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_CLASS,"UnavailableSeat");

echo "Booked SeatNums :";

while($q1=$prepared->fetch()){

	$q1->getSeatNum();
}




}catch(Exception $e){

	echo $e;
}


 ?>