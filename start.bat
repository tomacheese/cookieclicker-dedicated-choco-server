@echo off
git reset --hard
git pull

start "web-controller-server" /D "web-controller-server" /MIN "start.bat"
start "google-assistant-listening" /D "google-assistant-listening" /MIN "start.bat"
start "auto-start-clicker" /D "auto-start-clicker" /MIN "checkInGame.bat"
start "JQuake-Installer" /D "jquake" /MIN "installer.bat"
start "VbWinPos" /D "VbWinPos" /MIN "VbWinPos.exe"
