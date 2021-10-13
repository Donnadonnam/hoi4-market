::===============================================================
:: Sets up Symlinks for needed folder
:: Todo: make mor dynamic!
::===============================================================
mklink /J "%~dp0steam-workshop" "G:\SteamLibrary\steamapps\workshop\content\394360"

mklink /J "%UserProfile%\Documents\Paradox Interactive\Hearts of Iron IV\mod\market" "%~dp0release\basic"
mklink /J "%UserProfile%\Documents\Paradox Interactive\Hearts of Iron IV\mod\market-thegreatwar" "%~dp0release\thegreatwar"

pause