$message = 'http://xxx.xxx.xxx.xxx/result.php?'
$computername = $env:computername    
$ip = [System.Net.Dns]::GetHostAddresses($computername)  | where {$_.AddressFamily -notlike "InterNetworkV6"} | foreach {echo $_.IPAddressToString }
$message = $message + 'jmeno=' + $computername + '&ip=' + $ip
$disky = Get-WmiObject -Class win32_logicaldisk -ComputerName $computername 
$diskmessage = ''
$disky | ForEach-Object -Process {
        if (([math]::truncate($_.Size /1GB)) -gt 0) {
            $diskmessage = $diskmessage + ($_.DeviceID) + '_' + ([math]::truncate($_.Size /1GB)) + '_' + ([math]::truncate($_.FreeSpace /1GB)) +'*'
                                 }
        } 
$diskmessage = $diskmessage -replace '.$'
$message = $message + '&disky=' + $diskmessage
$totalRam = Get-WmiObject -Class win32_operatingsystem -ComputerName $computername | % {[math]::truncate($_.TotalVisibleMemorySize /1kB)}
$availRam = Get-WmiObject -Class win32_operatingsystem -ComputerName $computername | % {[math]::truncate($_.FreePhysicalMemory /1kB)}
$message = $message + '&ram=' + $totalRam + '_' + $availRam
$cpuload = Get-WmiObject Win32_Processor | % {$_.LoadPercentage}  
$cpuloadaverage = Get-WmiObject Win32_Processor | Measure-Object -Property LoadPercentage -Average | % {$_.Average}
$message = $message + '&cpuload=' + $cpuload + '_' + $cpuloadaverage

#for PS > 4
#Write-Output $message 
#Invoke-WebRequest -Uri $message -Method GET

#for PS < 4
$request = [System.Net.WebRequest]::Create($message)
$request.Method = "GET"
$response = $request.GetResponse()
#Write-Output $response