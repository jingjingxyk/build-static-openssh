:: @echo off

setlocal


echo %~dp0
cd /d %~dp0
cd /d ..\..\..\..\

set "__PROJECT__=%cd%"
echo %cd%


if not exist "%__PROJECT__%\var\windows-build-deps\php-nts-Win32-x64.zip" (
    echo "windows php runtime no found "
    echo "php runtime downloading ..."
    call "%__PROJECT__%\sapi\quickstart\windows\native-build\windows-init-download.bat"
     echo "php runtime downloaded"
)

call "%__PROJECT__%\sapi\quickstart\windows\native-build\windows-init-install.bat"
:: call "%__PROJECT__%\sapi\quickstart\windows\native-build\windows-init-install-vs-tools.bat"

endlocal
