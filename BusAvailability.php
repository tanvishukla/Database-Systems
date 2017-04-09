<?php 

include('config.php');


class BusAvailability{
	public function getBuses(){

		echo '<tr>';
		echo '<td>'.$this->StopID.'</td>';
		echo '<td>'.$this->BusId.'</td>';
		echo '<td>'.$this->BusModel.'</td>';
		echo '<td>'.$this->DepartureTime.'</td>';
		echo '</tr>';

	}

}

try{
$sourceName ="Bonner"; //$_POST['From']
$destName = "Continental"; //$_POST['To']
$travelDate = "12/4/2016"; //$_POST['travelDate']


//Set Session Varibales Source, Destination and Travel Date
$_SESSION['source'] = $sourceName;
$_SESSION['destination'] = $destName;
$_SESSION['traveldate'] = $travelDate;


$dbconnection =getDB();



	$query = "SELECT Q1.StopID, B.BusId, B.BusModel, ADDTIME( SH.DepartureTime, Q1.Offset) as DepartureTime FROM(
	SELECT ST.StopID as StopID, ST.BusID, SEC_TO_TIME(ST.Offset*60) as Offset FROM 
	STOPS ST JOIN STOPS DT
	WHERE ST.Name like '%".$sourceName."%' AND DT.Name like '%".$destName."%' AND ST.BusID = DT.BusID) as Q1 INNER JOIN Bus B on (Q1.BusId = B.BusId) INNER JOIN FOLLOWSSCHEDULE FS on (B.BusId = FS.BusId) INNER JOIN SCHEDULE SH ON (FS.ScheduleId = SH.ScheduleId) ORDER BY DepartureTime";

$stmt = $dbconnection->query($query);

echo '<table border="1">';
echo '<tr>
<th>Stop Id</th>
<th>BusId</th>
<th>Bus Model</th>
<th>Departure Time</th></tr>';

foreach ($stmt->fetchAll(PDO::FETCH_CLASS,'BusAvailability')as $r) {

	$r->getBuses();
}
echo '<table>';

}
catch(Execption $e){

	echo $e;
}


?>