---
name: reservations-list-and-detail-pagination-status-filter-paymen
title: Reservations list and detail: pagination, status filter, payment and guest details, linked room
created: 2026-09-27T01:58:33.219Z
updated: 2026-09-27T01:58:33.219Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-029a7b
tags: reservations, list, detail, pagination, status, filter, payment, guest, app, resources, tests
---
# Reservations list and detail: pagination, status filter, payment and guest details, linked room

## When to use
Requests like:
- "Reservations list and detail: pagination, status filter, payment and guest details, linked room"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ReservationController.php
- resources/views/staff/reservations/info.blade.php
- resources/views/staff/reservations/list.blade.php
- tests/Feature/ReservationPagesTest.php

Acceptance criteria that passed:
- With 16 reservations, page 2 of the list holds the oldest one
- ?status=cancelled lists reservations stored as cancelled or canceled and nothing else, and the pager keeps the filter
- The detail page shows the payment amount and method, or "No payment recorded" when there is none
- The detail page links to /guest/details/{guest id} and /rooms/details/{room id}
- Updating the status to checked_in saves it, marks the room unavailable and returns with "Reservation status updated successfully"
- At 390px both pages have no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- The reservations table overflowed the page at 390px even inside an overflow container, because an absolutely positioned sr-only label escaped it; give scroll containers `relative`.
- The browser run changed a real reservation's status to test the form; checking in rewrote its check in date to today. Restored the date by hand. Prefer a throwaway reservation for write tests.
- Tailwind `capitalize` capitalised every word of a sentence; use `ucfirst` on the value instead.
- Review: no findings.
