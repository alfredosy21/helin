## Why

The CMS interface feels robotic and disconnected from the brand identity. Colors, spacing, and component styles are inconsistent across 41 views — each duplicates inline Tailwind classes manually instead of using shared components. The CMS palette (`#09b6a2`) doesn't match the frontend palette (`#6BC2C3` / `#123F4A` / `#2D3740`), creating a jarring visual disconnect when navigating between the public site and the admin panel. A unified, modern, soft UI will improve usability and reinforce brand consistency.

## What Changes

- **Unify color palette**: Map CMS `tailwind.config.js` and `app.css` to the exact frontend colors (turquesa `#6BC2C3`, turquesa-dark `#4AA8A9`, heading `#123F4A`, body `#2D3740`, soft `#f4f7f8`, line `#D8E3E5`).
- **Redesign sidebar**: Switch from dark slate (`#1e293b`) to a light, soft theme with white background, turquesa accents, and subtle borders — matching the clean frontend aesthetic. Includes mobile sidebar behavior.
- **Modernize tables**: Consistent header styling, soft hover states with turquesa tint, rounded containers, subtle row dividers, and styled empty states.
- **Unify forms**: Standardize all inputs, labels, toggles, file uploads, and buttons to a single visual language using shared Blade components.
- **Restyle pagination**: Replace default Livewire pagination with a custom styled view using turquesa accents.
- **Refresh dashboard**: Replace generic multi-color stat cards (blue/emerald/purple) with a cohesive turquesa-toned palette and softer gradients.
- **Redesign auth views**: Login, lock, and forgot-password screens with a split-screen layout — turquesa gradient branding panel on the left, clean form panel on the right. Includes auth layout restyling.
- **Create reusable components**: `ui/empty-state`, `ui/toggle`, `ui/badge-status`, `ui/section-header`, `ui/file-upload`, `ui/stat-card` to eliminate class duplication across views. Also expose them as standard Laravel anonymous components at `resources/views/components/ui-*.blade.php` so `<x-ui-stat-card>` and similar tags resolve without custom component namespaces.
- **Softer shadows and transitions**: Replace near-invisible shadows with soft, layered elevations. Add smooth hover transitions throughout.
- **Remove dark mode classes**: Strip all `dark:` utility classes from CMS views — the CMS is light-only. This prevents broken dark: styles after the palette change.
- **Restyle third-party notifications**: Override SweetAlert2 and Toastify default colors to use turquesa brand accents.
- **Mobile sidebar**: Wire up the existing hamburger button with an Alpine.js slide-in overlay — the button exists but has no behavior currently.
- **No functional changes**: This is purely a look-and-feel redesign. No controllers, models, routes, migrations, or business logic are modified. No Livewire component PHP files are touched — only Blade views and CSS.
- **Run full test suite after completion**: Execute all existing Feature and Unit tests (`php artisan test`) to verify that every controller, CRUD flow, and Livewire component still works correctly with the new UI. Tests inject data via `Livewire::test()` to validate form submissions, edits, deletions, and pagination across all modules.

## Capabilities

### New Capabilities

- `cms-ui-design-system`: Shared design tokens, reusable Blade components, and unified styling guidelines for the entire CMS interface.

### Modified Capabilities

_None — no spec-level behavior changes. All modifications are visual/CSS only._

## Impact

- **CSS/Build**: `resources/cms/css/app.css`, `tailwind.config.js` — color tokens, component classes.
- **Blade views**: All 41 CMS views under `resources/views/cms/` — class updates, component adoption.
- **Blade components**: `resources/views/cms/components/ui/` — new components added, existing ones updated; plus `resources/views/components/ui-*.blade.php` — standard anonymous component copies to ensure `<x-ui-...>` tags resolve correctly.
- **Layouts**: `resources/views/cms/layouts/dashboard.blade.php` and `resources/views/cms/layouts/auth.blade.php` — sidebar, header, footer, auth wrapper restyling.
- **Livewire pagination**: Custom pagination view to replace default Tailwind pagination.
- **No backend changes**: Controllers, models, routes, migrations, and business logic remain untouched.
- **No breaking changes**: All Livewire bindings, wire directives, and form submissions stay identical.
