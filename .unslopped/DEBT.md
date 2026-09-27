# Debt log

Known deviations we are deliberately not fixing in the change where they were found.
One line each, logged on the spot instead of opening a work item. Sweep the cleanup
items in one batch at a quiet moment; accepted items stay, with the reason, so nobody
re-litigates them.

Categories: cleanup (mechanical, safe to batch), pattern (a wrong shape new code would
copy; fix it before it spreads), soon (costs someone time while it exists), accepted
(evaluated and staying as is).
- [soon] config/database.php reads PDO::MYSQL_ATTR_SSL_CA, deprecated in PHP 8.5; every test prints a deprecation notice. Switch to Pdo\Mysql::ATTR_SSL_CA (config/database.php) :: logged 2026-09-26, cycle 20260926-71d6ca
- [cleanup] Deleting a room leaves its photo files in storage/app/public/room-photos; delete them with the room (app/Http/Controllers/RoomController.php) :: logged 2026-09-27, cycle 20260927-cc508e
- [soon] config/app.php timezone is UTC, so staff see UTC times (dashboard Updated at, check in set to now); set APP_TIMEZONE to the hotel's zone after confirming with the owner (config/app.php) :: logged 2026-09-27, cycle 20260927-104733
