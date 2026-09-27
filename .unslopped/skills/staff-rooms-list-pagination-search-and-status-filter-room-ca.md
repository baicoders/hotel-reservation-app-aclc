---
name: staff-rooms-list-pagination-search-and-status-filter-room-ca
title: Staff rooms list: pagination, search and status filter, room cards with key tag and photo fallback
created: 2026-09-26T21:26:27.705Z
updated: 2026-09-26T21:26:27.705Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 5
lastCycle: 20260926-42cc6c
tags: staff, rooms, list, pagination, search, status, filter, room, app, resources, tests
---
# Staff rooms list: pagination, search and status filter, room cards with key tag and photo fallback

## When to use
Requests like:
- "Staff rooms list: pagination, search and status filter, room cards with key tag and photo fallback"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- resources/css/app.css
- resources/views/components/key-tag.blade.php
- resources/views/components/room-cards.blade.php
- resources/views/staff/rooms/list.blade.php
- tests/Feature/RoomListTest.php

Acceptance criteria that passed:
- With 11 rooms, the staff rooms list shows all 11 on the first page
- With 13 rooms, the 13th room by number appears on page 2 and the pager links to it
- Searching "Suite" keeps "Suite" in the search box and lists only rooms whose number or type matches
- Filtering by Maintenance lists only maintenance rooms, and combines with a search
- A room with no photo, or whose photo fails to load, shows "No photo yet" instead of a broken image
- Each card shows the room number on the key tag, the type, the nightly price and a status badge, and links to the room info page
- /search-room returns 200 and each available room card links to /guest/rooms/details/{id}
- At 390px the list has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 13 (5 failed)

## Known failures
- plan gate, criteria quality failed 2 time(s): 1 criterion(s) are descriptions, not verifiable outcomes. state what can be observed (status code, value, visible effect):
- code gate, scope failed 3 time(s): 1 changed file(s) are not in the plan's "## Files to touch":

## Notes
- Plan gate failed once: a criterion read as a description; restate as an observable result (status code, link target).
- Code gate failed once on scope: a late CSS tweak touched app.css, which the plan had not declared. Declare shared files up front when a page may need a token tweak.
- Review found 5 minor issues (array query 500, natural room order, placeholder read aloud, empty state wording, heading level); 2 got red tests first, all fixed before commit.
- Probe: /rooms, /rooms?page=9, /rooms?query=zzz and /rooms?query[]=x return 200 with the right empty state.
