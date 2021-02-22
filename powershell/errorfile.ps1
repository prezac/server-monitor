$message = 'http://xxx.xxx.xxx.xxx/result.php?'
$computername = $env:computername    
$ip = [System.Net.Dns]::GetHostAddresses($computername)  | where {$_.AddressFamily -notlike "InterNetworkV6"} | foreach {echo 
$_.IPAddressToString }
$message = $message + 'jmeno=' + $computername + '&ip=' + $ip
$file = "C:\directory\subdirectory\*"
if (!(Test-Path $file)) {
	$fe='0'
}else{
	$fe='1'
}
$fp=$file.Replace("\","backslash")
$message = $message + '&filedata=' + $fe + '___' + $fp
#for PS > 4
#Write-Output $message 
#Invoke-WebRequest -Uri $message -Method GET

#for PS < 4
$request = [System.Net.WebRequest]::Create($message)
$request.Method = "GET"
$response = $request.GetResponse()
#Write-Output $response



