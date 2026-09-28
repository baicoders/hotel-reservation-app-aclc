---
name: receipt-screen-8-confirmation-after-a-redirect-print-and-sav
title: Receipt: Screen 8 confirmation after a redirect, print and save as PDF, book another room, no false email claim
created: 2026-09-28T01:37:38.880Z
updated: 2026-09-28T01:37:38.880Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260928-2502da
tags: receipt, screen, confirmation, redirect, print, save, pdf, book, app, resources, routes, tests
---
# Receipt: Screen 8 confirmation after a redirect, print and save as PDF, book another room, no false email claim

## When to use
Requests like:
- "Receipt: Screen 8 confirmation after a redirect, print and save as PDF, book another room, no false email claim"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/AuthenticationController.php
- app/Http/Controllers/ReservationController.php
- resources/views/guest/receipt-page.blade.php
- resources/views/layout/guest.blade.php
- routes/web.php
- tests/Feature/BookingTest.php
- tests/Feature/ReceiptTest.php

Acceptance criteria that passed:
- A successful booking redirects to /guest/receipt
- The receipt shows the confirmation number, the stay dates and nights, the room, the nightly rate, the total paid, the payment method and date, and the guest details as typed
- Reloading /guest/receipt shows the same receipt and creates no second reservation
- /guest/receipt without a booking in the session redirects to /search-room
- The receipt makes no claim that an email was sent
- Print receipt opens the browser print dialog, and the printed page hides the site header, footer and buttons
- Book another room opens /search-room and Done opens /home, and both clear the receipt
- The receipt closes after 15 minutes and after a staff sign in on the same browser
- Booking with an email that belongs to a stored guest still shows only the typed details on the receipt
- At 390px the receipt has no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The receipt view write was refused once because the file changed after it was read; reread before rewriting a file another step touched.
- Review found the receipt lingering in the session on a shared browser; it now expires and is cleared on Done, Book another room and staff sign in, with red tests.
- Browser: throwaway rooms booked, receipt #000010 shown, reload booked nothing, print view hid header and buttons, Done cleared the receipt; test data removed.
