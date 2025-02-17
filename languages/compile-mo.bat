@echo off
setlocal EnableDelayedExpansion

echo Compiling MO files from PO files...

:: Loop through all PO files
for %%f in (*.po) do (
    echo Processing: %%f
    
    :: Get file name without extension
    set "basename=%%~nf"
    
    :: Create MO file using PowerShell
    powershell -Command ^
        "$content = Get-Content '%%f' -Raw; ^
         $bytes = [System.Text.Encoding]::UTF8.GetBytes($content); ^
         Set-Content -Path '!basename!.mo' -Value $bytes -Encoding Byte"
    
    if exist "!basename!.mo" (
        echo Created: !basename!.mo
    ) else (
        echo Failed to create: !basename!.mo
    )
)

echo.
echo All MO files have been compiled.
echo.

:: List all MO files
dir *.mo

pause