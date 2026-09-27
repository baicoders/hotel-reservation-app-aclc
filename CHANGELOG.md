# Changelog

## Unreleased

### Changed
- New visual base: lobby teal and brass palette, Big Shoulders Display and Figtree type, shared button, field and card styles.
- Flash messages show both success and error notices in one stack; each stays until dismissed.
- The confirm dialog uses the native dialog element: Escape closes it and focus starts on Cancel.
- Forms that save data disable their button and show "Saving..." once submitted, so a double click cannot submit twice.
- The home page shows the system title over the ACLC building with Reserve a room and Manage, and the version in the footer when APP_VERSION is set.
- The login page has a clear error under the Username field, keeps the typed username, and shows Signing in while it submits. The 404 page links home and to the room browser.
- The guest room browser lists available rooms in pages of 12 with a room type filter, a price sort, and View details and Reserve on each card, inside a new public layout.
- The guest room page shows the room details, a gallery of every photo, Reserve and Back, and says plainly when a room cannot be booked. It shares its gallery with the staff room page.
- Guests choose check in and check out dates when booking; the total is the nightly rate times the nights, stays run up to 30 nights, and the receipt shows the stay and the total paid.
- A booking no longer changes the room status: rooms can hold bookings for different dates, and only rooms in maintenance cannot be booked. Checking in marks the room unavailable; checking out or cancelling makes it available again unless another guest is checked in there or it is in maintenance (decision 0002).
- The guest room browser searches by check in and check out dates (tonight by default) and lists rooms free for that stay; the dates carry through to the room page and the booking form, and the room page says when a room is already booked for them.
- Staff pages get a sidebar that marks the current page and folds behind a Menu button on phones.
- The dashboard shows totals for rooms, available rooms, open reservations and guests, recent reservations by room number and status, rooms not available, the latest rooms and new guests, with a Refresh link and the time the page loaded.
- The staff rooms list has a search that keeps what was typed, a status filter, pages of 12 rooms ordered by room number, and room cards with a brass key tag and Details, Edit and Delete actions.
- Add room and Edit room share one form with errors under each field, photo previews before upload with a Remove button for each, and photos can be added when editing. Saving an edit returns to the room info page.
- The room info page has a photo gallery with Previous and Next, the room details under a large key tag, every reservation for the room with links, and a Book this room action for available rooms.
- The reservations list shows the reservation ID, room number and stay dates, filters by status, guest, room and a date the guest is staying, and pages of 15. The reservation page shows the guest, the room with links to both, the payment, and a status update whose confirmation names the new status.
- The guest list shows name, email, phone, address and date added, with a name or email search, pages of 20, and Open details and Delete on each row.
- The guest page shows the profile, every reservation with its room and both statuses, and a Delete guest action that states the reservation count.

### Fixed
- Pages in the local Docker setup took up to several seconds to load; PHP now caches compiled code for the dev server (restart the app container after editing PHP or Blade files).
- Pages no longer print markup before the doctype, which forced browsers into quirks mode.
- Error notices such as "Photo upload failed!" are now shown to staff.
- Dashboard counts were capped at 5 because they counted the latest five records; they now count the whole table.
- The staff rooms list showed only 10 rooms; every room is now reachable through the pager.
- Room photos that are missing or fail to load show "No photo yet" instead of a broken image.
- Whole number prices such as "100" are accepted when adding or editing a room.
- An invalid photo no longer leaves a half saved room behind; the room is only created once every photo passes validation.
- Deleting a room now warns how many reservations and payments are deleted with it, and removes its photo files from storage.
- The room info page listed only confirmed reservations; checked in, checked out and cancelled stays now show too.
- The reservations list stopped at 50; every reservation is now reachable through the pager.
- The reservation page loaded the payment but never showed it.
- The guest list stopped at 50; every guest is now reachable through the pager.
- Deleting a guest makes the rooms their confirmed or checked in reservations held available again, unless another guest still holds the room. The confirmation states how many reservations go with the guest.
- The guest page showed the room status and room id in place of the reservation status and id; it now shows both statuses and links to the right reservation.
- The guest page Reserve Room button pointed to a page that does not exist; Book a room now opens the room browser.
- A failed login reported its error on an email field the form does not have; it now appears under Username.
- Signed in staff who open /login go straight to the dashboard.
- The guest room browser stopped at 50 rooms; every available room is now reachable through the pager.
- Typing % or _ into the reservations, rooms or guests search matched every row; those characters now match literally.

- Bookings were fixed at one night starting today; they now use the chosen dates.
- Changing a reservation status could put a room in maintenance back to available or unavailable; maintenance is now left alone.
- A room can no longer be booked twice for overlapping dates: the booking rechecks confirmed, checked in and pending reservations inside its transaction.
- The booking form crashed without a room and opened for rooms that cannot be booked; it now returns to the room browser or the room page.

### Security
- Uploaded room photos are stored under a name and extension taken from the file content, not the uploaded file name, so an image renamed to .html can no longer be served as a web page.
