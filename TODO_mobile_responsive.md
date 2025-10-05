# Mobile Responsiveness Improvements

## Current Issues
- Hero section images are too tall on mobile (400px height)
- Card images are oversized (200px height)
- Font sizes are too large for mobile screens
- Areas section in header takes too much horizontal space
- Padding and margins are excessive on small screens
- Sidebar on news pages may be too wide

## Planned Changes
- Add mobile-specific CSS media queries in app.blade.php
- Reduce hero image height to 250px on mobile
- Reduce card image heights to 150px on mobile
- Decrease font sizes for headings and text on mobile
- Adjust padding and margins for compact layout
- Make areas section stack vertically on mobile
- Optimize navbar for mobile
- Adjust news page sidebar layout

## Files to Edit
- resources/views/layouts/app.blade.php (add media queries)
- resources/views/home.blade.php (adjust hero height inline)
- resources/views/partials/header.blade.php (areas section mobile layout)
- resources/views/news/index.blade.php (sidebar adjustments)

## Testing
- Use browser_action to test mobile view after changes
