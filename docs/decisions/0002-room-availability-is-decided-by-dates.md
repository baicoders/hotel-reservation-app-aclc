# Decision 0002: Room availability is decided by dates

Status: accepted
Date: 2026-09-27

## Context

Cycle 14 added check in and check out dates to bookings, but kept the documented rule that a booking marks its room unavailable. Review showed that one booking, even months away, then blocks the room for every other date, so the overlap check could rarely run. The project owner chose date based availability on 2026-09-27.

## Decision

- A room's status is its physical state: `available` (free now), `unavailable` (a guest is checked in) or `maintenance` (out of service). Staff can still set any of them.
- Making a booking never changes the room's status. A room can be booked for any dates that do not overlap a pending, confirmed or checked in reservation, unless it is in maintenance.
- Checking a guest in marks the room unavailable, unless it is in maintenance. Checking out or cancelling marks it available again, unless another guest is still checked in there or it is in maintenance. Confirming or setting pending leaves the room status alone.
- The public room browser searches by dates and lists rooms not in maintenance with no overlapping reservation for those dates.

This replaces the "a successful booking changes the room status to unavailable" rule in SYSTEM_DOCUMENTATION.txt and refines decision 0001.

## Options considered

- Keep one booking at a time (booking marks the room unavailable): rejected by the owner, because a room could hold only one upcoming booking.
- Drop the room status field entirely and derive everything from reservations: rejected because staff need maintenance, and the dashboard and lists already read status.

## Consequences

- The booking, the reservation status update and the guest delete stop treating "confirmed" as holding the room; only "checked in" occupies it.
- Existing rooms marked unavailable only because of a future booking need their status set back to available by staff, since no migration rewrites them.
- The room browser, room details and booking form pass the chosen dates along (next cycle).
