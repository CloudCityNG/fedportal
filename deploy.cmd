set emptyFolder="c:\zzz"

set deployFolder="c:\wamp\www\fedportal-deploy\qportal"

REM folders and files to be deleted
set deployFolderGit="c:\wamp\www\fedportal-deploy\qportal\.git"
set deployFolderLibFolder="c:\wamp\www\fedportal-deploy\qportal\libs"
set deployFolderJsFolder="c:\wamp\www\fedportal-deploy\qportal\js"
set deployFolderTcPdfFolder="c:\wamp\www\fedportal-deploy\qportal\helpers\tcpdf"
REM end folders and files to be deleted

set sourceFolder="c:\wamp\www\fedportal-deploy\original"

robocopy %sourceFolder% %deployFolder% /mir

robocopy %emptyFolder% %deployFolderGit% /s /mir
rmdir %deployFolderGit% /s /q

robocopy %emptyFolder% %deployFolderLibFolder% /s /mir
rmdir %deployFolderLibFolder% /s /q

robocopy %emptyFolder% %deployFolderTcPdfFolder% /s /mir
rmdir %deployFolderTcPdfFolder% /s /q

robocopy %emptyFolder% %deployFolderJsFolder% /s /mir
rmdir %deployFolderJsFolder% /s /q

del %deployFolder%\.bowerrc
del %deployFolder%\.gitignore
del %deployFolder%\.jshintrc
del %deployFolder%\bower.json
del %deployFolder%\deploy.cmd
del %deployFolder%\gulpfile.js
del %deployFolder%\package.json
del %deployFolder%\README.md
del %deployFolder%\root.config.js
del %deployFolder%\..\qportal.zip

7z a -y  %deployFolder%.zip %deployFolder%
