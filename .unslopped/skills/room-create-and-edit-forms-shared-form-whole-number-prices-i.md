---
name: room-create-and-edit-forms-shared-form-whole-number-prices-i
title: Room create and edit forms: shared form, whole number prices, inline errors, add photos when editing
created: 2026-09-27T00:59:31.701Z
updated: 2026-09-27T00:59:31.701Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260926-ff71cd
tags: room, create, edit, forms, shared, form, whole, number, app, resources, tests
---
# Room create and edit forms: shared form, whole number prices, inline errors, add photos when editing

## When to use
Requests like:
- "Room create and edit forms: shared form, whole number prices, inline errors, add photos when editing"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- resources/views/staff/rooms/create.blade.php
- resources/views/staff/rooms/edit.blade.php
- resources/views/staff/rooms/form.blade.php
- tests/Feature/RoomFormTest.php

Acceptance criteria that passed:
- Adding a room with price "100" creates it and redirects to /rooms with "New Room is added!"
- Adding a room whose number exists redirects back with an error under the Room number field and the typed values kept
- Adding a room with 2 photos stores 2 rows in rooms_photos
- Editing a room with price "120" saves 120.00 and redirects to the room info page with "Room is updated!"
- Editing a room with a new photo stores 1 more row in rooms_photos
- A photo uploaded as x.html with PNG content is stored with a .png extension
- Adding a room with an invalid photo creates no room
- Choosing photos on the add room page shows a preview of each before upload
- Both pages have no horizontal scroll at 390px
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- Docker Desktop had stopped between sessions; the first red run after resuming failed on the Docker connection, not on the tests. Check `docker compose ps` before trusting a red run.
- A red test passed for the wrong reason (its price tripped the decimal bug first); isolate each test to the one rule it checks.
- The browser script clicked the sidebar Sign out button with a bare `form button[type=submit]` selector; scope selectors to `main`.
- Review found a major stored XSS risk in photo naming (pre existing, newly reachable from edit); fixed with a red test before commit.
