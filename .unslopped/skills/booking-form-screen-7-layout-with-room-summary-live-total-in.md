---
name: booking-form-screen-7-layout-with-room-summary-live-total-in
title: Booking form: Screen 7 layout with room summary, live total, inline errors, kept payment method, Cancel
created: 2026-09-28T00:58:16.843Z
updated: 2026-09-28T00:58:16.843Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-3e40e1
tags: booking, form, screen, layout, room, summary, live, total, app, resources, tests
---
# Booking form: Screen 7 layout with room summary, live total, inline errors, kept payment method, Cancel

## When to use
Requests like:
- "Booking form: Screen 7 layout with room summary, live total, inline errors, kept payment method, Cancel"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ReservationController.php
- resources/views/guest/reserved-room-form.blade.php
- tests/Feature/BookingFormTest.php

Acceptance criteria that passed:
- Opening the form for a $100 room with a 3 night stay shows 3 nights and a total of $300.00, and the amount field holds 300.00
- Changing the dates in the browser updates nights, total and the amount field without a reload
- After a validation error the payment method and every other entered value are kept, and each error sits under its field
- The corner error summary does not repeat the inline errors
- Cancel opens the room page with the chosen dates
- A valid submission still books the room and shows the receipt
- At 390px the form has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The browser run found the summary and amount out of step with the kept dates after an error; the server now computes the total from the kept input and the script syncs on load, both with a red test.
- A test passed for the wrong reason (the payment date field also holds today); anchor value assertions on the field name.
- Native fieldset legends sit on the card border; a floated full width legend with a clearing grid keeps them inside the card.
- Review: 3 minor (array old input, reversed kept dates, doubled group names); all fixed, two with red tests.
