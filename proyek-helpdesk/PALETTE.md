Project color palette

This file documents the primary color palette used across the project and how to use it with Tailwind classes.

1) Source
- `tailwind.config.js` defines the custom colors under `theme.extend.colors`.

2) Palette (from `tailwind.config.js`)
- primary (object)
  - DEFAULT: #005691
  - 50: #E0F0F7
  - 100: #B3D6E7
  - 600: #004778
  - 700: #00335A
- accent (object)
  - 50: #E6F9FB
  - 100: #BFEFF2
  - 200: #8FE0E7
  - 300: #5FD1DC
  - 400: #2FC2D1
  - DEFAULT: #00BCD4
  - 600: #00A1B0
  - 700: #007B86
  - 800: #00565C
  - 900: #003F42

3) Usage examples (Tailwind CSS)
- Button CTA: `bg-primary text-white hover:bg-primary-600`
- Light primary background (card/icon): `bg-primary-50`
- Emphasis text: `text-primary` or `text-primary-600`
- Accent (secondary/emphasis): `text-accent`, background subtle: `bg-accent/10`

4) Dark mode notes
- `dark:` variants are available; the app uses some dark styles already (e.g. `dark:text-primary-100` or `dark:bg-primary-700/20`). Adjust if contrast is insufficient.

5) Dev / verify locally (PowerShell)
Run the dev build and server, then open the site in the browser to verify the landing page updates:

```powershell
# start Laravel dev server and vite dev build (runs sequentially in same shell)
php artisan serve; npm run dev
```

If `npm run dev` or `php artisan serve` exits with errors, check the terminal logs for missing dependencies or port conflicts.

6) Suggested follow-ups (I can do these)
- Add additional `accent` shades in `tailwind.config.js` for consistent modifiers (e.g. `accent-50`, `accent-100`, `accent-600`) — this lets you use `hover:bg-accent-600`, etc.
- Tweak contrast for numeric values on cards (use `text-primary-600` for stronger contrast).
- Run the build and confirm pages across different screen sizes and dark mode.

If you want, I can implement the `accent` shades and run a quick dev build to verify.
