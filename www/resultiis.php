<?php
if (isset($_SERVER['HTTPS'])) {$protocol = 'https://';}else{$protocol='http://';} 
require_once "class/class.db.php";
require_once "class/db.inc";
$database = new DB();
$database->changecharset("utf8");
$query="select * from computer where hostname='".$_GET['jmeno']."'";
$numrec=$database->num_rows( $query );
if ($numrec == 0) {
        $names =array(
                'hostname'=>$_GET['jmeno'],
                'ip'=>$_GET['ip'],
                'description'=>'',
                'enable'=>1,
                'alarm'=>90,
                'warning'=>85,
                'iisth'=>'1_10_20000'                
        );
        $database->insert( 'computer', $names );
}else{
        $names =array(
                'ip'=>$_GET['ip']
        );
        $where_clause = array(
        	'hostname' => $_GET['jmeno']
  	    );
  	    $database->update( 'computer', $names, $where_clause, 1 );
}
$query="select id,enable from computer where hostname='".$_GET['jmeno']."'";
list( $id,$enable ) = $database->get_row( $query );
if ($enable == 1){
        $names =array(
                'computer_id'=>$id,
                'record_type'=>4,
                'value'=>$_GET['iisdata']
        );
        $database->insert( 'data', $names );
        }
$bf = date('Y-m-d H:i:s', (time() - 86400));
$database->query( "DELETE FROM data WHERE time_record < '".$bf."'" );
?>