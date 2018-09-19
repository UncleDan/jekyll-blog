---
id: 1886
title: Script To Create All In One Microsoft Windows Seven (7) Installation Media
date: 2014-09-24T19:00:46+00:00
author: Daniele Lolli (UncleDan)
layout: post
guid: https://www.uncledan.it/?p=1886
permalink: /2014-09-24-script-to-create-all-in-one-microsoft-windows-seven-7-installation-media.html
mytory_md_visits_count:
  - "262"
categories:
  - PC
  - Tech
tags:
  - "7"
  - all-in-one
  - architecture
  - automate
  - deploy
  - installation media
  - multi
  - multi-architecture
  - script
  - seven
  - windows
---
Being a power user (or something less) I always wondered it it was possible to have a single media to install different versions of Microsoft Windows 7 (either flavour or architecture). The answer is yes and I wrote a little script to automate the job of &#8220;mixing&#8221; the installation media.Obviously no licence infringment is intended: you will need to own all the licences, but you can carry with you only one DVD!

Here it is:

`@echo off<br />
cls<br />
echo ************************************************************************<br />
echo * QUICK AND DIRTY MICROSOFT WINDOWS SEVEN ALL-IN-ONE-INSTALLER CREATOR *<br />
echo * by UncleDan 24/09/2014 *<br />
echo ************************************************************************<br />
echo You will need:<br />
echo - WAIK 3.0<br />
echo http://www.microsoft.com/en-us/download/details.aspx?id=5753<br />
echo - WAIK 3.1 Supplement<br />
echo http://www.microsoft.com/en-us/download/details.aspx?id=5188<br />
echo - 7-Zip<br />
echo http://www.7-zip.org/<br />
echo - PATIENCE!<br />
echo Based on:<br />
echo - http://technet.microsoft.com/en-us/library/dd744261(v=ws.10).aspx<br />
echo.<br />
echo All trademarks mentioned belong to their owners; third party brands,<br />
echo product names, trade names, corporate names and company names mentioned<br />
echo may be trademarks of their respective owners or registered trademarks<br />
echo of other companies and are used for purposes of explanation and to the<br />
echo owner's benefit, without implying a violation of copyright law.<br />
echo.<br />
echo Copyright (c) 2014, Daniele Lolli a.k.a. UncleDan ^<uncledan@uncledan.it^><br />
echo.<br />
echo Permission to use, copy, modify, and/or distribute this software<br />
echo for any purpose with or without fee is hereby granted, provided<br />
echo that the above copyright notice and this permission notice<br />
echo appear in all copies.<br />
echo.<br />
echo THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES<br />
echo WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF<br />
echo MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR<br />
echo ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES<br />
echo WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN<br />
echo ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF<br />
echo OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE<br />
echo.<br />
pause</p>
<p>rem VARIABLES</p>
<p>set SOURCE32=I:\Microsoft Windows 7 Professional (i386).iso<br />
set SOURCE64=I:\Microsoft Windows 7 Professional (amd64).iso<br />
set DESTINATION=J:\WIN7_UNI_x86-x64<br />
set SEVENZIPPATH=C:\Program Files\7-Zip</p>
<p>rem SCRIPT</p>
<p>echo.<br />
echo Extracting 32-bit image...<br />
echo.<br />
"%SEVENZIPPATH%\7z.exe" x "%SOURCE32%" -o"%DESTINATION%"<br />
md %DESTINATION%\temp<br />
move %DESTINATION%\sources\install.wim %DESTINATION%\temp\x86.wim</p>
<p>echo.<br />
echo Extracting 64-bit image...<br />
echo.<br />
"%SEVENZIPPATH%\7z.exe" x "%SOURCE64%" -o"%DESTINATION%" install.wim -r<br />
move %DESTINATION%\sources\install.wim %DESTINATION%\temp\x64.wim</p>
<p>echo.<br />
echo Mixing...<br />
echo.<br />
imagex /EXPORT "%DESTINATION%\temp\x86.wim" 5 "%DESTINATION%\sources\install.wim" "Windows 7 Ultimate x86"<br />
imagex /EXPORT "%DESTINATION%\temp\x64.wim" 4 "%DESTINATION%\sources\install.wim" "Windows 7 Ultimate x64"<br />
imagex /EXPORT "%DESTINATION%\temp\x86.wim" 4 "%DESTINATION%\sources\install.wim" "Windows 7 Professional x86"<br />
imagex /EXPORT "%DESTINATION%\temp\x64.wim" 3 "%DESTINATION%\sources\install.wim" "Windows 7 Professional x64"<br />
imagex /EXPORT "%DESTINATION%\temp\x86.wim" 3 "%DESTINATION%\sources\install.wim" "Windows 7 Home Premium x86"<br />
imagex /EXPORT "%DESTINATION%\temp\x64.wim" 2 "%DESTINATION%\sources\install.wim" "Windows 7 Home Premium x64"<br />
imagex /EXPORT "%DESTINATION%\temp\x86.wim" 2 "%DESTINATION%\sources\install.wim" "Windows 7 Home Basic x86"<br />
imagex /EXPORT "%DESTINATION%\temp\x64.wim" 1 "%DESTINATION%\sources\install.wim" "Windows 7 Home Basic x64"<br />
imagex /EXPORT "%DESTINATION%\temp\x86.wim" 1 "%DESTINATION%\sources\install.wim" "Windows 7 Starter x86"</p>
<p>echo.<br />
echo Doing latest tricks...<br />
echo.<br />
del "%DESTINATION%\sources\ei.cfg"<br />
del "%DESTINATION%\sources\cversion.ini"<br />
rd /S /Q "%DESTINATION%\temp"<br />
echo CREATED WITH:>"%DESTINATION%\readme.txt"<br />
echo.>>"%DESTINATION%\readme.txt"<br />
type %0>>"%DESTINATION%\readme.txt"</p>
<p>echo.<br />
echo Packaging ISO image...<br />
echo.<br />
oscdimg -n -m -b"%DESTINATION%\boot\etfsboot.com" "%DESTINATION%" "%DESTINATION%.iso"</p>
<p>echo.<br />
echo Cleaning up...<br />
echo.<br />
rd /S /Q %DESTINATION%</p>
<p>echo.<br />
echo ALL DONE.<br />
echo.<br />
pause<br />
exit<br />
`