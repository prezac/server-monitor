$message = 'http://xxx.xxx.xxx.xxx/result.php?'
$computername = $env:computername    
$ip = [System.Net.Dns]::GetHostAddresses($computername)  | where {$_.AddressFamily -notlike "InterNetworkV6"} | foreach {echo $_.IPAddressToString }
$message = $message + 'jmeno=' + $computername + '&ip=' + $ip
$status = 0; 
omreport storage pdisk controller=0 | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&pdisk=OK'
} else {
    $message = $message + '&pdisk=ERROR'
}
$status = 0; 
omreport storage pdisk controller=0 | Where-Object {$_ -match "^failure"} | %{if($_ -notlike "*No*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&pdiskpredict=NO'
} else {
    $message = $message + '&pdiskpredict=YES'
}
$status = 0;
omreport storage vdisk controller=0 | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&vdisk=OK'
} else {
    $message = $message + '&vdisk=ERROR'
}
$status = 0;
omreport storage battery | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&storagebatery=OK'
} else {
    $message = $message + '&storagebatery=ERROR'
}
$status = 0;
omreport chassis fans | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&fans=OK'
} else {
    $message = $message + '&fans=ERROR'
}
$status = 0;
omreport chassis temps | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&temperature=OK'
} else {
    $message = $message + '&temperature=ERROR'
}
$status = 0;
omreport chassis pwrsupplies | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&pwrsupplies=OK'
} else {
    $message = $message + '&pwrsupplies=ERROR'
}
$status = 0;
omreport chassis volts | Where-Object {$_ -match "^status"} | %{if($_ -notlike "*OK*"){$status=2}}
If ($status -eq 0) {
    $message = $message + '&voltage=OK'
} else {
    $message = $message + '&voltage=ERROR'
}
$n=gwmi Win32_ComputerSystem -ComputerName $env:computername | % {$_.model}
$n=$n -replace " ",""
$message = $message + '&model=' + $n

#Get-Host | Select-Object Version
#for PS > 4
#Write-Output $message 
Invoke-WebRequest -Uri $message -Method GET

#for PS < 4
#$request = [System.Net.WebRequest]::Create($message)
#$request.Method = "GET"
#$response = $request.GetResponse()
#Write-Output $response

 