<?php 

require('../model/waterlevel-entries.php');

$db = new Database();
$id = $_GET['id'];

if(isset($_POST['action']) && $_POST['action']== "view" ){
    $output = '';
    $data = $db->read($id);
   //  print_r($data);
    if($db->totalRowCount()>0){ 
        $output .= '<table class="table table-striped table-sm table-bordered">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Tank ID</th>
                <th>Water Level</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
        ';
        foreach($data as $row){
            $output .= '<tr class="text-center text-secondary">
            <td>'.$row['ID'].'</td>
            <td>'.$row['TankID'].'</td>
            <td>'.$row['WaterLevel'].'</td>
            <td>'.$row['Time'].'</td>
            </tr>';
        }
        $output .='</tbody></table>';
        echo $output;
    }else{
        echo '<h3 class="text-center text-secondary mt-5">:( no any user present in the database )</h3>';
    }
}


?>