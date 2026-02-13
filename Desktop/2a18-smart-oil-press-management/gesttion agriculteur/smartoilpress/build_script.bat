@echo off
REM Set the PATH to include MinGW and other necessary directories
set PATH=C:\Qt\Tools\mingw1120_64\bin;C:\Qt\Tools\mingw1120_64\lib;C:\Qt\Tools\mingw1120_64\libexec\gcc\x86_64-w64-mingw32\11.2.0;C:\Qt\6.7.3\mingw_64\bin;%PATH%

REM Set GCC_EXEC_PREFIX to help g++ find cc1plus
set GCC_EXEC_PREFIX=C:\Qt\Tools\mingw1120_64\libexec\gcc\x86_64-w64-mingw32\11.2.0\

REM Navigate to the build directory
cd /d "C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress\build\Desktop_Qt_6_7_3_MinGW_64_bit-Debug"

REM Clean previous build
echo Cleaning previous build...
mingw32-make clean

REM Build the project
echo Building project...
mingw32-make

REM Check if build was successful
if %ERRORLEVEL% EQU 0 (
    echo Build successful!
    echo Executable is at: C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress\build\Desktop_Qt_6_7_3_MinGW_64_bit-Debug\debug\smartoilpress.exe
) else (
    echo Build failed with exit code %ERRORLEVEL%
)
