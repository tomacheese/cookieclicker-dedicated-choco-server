@echo off
:loop
php checkInGame.php
timeout /T 300 /NOBREAK
goto loop