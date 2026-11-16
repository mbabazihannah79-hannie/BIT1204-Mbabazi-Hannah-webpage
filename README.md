# BIT1204 – Mbabazi Hannah – Webpages

This repository contains:

| Folder | What it is |
|---|---|
| `wordpress-theme/elroi-kadosh/` | **WordPress theme for El Roi Khadoshi Motel, Kyenjojo** (Elementor-ready) |
| `logo/` | Motel logo files (PNG and SVG, green & gold) |

## El Roi Khadoshi Motel – WordPress website

A custom WordPress theme built for **El Roi Khadoshi Motel**, 20 Market Street (near Centenary Bank Building), Kyenjojo, Uganda.

### Pages (created automatically when the theme is activated)
Home · About Us · Rooms · Amenities · Dining & Bar · Gallery · Explore Kyenjojo · Book a Room · Contact Us · FAQ

### Features
- Online **booking form** with live price estimate; bookings are saved in the dashboard (**Bookings** menu) with a status (Pending, Confirmed, Checked in…)
- **Contact form**; messages are saved under **Messages**
- **Google Map** of the motel location, click-to-call and **WhatsApp** buttons
- Gallery with category filters and a pop-up image viewer
- Room comparison table, restaurant menu, FAQ, local attractions (Kibale National Park, Fort Portal)
- Contacts editable from **Appearance → Customize → Motel Details** (no code needed)
- Fully responsive (phone, tablet, desktop)

### How to install on WampServer
1. Copy the folder `wordpress-theme/elroi-kadosh` into `C:\wamp64\www\<your-wordpress-folder>\wp-content\themes\`
2. Log in to WordPress → **Appearance → Themes** → activate **El Roi Khadoshi Motel**
3. All pages, the menu and the home page are set up automatically
4. Go to **Appearance → Customize → Motel Details** and enter the real phone, WhatsApp and email
5. Go to **Settings → Permalinks** and click **Save** once (refreshes the page links)

### Editing the website (all from the WordPress dashboard)
| What to change | Where in WordPress |
|---|---|
| Rooms, prices, beds, features, room photos | **Motel Content → Rooms** |
| Restaurant menu and prices | **Motel Content → Restaurant Menu** |
| Amenities | **Motel Content → Amenities** |
| Places near Kyenjojo | **Motel Content → Places to Visit** |
| FAQs | **Motel Content → FAQs** |
| Headings, paragraphs and photos on every page | **Motel Content → Page Text & Photos** (live preview) |
| Phone, WhatsApp, email, address, map, check-in times | **Motel Content → Contacts & Details** |
| Menu links | **Appearance → Menus** |
| Booking requests / contact messages | **Bookings** / **Messages** |

### Editing with Elementor (click-and-edit on the page)
1. Install the free **Elementor** plugin (Plugins → Add New → search "Elementor" → Install → Activate).
2. Open the dashboard once: all 10 pages are built as Elementor designs automatically.
3. Go to **Motel Content → Elementor Pages** (or Pages → hover a page → **Edit with Elementor**).
4. Click any heading, paragraph, photo, button or section to change it. Drag new widgets from the left panel.
   The motel widgets (Room Cards, Booking Form, Restaurant Menu, Gallery, FAQs, Map…) are in the **El Roi Khadoshi Motel** group.
5. **Reset to original design** on the Elementor Pages screen restores any page.

After hosting the site on a new address, use **Elementor → Tools → Replace URL**.

Built with: WordPress, Elementor, PHP, HTML5, CSS3, JavaScript.
