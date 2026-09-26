# Debt log

Known deviations we are deliberately not fixing in the change where they were found.
One line each, logged on the spot instead of opening a work item. Sweep the cleanup
items in one batch at a quiet moment; accepted items stay, with the reason, so nobody
re-litigates them.

Categories: cleanup (mechanical, safe to batch), pattern (a wrong shape new code would
copy; fix it before it spreads), soon (costs someone time while it exists), accepted
(evaluated and staying as is).
- [soon] config/database.php reads PDO::MYSQL_ATTR_SSL_CA, deprecated in PHP 8.5; every test prints a deprecation notice. Switch to Pdo\Mysql::ATTR_SSL_CA (config/database.php) :: logged 2026-09-26, cycle 20260926-71d6ca
