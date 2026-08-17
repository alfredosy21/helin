## Purpose

Defines the visual design system for the Helin CMS: unified color palette, reusable Blade components, and consistent styling rules that align the admin interface with the frontend brand identity. All requirements are purely visual — no functional behavior changes.

## ADDED Requirements

### Requirement: Unified color palette

The CMS SHALL use the same color tokens as the public frontend. The primary color SHALL be turquesa `#6BC2C3` with its dark variant `#4AA8A9` for hover states. Heading text SHALL use `#123F4A`, body text SHALL use `#2D3740`, soft backgrounds SHALL use `#f4f7f8`, and borders SHALL use `#D8E3E5` (exposed as `line` in Tailwind config). These tokens SHALL be defined in `tailwind.config.js` and `resources/cms/css/app.css` CSS variables.

#### Scenario: Color consistency between front and CMS

- **WHEN** a user navigates from the public site to the CMS
- **THEN** the primary accent color, text colors, and border colors SHALL match the frontend palette

#### Scenario: Tailwind utility classes reflect brand colors

- **WHEN** a developer uses `bg-primary`, `text-primary`, `border-primary` in CMS views
- **THEN** the rendered color SHALL be `#6BC2C3` or its corresponding shade from the configured palette

### Requirement: Light-themed sidebar

The CMS sidebar SHALL use a light theme with a white background and a right border in `#D8E3E5` (`line`). The logo area SHALL use turquesa `#6BC2C3` as background with white text. Active navigation items SHALL display a turquesa-tinted background (`primary/10`) with turquesa text and a left turquesa accent bar (`border-l-2 border-primary-500`). Inactive items SHALL show slate text that transitions to turquesa on hover. Section group labels (e.g. CATALOG, CONTENT) SHALL use `text-xs font-semibold text-slate-400 uppercase tracking-wider`.

#### Scenario: Sidebar visual appearance

- **WHEN** the CMS loads with the sidebar visible
- **THEN** the sidebar background SHALL be white, not dark slate

#### Scenario: Active navigation state

- **WHEN** the user is on a specific CMS section
- **THEN** the corresponding sidebar item SHALL have a `bg-primary/10` background, `text-primary-600` text color, and a left turquesa accent bar

### Requirement: Consistent table styling

All CMS data tables SHALL use a standardized pattern: white container with `rounded-xl`, `border-line` border, and `shadow-sm`. A search/filter bar SHALL sit at the top with `border-b border-line`. Table headers SHALL use the soft background (`#f4f7f8`) with uppercase, tracking-wider text in heading color. Row dividers SHALL be subtle (`border-line`). Row hover state SHALL apply a turquesa tint (`bg-primary/5`). Action buttons SHALL be icon-only with `p-2 rounded-lg text-slate-400 hover:text-primary hover:bg-primary/5`. Empty states SHALL display a centered icon in a turquesa-tinted circle with a title and description. A result count text ("Showing X–Y of Z results") SHALL appear before pagination.

#### Scenario: Table hover interaction

- **WHEN** the user hovers over a table row
- **THEN** the row background SHALL transition to a soft turquesa tint

#### Scenario: Empty table state

- **WHEN** a table has no records
- **THEN** the system SHALL display a centered icon inside a `bg-primary/5` circle with a heading-colored title and a muted description

#### Scenario: Table result count

- **WHEN** a paginated table displays records
- **THEN** a text showing "Showing X–Y of Z results" SHALL appear in the pagination footer section

### Requirement: Unified form inputs

All form inputs in the CMS SHALL share a consistent visual style: white background, `border-line` borders, `rounded-xl` corners, and a focus state with turquesa border and `ring-primary/20`. Labels SHALL use `text-xs font-semibold text-heading uppercase tracking-wider`. Required fields SHALL show a red asterisk. Toggle switches SHALL use turquesa when active. File upload areas SHALL use a dashed `border-line` with turquesa hover border and a turquesa-tinted icon.

#### Scenario: Input focus state

- **WHEN** a user focuses a text input, textarea, or select
- **THEN** the border SHALL become turquesa and a soft `ring-primary/20` glow SHALL appear

#### Scenario: Toggle switch active state

- **WHEN** a toggle switch is enabled
- **THEN** the toggle background SHALL be turquesa `#6BC2C3`

### Requirement: Styled pagination

CMS pagination SHALL use a custom Livewire pagination view with turquesa accents. The current page button SHALL have `bg-primary-500 text-white`. Navigation buttons SHALL use `rounded-lg` with a turquesa hover state. Prev/next buttons SHALL use arrow icons with a disabled state at reduced opacity. Pagination SHALL be wrapped in a `border-t border-line bg-soft/50` footer section within the table card. A result count text ("Showing X–Y of Z results") SHALL appear on the left side of the pagination footer.

#### Scenario: Current page indicator

- **WHEN** the user is on page 2 of a paginated list
- **THEN** the page "2" button SHALL have a turquesa background and white text

#### Scenario: Pagination disabled state

- **WHEN** the user is on the first or last page
- **THEN** the previous/next navigation buttons SHALL be visually disabled with reduced opacity

### Requirement: Reusable Blade components

The CMS SHALL provide reusable Blade components to eliminate class duplication: `ui/empty-state`, `ui/toggle`, `ui/badge-status`, `ui/section-header`, `ui/file-upload`, `ui/stat-card`. Each component SHALL accept props for customization and render consistent markup using the unified design tokens.

#### Scenario: Empty state component usage

- **WHEN** a developer includes `<x-ui-empty-state icon="package" title="Sin productos" />` in a view
- **THEN** the component SHALL render a centered icon circle, title, and optional description with the correct brand styling

#### Scenario: Badge status component

- **WHEN** a developer includes `<x-ui-badge-status :active="$model->is_active" />`
- **THEN** the component SHALL render a turquesa dot with "Activo" text for active items, or a gray dot with "Inactivo" for inactive items

#### Scenario: File upload component

- **WHEN** a developer includes `<x-ui-file-upload wire:model="image" />` in a view
- **THEN** the component SHALL render a dashed border drop zone with a turquesa hover state and cloud-upload icon

#### Scenario: Stat card component

- **WHEN** a developer includes `<x-ui-stat-card icon="package" value="150" label="Productos" trend="+5.2%" trend-up />`
- **THEN** the component SHALL render a turquesa gradient card with icon circle, large value, label, and a trend indicator with up/down arrow

### Requirement: Dashboard brand-aligned cards

Dashboard stat cards SHALL use turquesa-toned backgrounds and gradients instead of arbitrary colors (blue, emerald, purple). Each card SHALL use `from-primary/5 to-primary/10` gradient backgrounds with turquesa icon containers (`bg-primary/10` circle). Each stat card SHALL display a value in `text-heading text-2xl font-bold`, a label, and a trend indicator showing percentage change with an up/down arrow. Quick action buttons SHALL use `bg-primary/5 hover:bg-primary/10` with turquesa icon accents. The activity feed SHALL display recent items with avatars, timestamps in `text-xs text-slate-400`, and status badges.

#### Scenario: Dashboard stat card appearance

- **WHEN** the CMS dashboard loads
- **THEN** all stat cards SHALL use turquesa-tinted backgrounds matching the brand palette

#### Scenario: Stat card trend indicator

- **WHEN** a dashboard stat card displays a trend
- **THEN** the card SHALL show a percentage value with an up or down arrow icon in green or red respectively

### Requirement: Soft shadows, transitions, and border radius

All CMS cards SHALL use soft, layered shadows (`shadow-sm` to `shadow-md` with `shadow-black/5`). Interactive elements SHALL have smooth transitions (`duration-200`) on hover. Buttons SHALL have a subtle scale effect on hover (`hover:scale-[1.02]`). Card border radius SHALL be `rounded-xl` (12px), not `rounded-3xl` (24px). Modal border radius SHALL be `rounded-2xl` (16px). Input and button radius SHALL be `rounded-xl` and `rounded-lg` respectively.

#### Scenario: Card elevation

- **WHEN** a card is displayed on the CMS
- **THEN** the shadow SHALL be soft and visible, not near-invisible

#### Scenario: Card border radius

- **WHEN** a card component renders
- **THEN** the border radius SHALL be 12px (`rounded-xl`), not 24px (`rounded-3xl`)

### Requirement: Auth views with split-screen layout

Login, lock, and forgot-password screens SHALL use a split-screen layout. The left panel (hidden on mobile) SHALL display a turquesa gradient `from-primary-500 to-primary-700` with the Helin logo, tagline, and subtle pattern overlay in white text. The right panel SHALL display the form on a white/soft background with `rounded-xl shadow-md shadow-primary/10` card. Input focus states SHALL use turquesa. The submit button SHALL use a solid turquesa background. On mobile screens, the left panel SHALL collapse into a compact header bar.

#### Scenario: Login page appearance

- **WHEN** a user navigates to the CMS login page on a desktop
- **THEN** the left panel SHALL show a turquesa gradient with branding, and the right panel SHALL show the form with turquesa accents

#### Scenario: Login page on mobile

- **WHEN** a user navigates to the CMS login page on a mobile screen
- **THEN** the left branding panel SHALL collapse into a compact header bar and the form SHALL take full width

### Requirement: Mobile sidebar

The CMS sidebar SHALL collapse on mobile screens (below `lg` breakpoint). A hamburger menu button in the header SHALL toggle a slide-in sidebar overlay using Alpine.js. The mobile sidebar SHALL use the same light theme and turquesa accents as the desktop sidebar. The overlay SHALL dim the main content with a semi-transparent `bg-black/30` backdrop.

#### Scenario: Mobile sidebar toggle

- **WHEN** a user taps the hamburger menu button on a mobile screen
- **THEN** the sidebar SHALL slide in from the left with a semi-transparent backdrop behind it

#### Scenario: Mobile sidebar close

- **WHEN** a user taps outside the sidebar or selects a navigation item
- **THEN** the sidebar SHALL slide out and the backdrop SHALL disappear

### Requirement: Dark mode class removal

All `dark:` utility classes SHALL be removed from CMS Blade views and layouts. The CMS is light-only and the existing `dark:` classes reference the old palette, which will break after the color token migration. This includes `dark:bg-gray-900`, `dark:text-gray-100`, `dark:border-gray-700`, etc. in `dashboard.blade.php`, `auth.blade.php`, and all 41 views.

#### Scenario: No dark mode classes remain

- **WHEN** a developer searches for `dark:` in CMS Blade files after the redesign
- **THEN** no `dark:` utility classes SHALL be present in `resources/views/cms/`

### Requirement: Third-party notification styling

SweetAlert2 confirm buttons and Toastify toast notifications SHALL use turquesa brand accents instead of their default blue/gray colors. CSS overrides SHALL be added to `resources/cms/css/app.css` to style `.swal2-confirm` with `bg-primary-500` and Toastify toast backgrounds with turquesa tones.

#### Scenario: SweetAlert2 confirm button

- **WHEN** a confirmation dialog appears (e.g., delete confirmation)
- **THEN** the confirm button SHALL have a turquesa background matching the CMS primary color

#### Scenario: Toastify notification

- **WHEN** a toast notification appears
- **THEN** the toast background SHALL use turquesa tones for success/info types instead of default blue

### Requirement: Full test suite validation

After all visual changes are complete, the existing test suite SHALL be executed with `php artisan test` to verify that no functional behavior was broken. All tests in `tests/Feature/Cms/` (CRUD tests, access tests, feature tests), `tests/Unit/`, and `tests/Feature/Web/` MUST pass without modification. These tests inject data via `Livewire::test(Controller::class)->set('field', 'value')->call('save')` to simulate real form submissions across all CMS modules. Any test failure indicates an accidental functional change that must be reverted.

#### Scenario: All CMS CRUD tests pass

- **WHEN** the test suite is executed after the redesign
- **THEN** all CRUD tests (`AdminCrudTest`, `CatalogCrudTest`, `ProductsCrudTest`, `AttributesCrudTest`, `BlogCrudTest`, `ConfigCrudTest`, `ResourcesCrudTest`, `SettingsPageSeoCrudTest`, `TestimonialsCrudTest`) SHALL pass without modification

#### Scenario: All CMS access and feature tests pass

- **WHEN** the test suite is executed after the redesign
- **THEN** access tests (`CmsAccessTest`), feature tests (`CommercialRequestsTest`, `ContactMessagesTest`, `DashboardProfileTest`, `PermissionSystemTest`), unit tests (`CmsModelsTest`, `SubmoduleTest`), and web tests (`WebFunctionalTest`, `WebSmokeTest`) SHALL pass without modification

#### Scenario: No test files modified

- **WHEN** a developer checks git status after the redesign
- **THEN** no files under `tests/` SHALL appear as modified — the redesign touches only Blade views and CSS, not test logic
