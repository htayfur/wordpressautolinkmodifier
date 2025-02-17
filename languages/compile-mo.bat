@echo off
setlocal EnableDelayedExpansion

echo Compiling MO files from PO files...

:: Liste tüm .po dosyalarını
for %%f in (*.po) do (
    echo Processing: %%f
    
    :: .po uzantısını kaldır ve .mo ile değiştir
    set "mofile=%%~nf.mo"
    
    :: msgfmt.exe kullanarak MO dosyasını oluştur
    if exist "C:\Program Files\GnuWin32\bin\msgfmt.exe" (
        "C:\Program Files\GnuWin32\bin\msgfmt.exe" -o "!mofile!" "%%f"
    ) else if exist "C:\Program Files (x86)\GnuWin32\bin\msgfmt.exe" (
        "C:\Program Files (x86)\GnuWin32\bin\msgfmt.exe" -o "!mofile!" "%%f"
    ) else (
        echo msgfmt.exe not found. Using PowerShell alternative...
        powershell -Command ^
        "$content = Get-Content '%%f' -Raw; ^
        $bytes = [System.Text.Encoding]::UTF8.GetBytes($content); ^
        [System.IO.File]::WriteAllBytes('!mofile!', $bytes)"
    )
    
    if exist "!mofile!" (
        echo Created: !mofile!
    ) else (
        echo Failed to create: !mofile!
    )
)

echo.
echo All MO files have been compiled.
echo.

:: Dosyaları listele
dir *.mo

pause