@echo off
REM Set full PATH  for MinGW firt
set MINGW_PATH=C:\Qt\Tools\mingw1120_64
set CMAKE_PATH=C:\Qt\Tools\CMake_64\bin\cmake.exe

REM Set environment variables
set PATH=%MINGW_PATH%\bin;%MINGW_PATH%\libexec\gcc\x86_64-w64-mingw32\11.2.0;C:\Qt\6.7.3\mingw_64\bin;%PATH%
set CPLUS_INCLUDE_PATH=%MINGW_PATH%\include;%MINGW_PATH%\lib\gcc\x86_64-w64-mingw32\11.2.0\include;%MINGW_PATH%\lib\gcc\x86_64-w64-mingw32\11.2.0\include\c++;%CPLUS_INCLUDE_PATH%

cd /d "C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress"

REM Create build directory
if not exist "build_cmake" mkdir build_cmake
cd build_cmake

REM Run CMake with mingw32-make generator
echo Configuring CMake with MinGW Makefiles...
"%CMAKE_PATH%" .. -G "MinGW Makefiles" -DCMAKE_PREFIX_PATH="C:\Qt\6.7.3\mingw_64" -DCMAKE_BUILD_TYPE=Debug

if %errorlevel% EQU 0 (
    echo.
    echo Running mingw32-make to build...
    call mingw32-make
    
    if %errorlevel% EQU 0 (
        echo.
        echo BUILD SUCCESSFUL!
        echo Executable at: C:\Users\USER\Desktop\gesttion agriculteur\smartoilpress\build_cmake\smartoilpress.exe
    ) else (
        echo BUILD FAILED during make!
    )
) else (
    echo CMAKE CONFIGURATION FAILED!
)

pause

