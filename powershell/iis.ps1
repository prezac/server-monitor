$message = 'http://xxx.xxx.xxx.xxx/result.php?'
$computername = $env:computername    
$ip = [System.Net.Dns]::GetHostAddresses($computername)  | where {$_.AddressFamily -notlike "InterNetworkV6"} | foreach {echo $_.IPAddressToString }
$message = $message + 'jmeno=' + $computername + '&ip=' + $ip
$url = 'http:\\localhost'
$timeTaken = Measure-Command -Expression {
	#for PS > 4
	#$site = Invoke-WebRequest -Uri $url

	#for PS < 4
	$request = [System.Net.WebRequest]::Create($url)
	$site = $request.GetResponse()
}
$responsetime = [Math]::Round($timeTaken.TotalSeconds, 4)
$uptime = Get-WmiObject -class Win32_PerfRawData_W3SVC_WebService | select-object -expand ServiceUptime
$totalmethodrequestspersec=Get-WmiObject -class Win32_PerfRawData_W3SVC_WebService | select-object -expand TotalMethodRequestsPerSec
$tracerequestspersec=Get-WmiObject -class Win32_PerfRawData_W3SVC_WebService | select-object -expand TraceRequestsPerSec
$bytestotalpersec=Get-WmiObject -class Win32_PerfRawData_W3SVC_WebService | select-object -expand BytesTotalPerSec
$currentconnections=Get-wmiObject -class Win32_PerfRawData_W3SVC_WebService | select-object -expand CurrentConnections
$message = $message + '&iisdata=' + $responsetime + "_" + $uptime + "_" + $totalmethodrequestspersec +"_" + $tracerequestspersec + "_" + $bytestotalpersec + "_" + $currentconnections

#for PS > 4
#Write-Output $message 
#Invoke-WebRequest -Uri $message -Method GET

#for PS < 4
$request = [System.Net.WebRequest]::Create($message)
$request.Method = "GET"
$response = $request.GetResponse()
#Write-Output $response