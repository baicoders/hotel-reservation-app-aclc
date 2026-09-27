---
name: guest-room-details-screen-6-gallery-reserve-and-back-clear-u
title: Guest room details: Screen 6 gallery, Reserve and Back, clear unavailable state, shared gallery partial
created: 2026-09-27T09:56:39.919Z
updated: 2026-09-27T09:56:39.919Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-05b079
tags: guest, room, details, screen, gallery, reserve, back, clear, app, resources, tests
---
# Guest room details: Screen 6 gallery, Reserve and Back, clear unavailable state, shared gallery partial

## When to use
Requests like:
- "Guest room details: Screen 6 gallery, Reserve and Back, clear unavailable state, shared gallery partial"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- resources/views/components/room-gallery.blade.php
- resources/views/guest/room-details.blade.php
- resources/views/staff/rooms/info.blade.php
- tests/Feature/GuestRoomDetailTest.php

Acceptance criteria that passed:
- An available room shows its number, type, nightly price and status, and Reserve opens /guest/rooms/reserve?room_id={id}
- A room in maintenance shows "This room is not available to book right now." and no Reserve link
- A room with 2 photos shows both, labelled "photo 1 of 2" and "photo 2 of 2", with Previous and Next
- A room with no photos shows "No photos yet" and no staff link
- Back returns to /search-room
- The staff room page still shows its gallery, counter and "Add photos" link
- At 390px the page has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The owner saw app screenshots inside room 401's card: those were test photos uploaded in cycle 4. Test rooms 401 and 201 were deleted through the app afterwards, and an orphan file from a room deleted with tinker was removed. Use real images or generated fixtures for photo tests.
- The owner reported 5 second page loads. Measured warm server responses of 0.15 to 1.3 s; the dev server runs `artisan serve` with opcache off for the CLI. Handled in the next cycle.
- Review: 2 minor; the live region fixed, the fixed gallery ids accepted since each page includes the gallery once.
