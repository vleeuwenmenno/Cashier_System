@rem ----[ This code block detects if the script is being running with admin PRIVILEGES If it isn't it pauses and then quits]-------
echo OFF
NET SESSION >nul 2>&1
IF %ERRORLEVEL% EQU 0 (
    ECHO Deleting registry keys...
    REG DELETE HKEY_CLASSES_ROOT\printhelp  /f
    REG DELETE HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run /v "CashRegister Printer Helper" /f
	REG DELETE HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\CashRegPrintHelper /f
    ECHO Deleting files...
    DEL cli.exe
    DEL NDde.dll
    DEL Newtonsoft.Json.dll
    DEL Spire.License.dll
    DEL Spire.Pdf.dll
    DEL ui.exe
    DEL libeay32.dll 
    DEL libstdc++-6.dll
    DEL PrintHtml.exe
    DEL QtGui4.dll
    DEL QtWebKit4.dll
    DEL libgcc_s_dw2-1.dll
    DEL libwinpthread-1.dll
    DEL QtCore4.dll
    DEL QtNetwork4.dll 
    DEL ssleay32.dll
    echo msgbox "Uninstall success!" > %tmp%\tmp.vbs
    cscript /nologo %tmp%\tmp.vbs
    del %tmp%\tmp.vbs
    start /b "" cmd /c del "%~f0"&exit /b
	(goto) 2>nul & del "%~f0"
	EXIT
) ELSE (
   echo ######## ########  ########   #######  ########  
   echo ##       ##     ## ##     ## ##     ## ##     ## 
   echo ##       ##     ## ##     ## ##     ## ##     ## 
   echo ######   ########  ########  ##     ## ########  
   echo ##       ##   ##   ##   ##   ##     ## ##   ##   
   echo ##       ##    ##  ##    ##  ##     ## ##    ##  
   echo ######## ##     ## ##     ##  #######  ##     ## 
   echo.
   echo.
   echo ####### ERROR: ADMINISTRATOR PRIVILEGES REQUIRED #########
   echo This script must be run as administrator to work properly!  
   echo If you're seeing this after clicking on a start menu icon, then right click on the shortcut and select "Run As Administrator".
   echo ##########################################################
   echo.
   PAUSE
   EXIT /B 1
)
@echo ON