set emptyFolder="c:\zzz"

set deployFolder="c:\wamp\www\fedportal-deploy\qportal"

REM folders and files to be deleted
set deployFolderGit="c:\wamp\www\fedportal-deploy\qportal\.git"
set deployFolderIdea="c:\wamp\www\fedportal-deploy\qportal\.idea"
set deployFolderPhotoFolder="c:\wamp\www\fedportal-deploy\qportal\photo_files"
set deployFolderNodeFolder="c:\wamp\www\fedportal-deploy\qportal\node_modules"
set deployFolderLibFolder="c:\wamp\www\fedportal-deploy\qportal\libs"
set deployFolderImgFolder="c:\wamp\www\fedportal-deploy\qportal\img"
set deployFolderVendorFolder="c:\wamp\www\fedportal-deploy\qportal\vendor"
set deployFolderJsFolder="c:\wamp\www\fedportal-deploy\qportal\js"
set deployFolderTcPdfFolder="c:\wamp\www\fedportal-deploy\qportal\helpers\tcpdf"
set deployFolderLogFile="c:\wamp\www\fedportal-deploy\qportal\out_logs\out_log.log"
REM end folders and files to be deleted

set sourceFolder="c:\wamp\www\fedportal"

robocopy %sourceFolder% %deployFolder% /mir

robocopy %emptyFolder% %deployFolderGit% /s /mir
rmdir %deployFolderGit% /s /q

robocopy %emptyFolder% %deployFolderIdea% /s /mir
rmdir %deployFolderIdea% /s /q

robocopy %emptyFolder% %deployFolderPhotoFolder% /s /mir
rmdir %deployFolderPhotoFolder% /s /q

robocopy %emptyFolder% %deployFolderNodeFolder% /s /mir
rmdir %deployFolderNodeFolder% /s /q

robocopy %emptyFolder% %deployFolderLibFolder% /s /mir
rmdir %deployFolderLibFolder% /s /q

robocopy %emptyFolder% %deployFolderImgFolder% /s /mir
rmdir %deployFolderImgFolder% /s /q

robocopy %emptyFolder% %deployFolderVendorFolder% /s /mir
rmdir %deployFolderVendorFolder% /s /q

robocopy %emptyFolder% %deployFolderJsFolder% /s /mir
rmdir %deployFolderJsFolder% /s /q

robocopy %emptyFolder% %deployFolderTcPdfFolder% /s /mir
rmdir %deployFolderTcPdfFolder% /s /q

del /f /q %deployFolderLogFile%

7z a -y  %deployFolder%.zip %deployFolder%
