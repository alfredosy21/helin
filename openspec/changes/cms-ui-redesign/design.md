## Context

The CMS currently uses `#09b6a2` as its primary color while the public frontend uses `#6BC2C3`. The sidebar is dark slate (`#1e293b`), disconnected from the light, clean frontend. 41 Blade views duplicate inline Tailwind classes instead of using shared components. Existing `ui/*` components (`input`, `button`, `card`) are largely unused in favor of manual styling. See `proposal.md` for motivation.

**Tech stack:** Laravel + Livewire + Tailwind CSS + Alpine.js + Vite. Tailwind config at `tailwind.config.js`, CMS CSS at `resources/cms/css/app.css`.

**Visual references:** Two admin dashboard templates serve as design inspiration, both adapted to Helin's turquesa palette:
- **Aquiry** (codebucks.kcubeinfotech.com/aquiry) — Preferred primary reference. Clean, soft, modern aesthetic with light sidebar, rounded cards, stat cards with trend indicators, clean tables with avatars/images, and split-screen auth. Matches the "clean, soft, modern, not robotic" goal.
- **Fila** (templates.envytheme.com/fila) — Secondary reference for dashboard widget patterns (stat cards with % trends, top selling lists, recent orders tables, activity feeds).

**Design principles extracted from references:**
1. **Soft elevation** — Cards use `shadow-sm` to `shadow-md` with `shadow-black/5`, never heavy or harsh shadows
2. **Rounded everything** — `rounded-xl` (12px) for cards and inputs, `rounded-lg` (8px) for buttons and badges
3. **Breathing room** — Generous padding (`p-6` for cards, `py-4` for table cells), no cramped layouts
4. **Subtle interactions** — Hover states with gentle color transitions (`duration-200`), not jarring jumps
5. **Visual hierarchy via color, not weight** — Use turquesa tints (`primary/5`, `primary/10`) for backgrounds, heading color for titles, body color for text
6. **Clean tables** — Rows with avatars/thumbnails where applicable, status badges with dots, action icon buttons with tooltips, "Showing X–Y of Z" text before pagination
7. **Stat cards with context** — Each stat shows a value + trend indicator (% up/down) + small description, not just a number
8. **Empty states as design moments** — Centered icon in a soft circle, title, description, optional CTA — not just plain text

## Goals / Non-Goals

**Goals:**
- Unify CMS colors with frontend palette in a single source of truth (`tailwind.config.js` + CSS variables)
- Create reusable Blade components to replace duplicated inline classes
- Restyle all 41 CMS views to use the new design system
- Achieve a clean, soft, modern aesthetic — not robotic

**Non-Goals:**
- No changes to controllers, models, routes, migrations, or business logic
- No changes to Livewire component PHP files (only Blade views and CSS)
- No new JavaScript functionality beyond wiring up the existing hamburger button with Alpine.js for mobile sidebar (the button already exists in the layout but has no `@click` handler)
- No dark mode implementation — all existing `dark:` classes SHALL be removed from CMS views since the CMS is light-only. Leaving them would result in broken styles after the palette change.
- No changes to the public frontend

## Decisions

### 1. Color tokens in `tailwind.config.js`

**Decision:** Replace the current `primary` palette (`#09b6a2`-based) with the frontend's turquesa palette. Adjusted for better contrast between shades (the original 400/500 were too similar).

| Shade | Current | New | Usage |
|-------|---------|-----|-------|
| 50 | `#e6f9f7` | `#f0fafa` | Subtle backgrounds, hover tints |
| 100 | `#b3ece6` | `#d8f2f2` | Badges, chips, selection states |
| 200 | `#80dfd4` | `#b0e6e6` | Borders on focus, light accents |
| 300 | `#4dd2c2` | `#8fdada` | Disabled states, dividers |
| 400 | `#1ac5b1` | `#7cd0cf` | Secondary buttons, icons |
| 500 | `#09b6a2` | `#6BC2C3` | Primary buttons, links, active states |
| 600 | `#079282` | `#4AA8A9` | Hover states, pressed buttons |
| 700 | `#056e61` | `#3A9A9B` | Active sidebar items, strong accents |
| 800 | `#044a41` | `#2A7A7B` | Dark accents, footer |
| 900 | `#022520` | `#1A5A5B` | Dark text on turquesa backgrounds |

Add semantic colors: `heading: '#123F4A'`, `body: '#2D3740'`, `soft: '#f4f7f8'`, `line: '#D8E3E5'`.

**Rationale:** Single source of truth. All views reference `primary-500` etc., so changing the config propagates everywhere. The shade progression is recalculated for even perceptual steps (the original 400→500 gap was only ~3% lightness, now it's ~8%). Avoids hard-coded hex values in individual views.

**Alternative considered:** CSS variables only (no Tailwind config change). Rejected because Tailwind utility classes like `bg-primary` wouldn't reflect the new colors without config changes.

### 2. Sidebar: light theme (inspired by Aquiry light mode)

**Decision:** Replace `bg-[#1e293b]` with `bg-white border-r border-line`. Logo area uses `bg-primary-500` with white text. Active items: `bg-primary/10 text-primary-600` with a left turquesa accent bar (`border-l-2 border-primary-500`). Hover: `bg-slate-50 text-heading`. Section labels (CATALOG, CONTENT, etc.) use `text-xs font-semibold text-slate-400 uppercase tracking-wider`.

Mobile behavior (below `lg`): sidebar hidden by default, hamburger button in header toggles a slide-in overlay with `bg-black/30` backdrop. Same light theme and turquesa accents.

**Rationale:** A light sidebar matches the clean, soft frontend aesthetic. Aquiry's light mode demonstrates this pattern effectively — white background, colored active states, subtle hover. The dark sidebar creates a visual disconnect.

**Alternative considered:** Keep dark sidebar but change to turquesa-dark. Rejected — still feels heavy and disconnected from the light frontend.

### 3. Component-first approach

**Decision:** Create new Blade components and refactor views to use them. New components:

- `ui/empty-state` — icon in `bg-primary/5` circle, title in heading color, description in body color, optional action slot
- `ui/toggle` — styled switch with `bg-primary-500` when active, accepts `wire:model` via `$attrs`, optional label prop
- `ui/badge-status` — active/inactive badge: turquesa dot + "Activo" / gray dot + "Inactivo"
- `ui/section-header` — breadcrumb + title + optional action button slot; replaces repeated header markup in every view
- `ui/file-upload` — dashed `border-line` drop zone with turquesa hover, cloud-upload icon, preview support, accepts `wire:model` via `$attrs`
- `ui/stat-card` — dashboard stat card with icon, value, label, trend indicator (% up/down with arrow), turquesa gradient background

Update existing components:
- `ui/input` — fix focus state to `border-primary focus:ring-2 focus:ring-primary/20`, add `bg-white border-line rounded-xl`
- `ui/button` — fix `primary` variant to `bg-primary-500 hover:bg-primary-600`, fix `link` variant to `text-primary-600`
- `ui/card` — add `border-line` to bordered variant, softer shadows `shadow-md shadow-black/5`, default `rounded-xl`
- `ui/icon` — no changes needed, already uses `text-primary-600` variant which will auto-update from Tailwind config. Verify no hardcoded `text-[#09b6a2]` in views that override icon color.
- `ui/label` — update from `text-[#c0c1c6]` to `text-slate-400` or `text-heading` depending on context
- `ui/textarea` (class-based component, no Blade file in `components/ui/`) — update to match `ui/input` styling: `bg-white border-line rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20`. May need to locate the class in `app/View/Components/` or check if it's an anonymous component resolved by Laravel's component auto-discovery.
- `x-cms-breadcrumb` (used in 28+ views) — update text colors from `text-slate-500`/`text-[#09b6a2]` to `text-body`/`text-primary-600`, ensure separator icons use turquesa

**Rationale:** Components eliminate the massive class duplication across 41 views. Future style changes only need to touch the component file. The `stat-card` component is inspired by both Aquiry and Fila's dashboard stat cards.

**Alternative considered:** Find-and-replace across all views. Rejected — fragile, error-prone, and doesn't prevent future duplication.

### 4. CSS variables in `app.css`

**Decision:** Update CSS variables in `:root` to match the new palette. Update component classes (`.btn-primary`, `.input`, `.card`, `.badge-*`, `.nav-link-active`, etc.) to use the new tokens. Soften border-radius from `rounded-3xl` (24px) to `rounded-xl` (12px) on cards and `rounded-2xl` (16px) on modals — the current 24px feels excessive and toy-like; 12px is closer to Aquiry's clean aesthetic. Replace all hardcoded `bg-[#f8fafc]` with `bg-soft` (`#f4f7f8`) and all hardcoded `text-[#c0c1c6]` with `text-slate-400`. Add SweetAlert2 and Toastify CSS overrides using turquesa confirm button colors and turquesa toast accents.

**Rationale:** Some views use CSS component classes (`.btn`, `.input`, `.card`) rather than Tailwind utilities directly. These need to reflect the new colors too. The radius reduction aligns with the modern, clean aesthetic of the reference templates. SweetAlert2 and Toastify are loaded via CDN in `dashboard.blade.php` and their default blue/gray colors clash with the brand. A small CSS override block in `app.css` handles this without changing the CDN includes.

### 5. Livewire pagination custom view

**Decision:** Create a custom Livewire pagination view at `resources/views/cms/components/ui/pagination.blade.php`. Register it globally by publishing Livewire's pagination views (`php artisan livewire:publish --pagination`) or by setting `paginationView` in a base Livewire component class. The view renders:
- "Showing X–Y of Z results" text in `text-sm text-body`
- Page buttons with `rounded-lg px-3 py-1.5 text-sm` and turquesa hover
- Active page with `bg-primary-500 text-white`
- Prev/next with arrow icons, disabled state at `opacity-40 cursor-not-allowed`
- Wrapped in a `border-t border-line bg-soft/50 px-4 py-3` footer section

**Rationale:** Default Livewire pagination is unstyled and breaks the visual flow. Both Aquiry and Fila show clean pagination with result counts. A custom view ensures consistency across all 41 views.

**Alternative considered:** CSS-only override via `.pagination` class. Rejected — Livewire's default markup doesn't have enough class hooks for reliable styling.

**Note:** If the project uses Livewire 3, the preferred approach is creating a Blade file at `resources/views/livewire/pagination.blade.php` and referencing it via `->paginationView('livewire.pagination')` in the component's `render()` method, or globally via `Livewire::paginationView()` in a service provider.

### 6. Dashboard layout (inspired by Aquiry + Fila)

**Decision:** Redesign `dashboard/index.blade.php` with:
- **Stat cards row** — 4 cards using `ui/stat-card` component: Total Products, Total Requests, Pending Requests, Active Brands. Each with turquesa gradient `from-primary/5 to-primary/10`, icon in `bg-primary/10` circle, value in `text-heading text-2xl font-bold`, trend indicator with up/down arrow
- **Activity feed** — Recent commercial requests and contact messages in a clean list with avatars, timestamps in `text-xs text-slate-400`, status badges
- **Quick actions** — Grid of action buttons with `bg-primary/5 hover:bg-primary/10` cards, turquesa icons, labels in `text-heading`

**Rationale:** Both reference templates use this pattern effectively. The current dashboard has random colors (blue, emerald, purple, green) that don't match the brand. Turquesa gradients create a cohesive, soft, modern look.

### 7. Auth layout (inspired by Aquiry split-screen)

**Decision:** Redesign auth layout with a split-screen pattern:
- **Left panel** (hidden on mobile) — Turquesa gradient `from-primary-500 to-primary-700` with the Helin logo, tagline, and a subtle pattern overlay. White text.
- **Right panel** — White/soft background with the form card. Form card uses `rounded-xl shadow-md shadow-primary/10` with turquesa input focus states.
- **Mobile** — Stacks vertically, left panel becomes a compact header bar.

**Rationale:** Aquiry's split-screen auth is modern and clean. The current centered card is functional but plain. The split screen reinforces brand identity on the left while keeping the form clean on the right.

**Alternative considered:** Keep centered card with glassmorphism. Rejected — glassmorphism can look muddy and the split screen is more modern.

### 8. Table pattern (inspired by Aquiry product list)

**Decision:** Standardize all CMS tables with this pattern:
- **Container** — `bg-white rounded-xl border border-line shadow-sm overflow-hidden`
- **Search/filter bar** — `px-4 py-3 bg-white border-b border-line` with search input (icon prefix) and per-page select
- **Header row** — `bg-soft text-xs font-semibold text-heading uppercase tracking-wider px-6 py-3`
- **Body rows** — `border-t border-line hover:bg-primary/5 transition-colors duration-150 px-6 py-4`
- **Status badges** — Use `ui/badge-status` component
- **Action buttons** — Icon-only buttons with `p-2 rounded-lg text-slate-400 hover:text-primary hover:bg-primary/5`
- **Empty state** — Use `ui/empty-state` component in a `py-16` cell
- **Pagination footer** — Custom pagination view with "Showing X–Y of Z" text

**Rationale:** Aquiry's product list demonstrates this clean, scannable table pattern. The current tables have inconsistent padding, no hover tint, and default pagination.

### 9. Phased implementation

**Decision:** Implement in 6 phases to keep changes manageable and reviewable:

1. **Foundation** — `tailwind.config.js`, `app.css`, CSS variables, update existing `ui/*` components
2. **Layout** — `dashboard.blade.php` (sidebar, header, footer, mobile nav)
3. **New components** — `empty-state`, `toggle`, `badge-status`, `section-header`, `file-upload`, `stat-card`, pagination view
4. **Dashboard + Auth** — `dashboard/index.blade.php`, auth layout, `auth/login.blade.php`, `auth/lock.blade.php`, `auth/forgot-password.blade.php`
5. **CRUD views** — All remaining views, batch by module (catalog, content, settings, users, requests, attributes)
6. **Verification & Testing** — Build, visual QA, run full test suite

**Rationale:** Each phase is independently testable. Foundation must come first because everything depends on the color tokens. Components (phase 3) must exist before views can adopt them (phase 5).

### 10. Full test suite execution

**Decision:** After all visual changes are complete, run the existing test suite with `php artisan test` (or `php artisan test --parallel` for speed). The project has 20+ test files covering:

- **CMS CRUD tests** (`tests/Feature/Cms/`): `AdminCrudTest`, `AttributesCrudTest`, `BlogCrudTest`, `CatalogCrudTest`, `ConfigCrudTest`, `ProductsCrudTest`, `ResourcesCrudTest`, `SettingsPageSeoCrudTest`, `TestimonialsCrudTest` — each injects data via `Livewire::test(Controller::class)->set('field', 'value')->call('save')` and validates create, edit, delete flows
- **CMS access tests**: `CmsAccessTest` — verifies auth and route protection
- **CMS feature tests**: `CommercialRequestsTest`, `ContactMessagesTest`, `DashboardProfileTest`, `PermissionSystemTest`
- **Unit tests**: `CmsModelsTest`, `SubmoduleTest`
- **Web tests**: `WebFunctionalTest`, `WebSmokeTest`

All tests use `RefreshDatabase` and `Livewire::test()` to simulate real form submissions with injected data. Since the redesign only touches Blade views and CSS (no PHP logic changes), all tests MUST pass without modification. Any test failure after the redesign indicates an accidental functional change that must be reverted.

**Rationale:** The existing test suite provides comprehensive coverage of all controllers and Livewire components. Running it after the redesign validates that no `wire:model` bindings, form submissions, pagination, or CRUD operations were broken by the visual changes. This is the definitive proof that the redesign is purely cosmetic.

**Alternative considered:** Manual click-through testing. Rejected — the automated test suite is faster, more thorough, and covers edge cases that manual testing misses.

## Risks / Trade-offs

- **[Risk] Visual regression across 41 views** → Mitigation: Phased approach. After each phase, visually verify the affected views. Foundation phase changes are global but low-risk (color swaps).
- **[Risk] Tailwind purge removing new color classes** → Mitigation: The `content` config already scans `resources/**/*.blade.php`, so new classes in components and views will be included. Verify with `npm run dev` after changes.
- **[Risk] Livewire re-renders losing Alpine state** → Mitigation: No Alpine or Livewire changes. Only CSS classes and Blade markup change. All `wire:*` directives remain untouched.
- **[Risk] Split-screen auth layout on small screens** → Mitigation: Left panel hides on mobile, becomes compact header. Form panel takes full width.
- **[Trade-off] Light sidebar reduces contrast** → Acceptable because the CMS is used in office environments, not outdoors. Turquesa accents and the left accent bar on active items provide sufficient visual hierarchy.
- **[Trade-off] Component refactoring takes longer than find-replace** → Worth it for long-term maintainability and consistency.
- **[Trade-off] Reduced border-radius (24px→12px)** → The softer radius feels more professional and less toy-like, matching the reference templates.
