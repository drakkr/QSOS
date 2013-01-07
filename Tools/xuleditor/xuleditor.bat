@ECHO OFF

IF ""=="%1" GOTO NODEBUG
IF "%1"=="debug" GOTO DEBUG

echo "usage: xuleditor [debug]"
GOTO END

:NODEBUG
start xulrunner.exe application.ini /file:"%1"
GOTO END

:DEBUG
start xulrunner.exe application.ini -console -jsconsole
GOTO END

:END