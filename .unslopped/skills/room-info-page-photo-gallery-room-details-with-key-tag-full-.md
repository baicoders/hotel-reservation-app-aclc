---
name: room-info-page-photo-gallery-room-details-with-key-tag-full-
title: Room info page: photo gallery, room details with key tag, full reservation history, clear delete warning
created: 2026-09-27T01:44:41.148Z
updated: 2026-09-27T01:44:41.148Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-cc508e
tags: room, info, page, photo, gallery, details, key, app, resources, tests
---
# Room info page: photo gallery, room details with key tag, full reservation history, clear delete warning

## When to use
Requests like:
- "Room info page: photo gallery, room details with key tag, full reservation history, clear delete warning"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- resources/views/staff/rooms/info.blade.php
- tests/Feature/RoomInfoTest.php

Acceptance criteria that passed:
- A room with a confirmed and a checked out reservation lists both on its info page
- Each reservation row links to /reservations/details/{id}
- With 2 reservations, the delete confirm message says the 2 reservations are deleted too; Confirm deletes the room and returns to /rooms with "Room is removed!"
- A room with no photos shows "No photos yet" and no gallery buttons
- With 3 photos, Next and Previous move the gallery and the counter reads "Photo 2 of 3" after one Next
- "Book this room" opens /guest/rooms/reserve?room_id={id} for an available room and is absent for a room in maintenance
- At 390px the page has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- Restarting Docker Desktop also restarted about 90 containers from other projects; pages took 30 to 57 seconds until they settled, and the first browser login timed out. Wait for /login to answer in under a few seconds before a browser run.
- `{{ }}` inside an onsubmit attribute is not safe for JavaScript strings, because the browser decodes entities before the script runs; use `@js()` there. Checked with a room numbered Q'1.
- Review found 2 minor screen reader issues in the gallery; both fixed and checked in the browser (one counter update per step, wraps jump instantly).
