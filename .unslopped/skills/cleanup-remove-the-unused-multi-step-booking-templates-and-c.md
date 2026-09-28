---
name: cleanup-remove-the-unused-multi-step-booking-templates-and-c
title: Cleanup: remove the unused multi step booking templates and commented out methods, add a smoke test for every page
created: 2026-09-28T03:08:13.819Z
updated: 2026-09-28T03:08:13.819Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 3
lastCycle: 20260928-75822a
tags: cleanup, remove, unused, multi, step, booking, templates, commented, app, resources, tests
---
# Cleanup: remove the unused multi step booking templates and commented out methods, add a smoke test for every page

## When to use
Requests like:
- "Cleanup: remove the unused multi step booking templates and commented out methods, add a smoke test for every page"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ReservationController.php
- resources/views/guest/guest-form.blade.php
- resources/views/guest/payment-form.blade.php
- resources/views/staff/guest/edit.blade.php
- resources/views/staff/reservations/edit.blade.php
- tests/Feature/PageSmokeTest.php

Acceptance criteria that passed:
- The four unused templates are gone and a search of app, routes and resources finds no reference to them
- ReservationController has no commented out methods
- Every public GET page returns a status below 500 for a visitor, including the booking form opened with an array room_id, which crashed before this cycle
- Every staff GET page returns 200 for a signed in staff member
- `npm run build` exits 0 and all tests pass

Gate runs: 11 (3 failed)

## Known failures
- test gate, red before green failed 1 time(s): no failing test run recorded for the changed test files. write the test first, run `unslopped red` and watch it fail, then implement. set pr
- release gate, commit format failed 2 time(s): 1 commit message problem(s):

## Notes
- Test gate failed once: the smoke test passed on the first run, so no red was on record. Probing edge URLs found a real crash (booking form with room_id as a list returned 500); adding it to the smoke test gave a genuine red, then the fix.
- A Node loop to find the commented block never ended; removed by line range instead after reading the boundaries.
- Reviewed in the session (deletions, one guard, one test): no findings.
