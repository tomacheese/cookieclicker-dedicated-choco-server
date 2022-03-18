Set shell = WScript.CreateObject("WScript.Shell")

startupPath = shell.SpecialFolders("Startup")
fil = startupPath + "\JQuake.lnk"

Set shortCut = shell.CreateShortcut(fil)
curDir = objShell.CurrentDirectory
shortCut.TargetPath = curDir + "\JQuake.exe"
shortCut.Save
