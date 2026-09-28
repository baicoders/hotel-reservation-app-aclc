---
name: booking-rules-check-in-and-check-out-dates-total-of-rate-tim
title: Booking rules: check in and check out dates, total of rate times nights, no double booking inside the transaction
created: 2026-09-27T21:51:08.640Z
updated: 2026-09-27T21:51:08.640Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-15179b
tags: booking, rules, check, dates, total, rate, times, app, resources, tests
---
# Booking rules: check in and check out dates, total of rate times nights, no double booking inside the transaction

## When to use
Requests like:
- "Booking rules: check in and check out dates, total of rate times nights, no double booking inside the transaction"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ReservationController.php
- resources/views/guest/receipt-page.blade.php
- resources/views/guest/reserved-room-form.blade.php
- tests/Feature/BookingTest.php

Acceptance criteria that passed:
- Booking a $100 room from 2026-10-10 to 2026-10-13 with amount 300 creates one reservation with those dates and one payment of 300.00, and marks the room unavailable
- Amount 100 for that 3 night stay is rejected with an error on amount stating $300.00
- A check out on or before the check in is rejected with an error on check_out_date
- A check in before today is rejected with an error on check_in_date
- A stay of 31 nights is rejected
- A stay overlapping a confirmed reservation of the same room is rejected, even when the room status says available, and nothing is saved
- A stay starting on the day another reservation checks out is accepted
- A returning guest with the same email is reused, not duplicated
- Booking with an email that belongs to a stored guest shows only the details the visitor typed on the receipt
- /guest/rooms/reserve with no room_id redirects to /search-room; for a room in maintenance it redirects to the room page with an error
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- The first `unslopped red` timed out (exit 124): a missing room crashed the form and Laravel's error page took 50 seconds through the slow mount. A second run finished in 106 s.
- The old booking form loses the payment method after a validation error, so a resubmit silently fails the browser's required check; fixed in the cycle 15 redesign.
- Review found a privacy leak on the receipt (stored details of a guest matched by email); fixed with a red test. It also showed that marking the room unavailable at booking makes the overlap check rarely reachable; raised with the owner.
- Browser: throwaway room T-BOOK booked for 2 nights at $160.00, then removed with its test guest through the app.
