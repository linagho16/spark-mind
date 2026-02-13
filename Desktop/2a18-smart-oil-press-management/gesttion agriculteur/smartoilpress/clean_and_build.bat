@echo off
REM Navigate to project directory
cd /d "C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress"

REM Clean previous builds
echo Cleaning previous builds...
if exist "build" rmdir /s /q build >nul 2>&1
if exist "debug" rmdir /s /q debug >nul 2>&1
if exist "release" rmdir /s /q release >nul 2>&1
if exist "*.o" del /q *.o >nul 2>&1
if exist "Makefile" del /q Makefile >nul 2>&1

REM Set environment variables for Qt 6.7.3 with MinGW
set PATH=C:\Qt\6.7.3\mingw_64\bin;C:\Qt\Tools\mingw1120_64\bin;%PATH%

REM Run qmake to generate Makefile
echo.
echo Configuring project with qmake...
C:\Qt\6.7.3\mingw_64\bin\qmake.exe smartoilpress.pro -spec win32-g++

if %errorlevel% NEQ 0 (
    echo ERROR: qmake failed!
    pause
    exit /b 1
)

REM Compile
echo.
echo Compiling project...
mingw32-make clean
mingw32-make

if %errorlevel% NEQ 0 (
    echo ERROR: Compilation failed!
    pause
    exit /b 1
)

echo.
echo Build completed successfully!
echo Executable should be in: %CD%\debug\smartoilpress.exe or release\smartoilpress.exe
pause
