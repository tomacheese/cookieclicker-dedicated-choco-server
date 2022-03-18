@echo off

start "web-controller-server" /D "web-controller-server" "start.bat"
start "auto-start-clicker" /D "auto-start-clicker" "checkInGame.bat"
start "JQuake-Installer" /D "JQuake-Installer" "installer.bat"
