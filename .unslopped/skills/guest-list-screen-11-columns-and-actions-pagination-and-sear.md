---
name: guest-list-screen-11-columns-and-actions-pagination-and-sear
title: Guest list: Screen 11 columns and actions, pagination and search, deleting a guest releases their rooms
created: 2026-09-27T02:19:49.805Z
updated: 2026-09-27T02:19:49.805Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-bfccaf
tags: guest, list, screen, 11, columns, actions, pagination, search, app, docs, resources, tests
---
# Guest list: Screen 11 columns and actions, pagination and search, deleting a guest releases their rooms

## When to use
Requests like:
- "Guest list: Screen 11 columns and actions, pagination and search, deleting a guest releases their rooms"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/GuestController.php
- app/Models/Guest.php
- docs/decisions/0001-web-ui-follows-the-desktop-requirements-screen-list-and-book.md
- resources/views/staff/guest/list.blade.php
- tests/Feature/GuestListTest.php

Acceptance criteria that passed:
- The list shows each guest's name, email, phone, address and created date, with Open details and Delete actions
- With 21 guests, page 2 holds the oldest one
- Searching an email fragment lists only guests whose name or email matches, and keeps the text in the box
- The Delete confirmation states how many reservations are deleted with the guest
- Deleting a guest whose confirmed reservation holds room 305 sets room 305 to available
- Deleting a guest leaves a room unavailable when another guest's confirmed reservation still holds it
- Deleting a guest leaves a room in maintenance in maintenance
- After a delete the list returns with "Guest and related reservations deleted successfully."
- At 390px the page has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- The first six cycles were planned without reading DESKTOP_APPLICATION_REQUIREMENTS.txt and SYSTEM_DOCUMENTATION.txt; the owner caught it. Decision 0001 now makes the screen list and section 6 rules the checklist, and the gaps go into their own cycles.
- Node scripts with PHP namespaces in template literals lose backslashes; edit PHP with the Edit tool.
- Review found that releasing rooms ignored maintenance status; fixed with a red test. The same pattern in ReservationController update is scheduled for cycle 15.
- Browser: a throwaway guest holding room 401 was deleted from the list; the room returned to available.
