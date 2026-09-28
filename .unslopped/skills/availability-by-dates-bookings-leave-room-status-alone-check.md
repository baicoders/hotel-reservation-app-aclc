---
name: availability-by-dates-bookings-leave-room-status-alone-check
title: Availability by dates: bookings leave room status alone, check in and out drive it, only maintenance blocks booking
created: 2026-09-27T22:34:10.216Z
updated: 2026-09-27T22:34:10.216Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-b20e74
tags: availability, dates, bookings, leave, room, status, alone, check, app, docs, resources, tests
---
# Availability by dates: bookings leave room status alone, check in and out drive it, only maintenance blocks booking

## When to use
Requests like:
- "Availability by dates: bookings leave room status alone, check in and out drive it, only maintenance blocks booking"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/GuestController.php
- app/Http/Controllers/ReservationController.php
- docs/decisions/0002-room-availability-is-decided-by-dates.md
- resources/views/guest/room-details.blade.php
- resources/views/staff/guest/info.blade.php
- resources/views/staff/guest/list.blade.php
- resources/views/staff/rooms/info.blade.php
- tests/Feature/BookingTest.php
- tests/Feature/GuestListTest.php
- tests/Feature/GuestRoomDetailTest.php
- tests/Feature/ReservationStatusTest.php

Acceptance criteria that passed:
- Booking an available room for future dates leaves the room status available
- A room that is unavailable because a guest is checked in can be booked for dates after that stay
- Booking a room in maintenance is refused and its booking form redirects to the room page
- Checking a reservation in sets its room to unavailable; a room in maintenance stays in maintenance
- Checking out or cancelling sets the room to available, unless another reservation of that room is checked in
- Confirming a pending reservation leaves the room status unchanged
- Moving a checked in stay back to confirmed frees the room
- Deleting a guest who is checked in frees the room; deleting a guest with only a confirmed future stay leaves the room status unchanged
- The guest room page offers Reserve for an unavailable (occupied) room and not for a room in maintenance
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. Browser on throwaway room T-DATES: back to back stays 10 to 12 and 12 to 14 booked, 11 to 13 refused, and the room went Available, Unavailable at check in, Available at check out. Test data removed through the app.
- Room 102 was unavailable only because of Maria's confirmed booking under the old rule; reset to available through the staff edit form, as decision 0002 foresees.
- Review: 3 minor (race on checkout, stay moved back from checked in, stale warning copy); all fixed, the second with a red test.
