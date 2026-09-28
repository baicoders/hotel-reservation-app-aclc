---
name: gaps-a-reservation-id-and-date-guest-and-room-filters-screen
title: Gaps A: reservation ID and date, guest and room filters (Screen 9); dashboard latest rooms and Refresh (Screen 4)
created: 2026-09-27T03:06:44.787Z
updated: 2026-09-27T03:06:44.787Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-104733
tags: gaps, reservation, id, date, guest, room, filters, screen, app, resources, tests
---
# Gaps A: reservation ID and date, guest and room filters (Screen 9); dashboard latest rooms and Refresh (Screen 4)

## When to use
Requests like:
- "Gaps A: reservation ID and date, guest and room filters (Screen 9); dashboard latest rooms and Refresh (Screen 4)"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/GuestController.php
- app/Http/Controllers/ManageController.php
- app/Http/Controllers/ReservationController.php
- app/Http/Controllers/RoomController.php
- resources/views/staff/dashboard.blade.php
- resources/views/staff/reservations/list.blade.php
- tests/Feature/DashboardTest.php
- tests/Feature/GuestListTest.php
- tests/Feature/ReservationPagesTest.php
- tests/Feature/RoomListTest.php

Acceptance criteria that passed:
- Each reservation row shows its ID as "#{id}"
- ?guest=lena lists only reservations whose guest name or email contains "lena"
- ?room=305 lists only reservations for rooms whose number contains "305"
- ?date=2026-10-05 lists only reservations with check in on or before and check out on or after that date
- ?date=not-a-date lists every reservation and returns 200
- Filters combine, stay filled in the form, and survive the pager
- Typing % or _ into the reservations, rooms or guests search matches those characters literally
- With 7 rooms the dashboard lists the 5 newest under Latest rooms, with a room count of 7
- The dashboard has a Refresh link to /dashboard and shows the time the page loaded
- At 390px both pages have no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- The dashboard overflowed by 1px at 390px because grid columns kept their content's minimum width; `min-w-0` on grid children fixes it.
- Review found unescaped LIKE wildcards in three searches and a heading level issue; both fixed, the wildcard fix with red tests in each list.
- Staff times display in UTC (app timezone); logged as debt for the owner to confirm the hotel's zone.
