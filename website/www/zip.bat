@echo on
cd /D "%~dp0"
tar.exe -a -cf www.zip --exclude www.zip --exclude .hg --exclude .tmp.* --exclude nbproject --exclude settings.conf --exclude .hgignore --exclude zip.bat *