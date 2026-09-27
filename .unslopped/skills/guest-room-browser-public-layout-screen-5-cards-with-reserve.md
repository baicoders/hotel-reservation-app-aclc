---
name: guest-room-browser-public-layout-screen-5-cards-with-reserve
title: Guest room browser: public layout, Screen 5 cards with Reserve, type filter, price sort, pagination
created: 2026-09-27T06:39:47.174Z
updated: 2026-09-27T06:39:47.174Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-17efd6
tags: guest, room, browser, public, layout, screen, cards, reserve, app, resources, tests
---
# Guest room browser: public layout, Screen 5 cards with Reserve, type filter, price sort, pagination

## When to use
Requests like:
- "Guest room browser: public layout, Screen 5 cards with Reserve, type filter, price sort, pagination"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/RoomController.php
- resources/views/components/room-cards.blade.php
- resources/views/guest/search-rooms.blade.php
- resources/views/layout/guest.blade.php
- tests/Feature/GuestBrowseTest.php

Acceptance criteria that passed:
- /search-room lists only rooms whose status is available
- Each card shows the room number, type, nightly price, status and first photo or placeholder, with View details and Reserve
- Reserve on a card opens /guest/rooms/reserve?room_id={id}
- ?type=Suite lists only available suites, and the type list offers only types that available rooms have
- ?sort=price_asc orders by nightly price low to high; ?sort=price_desc high to low
- With 13 available rooms, page 2 holds the 13th by room number
- With no available rooms the page says so and links home
- A page past the last one says so and links to page 1
- At 390px the page has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. Review found the same past the end empty state issue the staff rooms list had in cycle 3; reuse that pattern for every paginated list.
