$message = 'http://xxx.xxx.xxx.xxx/result.php?'
$computername = $env:computername    
$ip = [System.Net.Dns]::GetHostAddresses($computername)  | where {$_.AddressFamily -notlike "InterNetworkV6"} | foreach {echo $_.IPAddressToString }
$message = $message + 'jmeno=' + $computername + '&ip=' + $ip
$message = $message + '&pdisk=NON'
$message = $message + '&pdiskpredict=NON'
$message = $message + '&vdisk=NON'
$message = $message + '&storagebatery=NON'
$message = $message + '&fans=NON'
$status = Get-ComputerInfo | % {$_.csthermalstate} 
If ($status -eq 'Safe') {
    $message = $message + '&temperature=OK'
} else {
    $message = $message + '&temperature=ERROR'
}
$message = $message + '&voltage=NON'
$message = $message + '&pwrsupplies=NON'
$n=Get-ComputerInfo | % {$_.csmodel}
$n=$n -replace " ",""
$message = $message + '&model=' + $n

#for PS > 4
#Write-Output $message 
Invoke-WebRequest -Uri $message -Method GET

#for PS < 4
#$request = [System.Net.WebRequest]::Create($message)
#$request.Method = "GET"
#$response = $request.GetResponse()
#Write-Output $response