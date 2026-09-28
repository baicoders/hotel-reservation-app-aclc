---
name: search-rooms-by-dates-browser-lists-rooms-free-for-the-chose
title: Search rooms by dates: browser lists rooms free for the chosen stay and passes the dates to room details and the booking form
created: 2026-09-27T23:59:28.352Z
updated: 2026-09-27T23:59:28.352Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-52bbb0
tags: search, rooms, dates, browser, lists, free, chosen, app, resources, tests
---
# Search rooms by dates: browser lists rooms free for the chosen stay and passes the dates to room details and the booking form

## When to use
Requests like:
- "Search rooms by dates: browser lists rooms free for the chosen stay and passes the dates to room details and the booking form"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ReservationController.php
- app/Http/Controllers/RoomController.php
- app/Models/Reservation.php
- resources/views/components/room-cards.blade.php
- resources/views/guest/reserved-room-form.blade.php
- resources/views/guest/room-details.blade.php
- resources/views/guest/search-rooms.blade.php
- tests/Feature/BookingTest.php
- tests/Feature/GuestBrowseTest.php
- tests/Feature/GuestRoomDetailTest.php

Acceptance criteria that passed:
- Without dates the browser searches today to tomorrow and leaves out a room with a confirmed stay covering tonight
- With dates, a room whose stay overlaps is left out and a room whose stay ends on the check in day is listed
- A room that is unavailable because a guest is checked in now is listed for dates after that stay
- Rooms in maintenance are never listed
- A check out on or before the check in shows an error and falls back to today and tomorrow
- Card Reserve and View details links, and the room page Reserve link, carry check_in and check_out
- The room page with overlapping dates shows "This room is already booked for some of these dates." and no Reserve
- The booking form opened with check_in and check_out fills its date fields with them
- At 390px the browser has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The overlap condition existed in three places after this cycle's first draft; one Reservation::overlapping scope now serves the booking, the browser and the room page.
- The Node edit script dropped backslashes in a Blade class name again; use the Edit tool for any text with backslashes.
- Review: 2 minor (array query dates crashing the form, silent fallback on the room page); both fixed with red tests.
