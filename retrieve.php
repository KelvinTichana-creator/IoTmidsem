<?php
    $servername= "localhost";
    $username="root";
    $password="";
    $dbname="lab3waterlevel";
    $con = mysqli_connect($servername,$username,$password,$dbname);
    $id = $_GET['id'];
    $data=array();        

    $q=mysqli_query($con,"select WaterLevel from main WHERE TankID = ('$id') ORDER BY ID DESC LIMIT 1");    
    
    $row=mysqli_fetch_object($q);
    while ($row)
    {         
        echo " {$row->WaterLevel}";
        $row=mysqli_fetch_object($q);
    }       
?>

