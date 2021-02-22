<?php
error_reporting(0);
ini_set("display_errors", 0);
if (isset($_SERVER['HTTPS'])) {$protocol = 'https://';}else{$protocol='http://';} 
require_once "class/class.db.php";
require_once "class/db.inc";
$database = new DB();
$database->changecharset("utf8");
require_once( 'include/header.php' );
function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}

function ping($host, $timeout = 1) {
    /* ICMP ping packet with a pre-calculated checksum */
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket  = socket_create(AF_INET, SOCK_RAW, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
    socket_connect($socket, $host, null);
    $ts = microtime(true);
    socket_send($socket, $package, strLen($package), 0);
    if (socket_read($socket, 255)) {
        //$result = microtime(true) - $ts;
        $result = 'UP';
    } else {
        //$result = false;
        $result = 'DOWN';
    }
    socket_close($socket);
    return $result;
}
$query="select * from computer where enable=1 order by ip";
$result = $database->get_num_results( $query );
?>
</head>
<body>
<script type="text/javascript">
$(document).ready(function() {
  setInterval(function() {
    cache_clear()
  }, 20000);
});

function cache_clear() {
  window.location.reload(true);
  // window.location.reload(); use this if you do not remove cache
}
</script>
<table class="maintable">
<?php
foreach( $result as $row_array ){
?>
    <tr class="computer">
        <td class="hostname"><div class="head"><?php echo $row_array[1] ." - ".$row_array[2]; ?></div><div class="description"><?php echo $row_array[3]; ?></div></td>
<?php
    if (ping($row_array[2]) == 'UP') {$bgcolor = 'green';}else{$bgcolor = 'red';}
?>
        <td class="cpu"><div class="<?php echo $bgcolor;?>">PING<br /><?php echo ping($row_array[2]);?></div></td>
<?php
    //hw data
    $query="select value from data where computer_id=".$row_array[0]." and record_type=6 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec != 0) {
        list( $value ) = $database->get_row( $query );
        $fl=0;
        if (strpos($value, 'ERROR')) $fl=1;
        if (strpos($value, 'YES')) $fl=1;
        $hw=explode('_',$value);
        if ($fl==0) {$bgcolor = 'green';}else{$bgcolor = 'red';}
        echo "<td class=\"disc\"><div class=\"".$bgcolor."\">HW monitor: ".$hw[8]."<br />";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">Physical disc: ".$hw[0]."</div>";
        echo "<div class=\"ldesc\">Disc failure prediction: ".$hw[1]."</div></div>";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">RAID: ".$hw[2]."</div>";
        echo "<div class=\"ldesc\">Storage battery: ".$hw[3]."</div><div class=\"ldesc\">Voltage: ".$hw[7]."</div></div>";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">Fans: ".$hw[4]."</div>";
        echo "<div class=\"ldesc\">Temperature: ".$hw[5]."</div><div class=\"ldesc\">Power supplies: ".$hw[6]."</div></div></div></td>";
    }
?>
        <td class="cpu">
<?php
    $alarm=$row_array[5];
    $warning=$row_array[6];
    $iisth=$row_array[7];
    $query="select value from data where computer_id=".$row_array[0]." and record_type=2 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec == 0) {
        echo "<div class=\"white\">CPU load (now/average):<br />no data </div>";
    }else{
        list( $value ) = $database->get_row( $query );
        $cpuload=explode('_',$value);
        if ($cpuload[1] < $row_array[6]) $bgcolor = 'green';
        if (($cpuload[1] >= $row_array[6]) && ($cpuload[1] <= $row_array[5])) $bgcolor = 'orange';
        if ($cpuload[1] > $row_array[5]) $bgcolor = 'red';
        $cpu=str_replace(" ","|",$cpuload[0]);
        echo "<div class=\"".$bgcolor."\">CPU load (now/average):<br /> ".$cpu." % / ".$cpuload[1]." % </div>"; 
    }
?>
        </td>
        <td class="ram">
<?php
    $query="select value from data where computer_id=".$row_array[0]." and record_type=3 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec == 0) {
        echo "<div class=\"white\">RAM (total/free):<br />no data </div>";
    }else{
        list( $value ) = $database->get_row( $query );
        $ram=explode('_',$value);
        $procento= $ram[0]/100;
        $stav = ($ram[0] - $ram[1]) / $procento;
        if ($stav < $row_array[6]) $bgcolor = 'green';
        if (($stav >= $row_array[6]) && ($stav <= $row_array[5])) $bgcolor = 'orange';
        if ($stav > $row_array[5]) $bgcolor = 'red';
        echo "<div class=\"".$bgcolor."\">RAM (total/free):<br /> ".$ram[0]." MB / ".$ram[1]." MB </div>";
    }
?>
        </td>
        <td class="disc">
            <table class="discmain">
                <tr class="disccontent">
<?php
    $query="select value from data where computer_id=".$row_array[0]." and record_type=1 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec == 0) {
        echo "<td class=\"discpart\"><div class=\"white\">Disc (total/free):<br />no data </div></td>";
    }else{
        list( $value ) = $database->get_row( $query );
        $discs=explode('*',$value);
        foreach( $discs as $disc ){
            $discval=explode('_',$disc);
            $procento= $discval[1]/100;
            $stav = ($discval[1] - $discval[2]) / $procento;
            if ($stav < $row_array[6]) $bgcolor = 'green';
            if (($stav >= $row_array[6]) && ($stav <= $row_array[5])) $bgcolor = 'orange';
            if ($stav > $row_array[5]) $bgcolor = 'red';
            echo "<td class=\"discpart\"><div class=\"".$bgcolor."\">Disc ".$discval[0]." (total/free):<br /> ".$discval[1]." GB / ".$discval[2]." GB </div></td>";
        }
    }
?>
                </tr>
            </table>
        </td>
<?php
    //iis data
    $query="select value from data where computer_id=".$row_array[0]." and record_type=4 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec != 0) {
        list( $value ) = $database->get_row( $query );
        $iis=explode('_',$value);
        $iistrashold=explode('_',$iisth);
        $fl=0;
        if ($iis[0] > $iistrashold[0]) $fl=1;
        $uptime=explode(' ',$iis[1]);
        $uptime  = (int) $uptime[0];
        $seconds = $uptime % 60;
        $minutes = round(($uptime / 60 ) % 60);
        $hours   = round(($uptime / (60*60)) % 24);
        $days    = round($uptime / (60*60*24)); # % 365, if you want
        $totalmethodrequest=explode(' ',$iis[2]);
        if ($totalmethodrequest[0]>$iistrashold[2]) $fl=1;
        $tracerequest=explode(' ',$iis[3]);
        $bytestotal=explode(' ',$iis[4]);
        $currentconnections=explode(' ',$iis[5]);
        if ($currentconnections[0]>$iistrashold[1]) $fl=1;
        if ($fl==0) {$bgcolor = 'green';}else{$bgcolor = 'red';}
        echo "<td class=\"disc\"><div class=\"".$bgcolor."\">IIS status:<br />";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">Response time: ".$iis[0]." sec (".$iistrashold[0]." sec)</div>";
        echo "<div class=\"ldesc\">Current connections: ".$currentconnections[0]." (".$iistrashold[1].")</div></div>";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">Total method requests: ".$totalmethodrequest[0]." /sec (".$iistrashold[2]." /sec)</div>";
        echo "<div class=\"ldesc\">Trace requests: ".$tracerequest[0]." /sec</div></div>";
        echo "<div style=\"display:block;\"><div class=\"ldesc\">Bytes total: ".formatBytes($bytestotal[0])."</div>";
        echo "<div class=\"ldesc\">Up time: ".$days." days ".$hours." hours ".$minutes." minutes.</div></div></div></td>";
    }
    //error file or folder data
    $query="select value from data where computer_id=".$row_array[0]." and record_type=5 ORDER BY time_record DESC";
    $numrec=$database->num_rows( $query );
    if ($numrec != 0) {
        list( $value ) = $database->get_row( $query );
        $fe=explode('___',$value);
        if ($fe[0]==0) {$bgcolor = 'green';$txt="not exist";}else{$bgcolor = 'red';$txt="exist";}
        $fp = str_replace("backslash","\\",$fe[1]);
        echo "<td class=\"disc\"><div class=\"".$bgcolor."\">File ".$txt."</div>";
        echo "<div class=\"ldesc\">".$fp."</div></td>";
    }        
?>        
    </tr>
<?php
}
?>
</table>
<?php
require_once( 'include/footer.php' );
?>