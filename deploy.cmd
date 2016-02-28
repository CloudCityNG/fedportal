open fedsdimy:@fed_ADMIN100@208.91.199.233
cd public_html/qportal
lcd c:/wamp/www/fedportal
synchronize remote -delete admin_academics admin_academics
synchronize remote -delete helpers helpers
synchronize remote -delete img img
synchronize remote -delete includes includes
synchronize remote -delete js js
synchronize remote -delete libs libs
synchronize remote -delete migrations migrations
synchronize remote -delete student_portal student_portal
synchronize remote -delete vendor vendor
exit
