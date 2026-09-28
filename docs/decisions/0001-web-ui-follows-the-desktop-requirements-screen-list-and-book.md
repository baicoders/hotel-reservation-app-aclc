# Decision 0001: Web UI follows the desktop requirements screen list and booking rules

Status: accepted
Date: 2026-09-27

## Context

The repository keeps two documents: SYSTEM_DOCUMENTATION.txt describes the web app as it was before the UI/UX overhaul, and DESKTOP_APPLICATION_REQUIREMENTS.txt specifies an equivalent desktop app. The overhaul (branch feature/ui-ux-overhaul) started without either being read, so finished pages drifted from the documented screens, and the booking flow still hard codes a one night stay.

## Decision

- Sections 4 (required screens) and 6 (domain rules) of DESKTOP_APPLICATION_REQUIREMENTS.txt are the acceptance checklist for every web page and for the booking rules. A cycle that touches a screen lists that screen's items in its acceptance criteria.
- The section 6 recommended improvements are in scope: guests pick check in and check out dates, check out must be after check in, the total is the nightly rate times the number of nights, the payment amount must equal that total, and availability is rechecked inside the booking transaction against overlapping active reservations.
- The staff room details page lists every reservation for the room with a status badge. This is a superset of the documented "confirmed reservation list" and was chosen by the project owner on 2026-09-27.
- Desktop only items stay out: installers, application data directories, backups, CSV export and first run setup. Printing a receipt is in scope through the browser print dialog.
- SYSTEM_DOCUMENTATION.txt is rewritten in the last cycle to describe the new behaviour.

## Options considered

- Follow SYSTEM_DOCUMENTATION.txt only: rejected because it documents existing limitations, not targets, and would keep the one night stay.
- Screen list without the booking rules: rejected by the project owner, who asked for the backend improvements too.
- Port the whole desktop specification: rejected because packaging, backups and file system layout do not apply to a server rendered web app.

## Consequences

- Six gaps in finished pages need a follow up cycle: reservation ID column and date, guest and room filters on the reservations list; Edit and Delete actions on room cards; the dashboard latest rooms list and a Refresh action; removing a picked photo before saving; blocking double submits; deleting photo files with their room.
- The booking form, controller and receipt change together, and room availability gains a date overlap check next to the existing status string.
- Remaining work: guests pages, the gap cycle, home and login, the guest room browser, booking with dates and totals, the receipt with print, releasing a room on cancellation only when no other active reservation needs it, and the documentation rewrite.
