@echo off
git pull

start "web-controller-server" /D "web-controller-server" "start.bat"
start "google-assistant-listening" /D "google-assistant-listening" "start.bat"
start "auto-start-clicker" /D "auto-start-clicker" "checkInGame.bat"
start "JQuake-Installer" /D "jquake" "installer.bat"
