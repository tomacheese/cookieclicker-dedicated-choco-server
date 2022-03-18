Option Explicit

Dim objWshShell
Set objWshShell = WScript.CreateObject("WScript.Shell")

Dim intProcID
intProcID = GetProcID("Cookie Clicker.exe")

If intProcID = 0 Then
    MsgBox "Cookie Clicker not found.", vbInformation, "Cookie Clicker Minimalizer"
    WScript.Quit
End If

If objWshShell.AppActivate(intProcID) Then
    objWshShell.SendKeys "% "
    WScript.Sleep 500
    objWshShell.SendKeys "n"
Else
    WScript.Quit
End If

WScript.Sleep 1000

Function GetProcID(ProcessName)
    Dim Service
    Dim QfeSet
    Dim Qfe
    Dim intProcID

    Set Service = WScript.CreateObject("WbemScripting.SWbemLocator").ConnectServer
    Set QfeSet = Service.ExecQuery("Select * From Win32_Process Where Caption='" & ProcessName & "'")

    intProcID = 0
    For Each Qfe in QfeSet
        intProcID = Qfe.ProcessId
        Exit For
    Next
    GetProcID = intProcID
End Function