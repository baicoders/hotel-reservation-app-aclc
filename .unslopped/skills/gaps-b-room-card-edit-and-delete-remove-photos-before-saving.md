---
name: gaps-b-room-card-edit-and-delete-remove-photos-before-saving
title: Gaps B: room card Edit and Delete, remove photos before saving, block double submits, delete photo files with the room
created: 2026-09-27T05:21:57.478Z
updated: 2026-09-27T05:21:57.478Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 3
lastCycle: 20260927-6e1df0
tags: gaps, room, card, edit, delete, remove, photos, saving, app, resources, tests
---
# Gaps B: room card Edit and Delete, remove photos before saving, block double submits, delete photo files with the room

## When to use
Requests like:
- "Gaps B: room card Edit and Delete, remove photos before saving, block double submits, delete photo files with the room"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- app/Models/Room.php
- resources/views/components/modal-pop.blade.php
- resources/views/components/room-cards.blade.php
- resources/views/components/staff-nav.blade.php
- resources/views/layout/app.blade.php
- resources/views/staff/guest/info.blade.php
- resources/views/staff/guest/list.blade.php
- resources/views/staff/reservations/info.blade.php
- resources/views/staff/rooms/form.blade.php
- resources/views/staff/rooms/info.blade.php
- resources/views/staff/rooms/list.blade.php
- tests/Feature/RoomFormTest.php
- tests/Feature/RoomListTest.php

Acceptance criteria that passed:
- Each staff room card links to /rooms/edit/{id} and has a Delete form posting to /rooms/delete/{id}
- Guest room cards on /search-room have no Edit or Delete
- A card's Delete confirmation states the room's reservation count; Confirm deletes the room and shows "Room is removed!"
- Deleting a room with 2 photos removes both files from storage/app/public/room-photos
- On Add room, choosing 3 photos and removing 1 uploads 2 photos
- After pressing Add room once, the button is disabled and reads "Saving..." until the next page loads
- At 390px the rooms list has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 11 (3 failed)

## Known failures
- code gate, scope failed 3 time(s): 1 changed file(s) are not in the plan's "## Files to touch":

## Notes
- Code gate failed once on scope: the rooms list had to pass the reservation count to its cards and was not declared.
- Browser checks of a busy button need the submit cancelled at window level; cancelling on the form itself makes the page listener skip the lock, which is the intended behaviour for dialog forms.
- Playwright's click waits out a navigation, so a delayed route did not expose the busy state; check it inside page.evaluate right after button.click().
- Deleting room 409 with 2 photos took the photo folder from 6 files back to 4.
- Review: 1 minor (busy label wording), fixed.
- The unslopped scanner flagged restoring a button from a data attribute with innerHTML as script injection; the lock now keeps and restores the original child nodes, no HTML strings.
