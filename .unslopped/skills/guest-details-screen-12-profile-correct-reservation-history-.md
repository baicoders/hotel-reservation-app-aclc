---
name: guest-details-screen-12-profile-correct-reservation-history-
title: Guest details: Screen 12 profile, correct reservation history with room and reservation status, delete with count
created: 2026-09-27T02:39:28.586Z
updated: 2026-09-27T02:39:28.586Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-bb4ec4
tags: guest, details, screen, 12, profile, correct, reservation, history, app, resources, tests
---
# Guest details: Screen 12 profile, correct reservation history with room and reservation status, delete with count

## When to use
Requests like:
- "Guest details: Screen 12 profile, correct reservation history with room and reservation status, delete with count"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/GuestController.php
- resources/views/components/sidebar.blade.php
- resources/views/staff/guest/info.blade.php
- tests/Feature/GuestDetailTest.php

Acceptance criteria that passed:
- The profile shows the guest's name, email, phone, address and date added
- For a checked out reservation in a room now in maintenance, the history shows reservation status "Checked out" and room status "Maintenance"
- Each history row links to /reservations/details/{reservation id}, not the room id
- The page has no link to /bookings/create, and "Book a room" opens /search-room
- The Delete confirmation states the guest's reservation count; Confirm deletes the guest and returns to /guest with the notice
- A guest with no reservations shows "No reservations yet"
- At 390px the page has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The first /login after an idle period took 53 seconds (the machine runs about 90 other containers); later requests were fast.
- Every staff page is now on the staff shell, so the old sidebar component was removed.
- Review: no findings.
