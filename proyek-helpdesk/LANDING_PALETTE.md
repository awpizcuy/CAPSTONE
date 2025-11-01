# Landing Page Color Palette

## Hero Section

### Background Gradient
```css
/* Hero background gradient */
background-image: linear-gradient(
    rgba(0, 86, 145, 0.9),  /* primary */
    rgba(0, 51, 90, 0.95)   /* primary-700 */
);
```

### Navigation
- Link: `text-white`
- Link Hover: `text-primary-100`

### CTA Button
- Background: `bg-primary` (#005691)
- Hover: `bg-primary-600` (#004778)
- Text: `text-white`

## Statistics Section

### Section Background
- Light mode: `bg-primary-50`
- Dark mode: `bg-primary-900`

### Section Title
- Light mode: `text-primary-700`
- Dark mode: `text-primary-100`

### Section Subtitle
- Light mode: `text-primary-600`
- Dark mode: `text-primary-300`

### Statistic Cards

#### Total Reports Card
- Background: `bg-primary-50` (light), `bg-primary-900/50` (dark)
- Icon Background: `bg-primary-100` (light), `bg-primary-800/50` (dark)
- Icon Color: `text-primary-600` (light), `text-primary-400` (dark)
- Text: `text-primary-600` (light), `text-primary-300` (dark)

#### Pending Reports Card
- Background: `bg-orange-50` (light), `bg-orange-900/50` (dark)
- Icon Background: `bg-orange-100` (light), `bg-orange-800/50` (dark)
- Icon Color: `text-orange-500` (light), `text-orange-400` (dark)
- Number: `text-orange-500` (light), `text-orange-300` (dark)
- Label: `text-orange-600` (light), `text-orange-400` (dark)

#### Completed Reports Card
- Background: `bg-green-50` (light), `bg-green-900/50` (dark)
- Icon Background: `bg-green-100` (light), `bg-green-800/50` (dark)
- Icon Color: `text-green-500` (light), `text-green-400` (dark)
- Number: `text-green-500` (light), `text-green-300` (dark)
- Label: `text-green-600` (light), `text-green-400` (dark)

#### Rejected Reports Card
- Background: `bg-red-50` (light), `bg-red-900/50` (dark)
- Icon Background: `bg-red-100` (light), `bg-red-800/50` (dark)
- Icon Color: `text-red-500` (light), `text-red-400` (dark)
- Number: `text-red-500` (light), `text-red-300` (dark)
- Label: `text-red-600` (light), `text-red-400` (dark)

## Hex Color Values

### Primary Colors
```css
--primary: #005691;      /* primary default */
--primary-50: #E0F0F7;
--primary-100: #B3D6E7;
--primary-600: #004778;
--primary-700: #00335A;
```

### Status Colors
```css
/* Orange (Pending) */
--orange-50: #FFF7ED;
--orange-100: #FFE4CC;
--orange-500: #F97316;
--orange-600: #EA580C;
--orange-800: #9A3412;
--orange-900: #7C2D12;

/* Green (Completed) */
--green-50: #F0FDF4;
--green-100: #DCFCE7;
--green-500: #22C55E;
--green-600: #16A34A;
--green-800: #166534;
--green-900: #14532D;

/* Red (Rejected) */
--red-50: #FEF2F2;
--red-100: #FEE2E2;
--red-500: #EF4444;
--red-600: #DC2626;
--red-800: #991B1B;
--red-900: #7F1D1D;
```

## Usage Notes
1. Dark mode variants use opacity modifiers (e.g., `/50` means 50% opacity)
2. Status colors (orange, green, red) follow Tailwind's default color palette
3. Primary colors are custom-defined in `tailwind.config.js`
