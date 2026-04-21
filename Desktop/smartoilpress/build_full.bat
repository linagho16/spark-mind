@echo off
setlocal enabledelayedexpansion

REM Full PATH configuration
set PATH=C:\Qt\Tools\mingw1120_64\bin;C:\Qt\6.7.3\mingw_64\bin;C:\Qt\Tools\mingw1120_64\libexec\gcc\x86_64-w64-mingw32\11.2.0;%PATH%
set CPATH=C:\Qt\Tools\mingw1120_64\include;C:\Qt\Tools\mingw1120_64\lib\gcc\x86_64-w64-mingw32\11.2.0\include;%CPATH%
set CPLUS_INCLUDE_PATH=C:\Qt\Tools\mingw1120_64\include;C:\Qt\Tools\mingw1120_64\lib\gcc\x86_64-w64-mingw32\11.2.0\include;C:\Qt\Tools\mingw1120_64\lib\gcc\x86_64-w64-mingw32\11.2.0\include\c++;%CPLUS_INCLUDE_PATH%
set GCC_EXEC_PREFIX=C:\Qt\Tools\mingw1120_64\libexec\gcc\x86_64-w64-mingw32\11.2.0\

echo ===========================================================
echo PATH Configuration for Qt 6.7.3 MinGW Compilation
echo ===========================================================
echo MinGW bin:     C:\Qt\Tools\mingw1120_64\bin
echo Qt bin:        C:\Qt\6.7.3\mingw_64\bin
echo GCC prefix:    %GCC_EXEC_PREFIX%
echo ===========================================================

REM Test if g++ works
echo.
echo Testing g++ compiler...
g++ --version | findstr mingw
if errorlevel 1 (
    echo ERROR: g++ not working properly!
    exit /b 1
)

REM Clean and rebuild
cd /d "C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress\build\Desktop_Qt_6_7_3_MinGW_64_bit-Debug"

echo.
echo Executing mingw32-make...
call mingw32-make

echo.
if %errorlevel% EQU 0 (
    echo BUILD SUCCESSFUL!
    echo Executable location: "C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress\build\Desktop_Qt_6_7_3_MinGW_64_bit-Debug\debug\smartoilpress.exe"
) else (
    echo BUILD FAILED with error code: %errorlevel%
)

pause
