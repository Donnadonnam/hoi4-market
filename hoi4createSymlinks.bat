::===============================================================
:: Sets up Symlinks for needed folder
:: Todo: make more dynamic!
::===============================================================
mklink /J "%~dp0_steam-workshop" "G:\SteamLibrary\steamapps\workshop\content\394360"
mklink /J "%~dp0_mod-folder" "%UserProfile%\Documents\Paradox Interactive\Hearts of Iron IV\mod"
mklink /J "%~dp0_logs" "%UserProfile%\Documents\Paradox Interactive\Hearts of Iron IV\logs"

pause