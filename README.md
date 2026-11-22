# BIT1204 – Mbabazi Hannah – Webpages

WordPress website for **El Roi Khadoshi Motel**, 20 Market Street (near Centenary Bank Building), Kyenjojo, Uganda.

- **Developer:** Mbabazi Hannah
- **Platform:** WordPress (CMS) with a custom block child theme and a custom bookings plugin
- **Local environment:** WampServer (Windows)
- **Live server:** cPanel hosting

## Repository layout

```
BIT1204-Mbabazi-Hannah-webpage/
├── elroi-child/                 Child theme of Twenty Twenty-Five
│   ├── style.css                Theme header + all custom CSS (green & gold, line icons)
│   ├── theme.json               Colour palette, Playfair Display + Inter (bundled, SIL OFL), sizes, buttons
│   ├── functions.php            Motel details, photo lookup by file name, WhatsApp button, block styles
│   ├── inc/blocks.php           Helpers that write the block markup for the page designs
│   ├── inc/content.php          Starting lists: amenities, places, menu, FAQs, gallery photos
│   ├── inc/setup.php            Appearance → Motel setup (one-click page build)
│   ├── parts/, patterns/        Header, footer and a design for every page
│   └── assets/                  Fonts, logo files, slider.js, gallery.js, site.js, editor.css
├── elroi-bookings/              Custom plugin: rooms, booking requests, contact messages
│   ├── elroi-bookings.php       Bootstrap, settings, helpers
│   ├── includes/class-erb-db.php      All SQL (prepared statements), install and upgrade
│   ├── includes/class-erb-public.php  Shortcodes, booking and contact form handlers, emails
│   ├── includes/class-erb-admin.php   Bookings, Rooms & prices, Messages, Settings screens
│   ├── assets/                  public.css, public.js (price estimate), admin.css, admin.js
│   └── uninstall.php            Drops the module's tables when the plugin is deleted
├── logo/                        Motel logo files (PNG and SVG, green & gold)
└── README.md
```

The theme gives the look and the pages; the plugin holds the data. Every page is made of normal
WordPress blocks (Cover, Group, Columns, Heading, Paragraph, Image, Buttons, List, Table, Details),
so **anything on a page is edited by clicking it in the block editor**: no page builder needed.

## Pages

Home · About Us · Rooms · Amenities · Dining & Bar · Gallery · Explore Kyenjojo · Book a Room · Contact Us · FAQ

## Custom module: El Roi Bookings

Three MySQL tables (prefix `wp_erb_`):

```
rooms    (id, slug, name, price, max_guests, beds, description, features, photo_ids, photo_keys, sort_order, is_active)
   1 ──< bookings (id, reference, room_id → rooms.id, room_name, guest_name, phone, email, check_in, check_out,
                   nights, rooms_qty, guests, total, notes, status, staff_note, created_at)
messages (id, name, contact, subject, message, is_read, created_at)
```

- A booking keeps a copy of the room name, so deleting a room never loses past bookings.
- Booking lists use a `LEFT JOIN` with rooms to show the room's current name.
- Full CRUD in wp-admin, every query through `$wpdb->prepare()`.
- Statuses: Pending → Confirmed → Checked in → Completed (or Cancelled).
- If the site ran the old theme, its rooms (with any prices and photos already changed), bookings and messages are copied into the new tables automatically on activation.

**Booking flow:** guest picks dates (search bar on Home, or the form on Book a Room) → sees a live price
estimate → sends a request → it is saved as *Pending* with a reference like `ERK-7KQ2MD` → the motel is emailed
(and the guest, if they gave an email) → staff open it under **Bookings**, call or WhatsApp the guest with one click,
and change the status.

**Security:** nonces and capability checks on every action, sanitised input and escaped output, a hidden spam trap,
and a limit of 5 submissions per visitor every 10 minutes.

### Shortcodes (already placed on the pages)

| Shortcode | Where | Shows |
|---|---|---|
| `[erk_quick_book]` | Home hero | Check-in / check-out search bar |
| `[erk_rooms]` | Home | Room cards with prices |
| `[erk_room_details compare="1"]` | Rooms | Every room with photos, features and the comparison table |
| `[erk_booking_form]` | Book a Room | Booking form, rates and policies |
| `[erk_contact_form]` | Contact Us | Contact form |

## Editing the website

| What to change | Where in WordPress |
|---|---|
| Any heading, paragraph, photo, button, amenity, menu item, FAQ, place or opening hour | **Pages** → open the page → click it and type |
| Photos | Click a photo → **Replace** → choose from the Media Library |
| Page banner photo | Click the banner → **Replace** (top toolbar) |
| Photo sliders (Home "Take a look around", Gallery) | Click a photo in the dashed box to replace it or change its caption; add or delete Image blocks |
| Gallery photo category | Select the photo → Advanced → Additional CSS class: `erk-cat-motel`, `erk-cat-rooms`, `erk-cat-dining` or `erk-cat-area` |
| Rooms, prices, beds, features, room photos | **Bookings → Rooms & prices** |
| Booking requests / contact messages | **Bookings → All bookings** / **Bookings → Messages** |
| Phone, WhatsApp, email, address, map, check-in times | **Bookings → Settings** (top bar, footer and forms update straight away) |
| Menu links, top bar, footer | **Appearance → Editor → Patterns → Header / Footer** |
| Start a page again from the original design | **Appearance → Motel setup** → tick *Reset* → Run setup |

**Real photos:** upload them to **Media → Add New** with these names and run *Motel setup → Reset* once, and every
page picks them up (until then, free sample photos are shown):
`hero` `exterior` `reception` `terrace` `parking` `gym` `room-standard` `room-deluxe` `room-family`
`room-apartment` `room-extra1` `room-extra2` `bathroom` `workdesk` `restaurant` `dining-room` `breakfast`
`breakfast2` `coffee` `bar` `wifi` `chimp` `tea` `landscape` (`.jpg`, `.png` or `.webp`).

## Install locally (WampServer)

1. Copy `elroi-child` into `C:\wamp64\www\<site>\wp-content\themes\` and `elroi-bookings` into `...\wp-content\plugins\`.
2. Make sure **Twenty Twenty-Five** is installed (Appearance → Themes; it comes with WordPress 6.7+).
3. **Plugins** → activate **El Roi Bookings**. **Appearance → Themes** → activate **El Roi Khadoshi Motel**.
4. **Appearance → Motel setup → Build the site.**
5. **Bookings → Settings**: enter the real phone, WhatsApp and email.
6. Elementor is no longer needed; it can be deactivated and deleted.

## Hosting on cPanel

1. cPanel → **Domains** → create the subdomain (for example `elroi.yourdomain.org`) and wait for its folder.
2. cPanel → **Softaculous / WordPress Manager** → install WordPress on that subdomain.
3. cPanel → **SSL/TLS Status** → **Run AutoSSL** so the site opens on `https://`.
4. In the new WordPress: **Plugins → Add New → Upload** `elroi-bookings.zip` → Activate;
   **Appearance → Themes → Add New → Upload** `elroi-child.zip` → Activate.
5. **Appearance → Motel setup → Build the site**, then **Bookings → Settings**.
6. Settings → General: check the site title and that both addresses start with `https://`.
7. Make a test booking and a test message, and check that the alert email arrives (if it doesn't, install an SMTP plugin such as WP Mail SMTP with the cPanel email account).

To make the zips: right-click the `elroi-child` folder → *Send to → Compressed (zipped) folder* (same for `elroi-bookings`).
The zip must contain the folder itself, not just its files.

Built with: WordPress (block theme, theme.json, block patterns), PHP, MySQL, HTML5, CSS3, JavaScript.
