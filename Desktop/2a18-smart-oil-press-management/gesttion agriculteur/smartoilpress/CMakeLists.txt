cmake_minimum_required(VERSION 3.21)

project(SmartOilPress LANGUAGES CXX)

set(CMAKE_AUTOMOC ON)
set(CMAKE_AUTORCC ON)
set(CMAKE_AUTOUIC ON)
set(CMAKE_CXX_STANDARD 17)
set(CMAKE_CXX_STANDARD_REQUIRED ON)

# Find Qt packages
find_package(Qt6 COMPONENTS Core Gui Widgets LinguistTools REQUIRED)

# Set source files
set(PROJECT_SOURCES
    main.cpp
    mainwindow.h
    mainwindow.cpp
    mainwindow.ui
    agriculteur.h
    agriculteur.cpp
    culture.h
    culture.cpp
    historique.h
    historique.cpp
    smartoilpress_fr_FR.ts
)

# Create executable
add_executable(smartoilpress ${PROJECT_SOURCES})

# Link Qt libraries
target_link_libraries(smartoilpress PRIVATE 
    Qt6::Core
    Qt6::Gui
    Qt6::Widgets
)

# Set output directory
set_target_properties(smartoilpress PROPERTIES
    RUNTIME_OUTPUT_DIRECTORY ${CMAKE_BINARY_DIR}/debug
)
