Option Explicit

Dim objWshShell
Set objWshShell = WScript.CreateObject("WScript.Shell")

Dim intProcID
intProcID = GetProcID("Google Assistant.exe")

If intProcID = 0 Then
    MsgBox "Google Assistant not found.", vbInformation, "Google Assistant WakeUp"
    WScript.Quit
End If

If objWshShell.AppActivate("Google Assistant Unofficial Client") Then
    objWshShell.SendKeys "^{ENTER}"
Else
    MsgBox "Failed to WakeUp Google Assistant.", vbInformation, "AutoPageant"
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