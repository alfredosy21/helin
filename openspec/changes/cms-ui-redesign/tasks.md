## 1. Foundation — Color tokens & CSS

- [x] 1.1 Update `tailwind.config.js`: replace `primary` palette with frontend turquesa values (`#6BC2C3` at 500, `#4AA8A9` at 600, etc. — see design.md shade table), add semantic colors (`heading: '#123F4A'`, `body: '#2D3740'`, `soft: '#f4f7f8'`, `line: '#D8E3E5'`), update `sidebar` tokens for light theme
- [x] 1.2 Update `resources/cms/css/app.css`: replace CSS variables in `:root` with new palette values, reduce border-radius from `rounded-3xl` (24px) to `rounded-xl` (12px) on cards and `rounded-2xl` (16px) on modals, update all component classes (`.btn-primary`, `.input`, `.card`, `.badge-*`, `.nav-link-active`, etc.) to use new tokens, replace hardcoded `bg-[#f8fafc]` with `bg-soft` and `text-[#c0c1c6]` with `text-slate-400`, add SweetAlert2 `.swal2-confirm` and Toastify CSS overrides using turquesa colors
- [x] 1.3 Remove all `dark:` utility classes from `resources/views/cms/layouts/dashboard.blade.php` and `resources/views/cms/layouts/auth.blade.php`
- [x] 1.4 Run `npm run dev` to verify Tailwind picks up new config and no purge issues

## 2. Layout — Sidebar, header, footer, mobile nav

- [x] 2.1 Restyle sidebar in `resources/views/cms/layouts/dashboard.blade.php`: white background with `border-r border-line`, logo area `bg-primary-500` with white text, active items `bg-primary/10 text-primary-600` with `border-l-2 border-primary-500` accent bar, hover `bg-slate-50 text-heading`, section labels `text-xs font-semibold text-slate-400 uppercase tracking-wider`
- [x] 2.2 Restyle header: white background with `border-b border-line`, avatar with `ring-primary/20` ring, add hamburger menu button visible on mobile (`lg:hidden`)
- [x] 2.3 Restyle footer: subtle `bg-soft` with `border-t border-line`
- [x] 2.4 Implement mobile sidebar: Alpine.js toggle for slide-in overlay with `bg-black/30` backdrop, close on outside click or navigation item select
- [x] 2.5 Update flash notification styles to use turquesa accents for info/success types
- [x] 2.6 Remove all `dark:` utility classes from header, footer, and flash notification markup

## 3. New reusable Blade components

- [x] 3.1 Create `resources/views/cms/components/ui/empty-state.blade.php`: props for `icon`, `title`, `description`, optional `action` slot; renders centered icon in `bg-primary/5` circle with heading-colored title
- [x] 3.2 Create `resources/views/cms/components/ui/toggle.blade.php`: styled switch with `bg-primary-500` when active, accepts `wire:model` via `$attrs`, optional label prop
- [x] 3.3 Create `resources/views/cms/components/ui/badge-status.blade.php`: props for `active` boolean, renders turquesa dot + "Activo" / gray dot + "Inactivo"
- [x] 3.4 Create `resources/views/cms/components/ui/section-header.blade.php`: breadcrumb + title + optional action button slot; integrates with existing `x-cms-breadcrumb`
- [x] 3.5 Create `resources/views/cms/components/ui/file-upload.blade.php`: dashed `border-line` drop zone with turquesa hover, cloud-upload icon, preview support, accepts `wire:model` via `$attrs`
- [x] 3.6 Create `resources/views/cms/components/ui/stat-card.blade.php`: props for `icon`, `value`, `label`, `trend`, `trendUp`; renders turquesa gradient card `from-primary/5 to-primary/10` with icon circle, large value, trend indicator with up/down arrow
- [x] 3.7 Create custom Livewire pagination view: published Livewire's default `tailwind` theme to `resources/views/vendor/livewire/tailwind.blade.php` (via `php artisan livewire:publish --pagination`) and restyled with "Showing X–Y of Z results" text, page buttons with `rounded-lg` and turquesa active state, prev/next with arrow icons and disabled opacity, wrapped in `border-t border-line bg-soft/50` footer. Applies globally with zero PHP changes.

## 4. Update existing Blade components

- [x] 4.1 Update `resources/views/cms/components/ui/input.blade.php`: fix focus state to `focus:border-primary focus:ring-2 focus:ring-primary/20`, add `bg-white border-line rounded-xl`
- [x] 4.2 Update `resources/views/cms/components/ui/button.blade.php`: fix `primary` variant to `bg-primary-500 hover:bg-primary-600`, fix `link` variant to `text-primary-600`
- [x] 4.3 Update `resources/views/cms/components/ui/card.blade.php`: change default radius from `rounded-3xl` to `rounded-xl`, add `border-line` to bordered variant, softer shadows `shadow-md shadow-black/5`
- [x] 4.4 Update `resources/views/cms/components/ui/badge.blade.php`: align `badge-primary` and `badge-info` to new turquesa palette
- [x] 4.5 Update `resources/views/cms/components/ui/label.blade.php`: replace `text-[#c0c1c6]` with `text-slate-400` or `text-heading` as appropriate. Note: the hex was actually used inline across 30 CRUD views (not in this component file) — replaced globally with `text-slate-400`.
- [x] 4.6 Locate and update `ui/textarea` component (class-based, no Blade file in `components/ui/`): confirmed no such component exists — textareas are raw `<textarea>` tags duplicated inline across ~23 views with an identical class string; unified that string (and matching `<input>`/`<select>` siblings) to `bg-white border-line rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20` across all affected views. Also added missing `DEFAULT` value to the `primary` color in `tailwind.config.js` so bare `border-primary`/`text-primary`/`bg-primary` utilities resolve.
- [x] 4.7 Update `x-cms-breadcrumb` component (`resources/views/components/cms-breadcrumb.blade.php`): replaced `text-slate-500` with `text-body` for inactive items; active items already used `text-primary-600`

## 5. Dashboard & Auth views

- [x] 5.1 Restyle `resources/views/cms/dashboard/index.blade.php`: replace blue/emerald/purple/green stat cards with `ui/stat-card` component using turquesa gradients, add trend indicators, update activity feed with avatars and timestamps, update quick actions to `bg-primary/5 hover:bg-primary/10`
- [x] 5.2 Redesign `resources/views/cms/layouts/auth.blade.php`: split-screen layout with left turquesa gradient panel (logo, tagline, pattern) and right form panel, mobile collapses left to compact header
- [x] 5.3 Restyle `resources/views/cms/auth/login.blade.php`: use new auth layout, turquesa input focus, turquesa submit button, `rounded-xl shadow-md shadow-primary/10` form card
- [x] 5.4 Restyle `resources/views/cms/auth/lock.blade.php`: same split-screen treatment as login
- [x] 5.5 Restyle `resources/views/cms/auth/forgot-password.blade.php`: same split-screen treatment as login

## 6. CRUD views — Catalog module

- [x] 6.1 Restyle `resources/views/cms/brands/index.blade.php`: adopt standardized table pattern (white container, search bar, soft headers, turquesa hover, icon action buttons), use `ui/empty-state`, `ui/badge-status`, `ui/section-header`, `ui/file-upload` in form, custom pagination, unify form inputs with `border-line` and turquesa focus
- [x] 6.2 Restyle `resources/views/cms/categories/index.blade.php`: same standardized treatment
- [x] 6.3 Restyle `resources/views/cms/lines/index.blade.php`: same standardized treatment
- [x] 6.4 Restyle `resources/views/cms/system-products/index.blade.php`: same standardized treatment
- [x] 6.5 Restyle `resources/views/cms/product-platforms/index.blade.php`: same standardized treatment
- [x] 6.6 Restyle `resources/views/cms/products/index.blade.php`: same standardized treatment

## 7. CRUD views — Content module

- [ ] 7.1 Restyle `resources/views/cms/sections/index.blade.php`: adopt standardized table pattern, use `ui/section-header`, `ui/badge-status`, `ui/empty-state`, custom pagination, remove `dark:` classes, replace hardcoded colors with semantic tokens
- [ ] 7.2 Restyle `resources/views/cms/testimonials/index.blade.php`: same standardized treatment, use `ui/file-upload` for testimonial images
- [ ] 7.3 Restyle `resources/views/cms/resources/index.blade.php`: same standardized treatment, use `ui/file-upload` for resource files
- [ ] 7.4 Restyle `resources/views/cms/resource-types/index.blade.php`: same standardized treatment
- [ ] 7.5 Restyle `resources/views/cms/resource-specialties/index.blade.php`: same standardized treatment
- [ ] 7.6 Restyle `resources/views/cms/blog_articles/index.blade.php`: same standardized treatment, use `ui/file-upload` for article images, `ui/textarea` for content fields
- [ ] 7.7 Restyle `resources/views/cms/blog_categories/index.blade.php`: same standardized treatment

## 8. CRUD views — Settings module

- [ ] 8.1 Restyle `resources/views/cms/settings/index.blade.php`: update form inputs to use `ui/input` and `ui/textarea` components, use `ui/file-upload` for logo/image uploads, replace `text-[#c0c1c6]` labels with `text-slate-400`, remove `dark:` classes
- [ ] 8.2 Restyle `resources/views/cms/menu/index.blade.php`: adopt standardized table pattern for menu items, use `ui/toggle` for visibility, `ui/section-header`, remove `dark:` classes
- [ ] 8.3 Restyle `resources/views/cms/whatsapp-numbers/index.blade.php`: same standardized treatment, use `ui/toggle` for active state
- [ ] 8.4 Restyle `resources/views/cms/page-seo/index.blade.php`: update form inputs, use `ui/textarea` for meta descriptions, remove `dark:` classes
- [ ] 8.5 Restyle `resources/views/cms/payment-methods/index.blade.php`: same standardized treatment, use `ui/toggle` for active state
- [ ] 8.6 Restyle `resources/views/cms/delivery-methods/index.blade.php`: same standardized treatment, use `ui/toggle` for active state
- [ ] 8.7 Restyle `resources/views/cms/customer-types/index.blade.php`: same standardized treatment

## 9. CRUD views — Users & Permissions module

- [ ] 9.1 Restyle `resources/views/cms/users/index.blade.php`: adopt standardized table pattern, use `ui/badge-status` for active users, `ui/section-header`, `ui/empty-state`, custom pagination, remove `dark:` classes
- [ ] 9.2 Restyle `resources/views/cms/roles/index.blade.php`: same standardized treatment
- [ ] 9.3 Restyle `resources/views/cms/permissions/index.blade.php`: same standardized treatment, update permission grid cards with turquesa accents
- [ ] 9.4 Restyle `resources/views/cms/profile/index.blade.php`: update form inputs, use `ui/file-upload` for avatar, remove `dark:` classes

## 10. CRUD views — Requests & Messages module

- [ ] 10.1 Restyle `resources/views/cms/commercial_requests/index.blade.php`: adopt standardized table pattern, use `ui/badge-status` for request status, `ui/section-header`, `ui/empty-state`, custom pagination, remove `dark:` classes
- [ ] 10.2 Restyle `resources/views/cms/contact-messages/index.blade.php`: same standardized treatment, use `ui/badge-status` for read/unread state

## 11. CRUD views — Attributes module

- [ ] 11.1 Restyle `resources/views/cms/attributes/index.blade.php`: adopt standardized table pattern, use `ui/section-header`, `ui/badge-status`, `ui/empty-state`, custom pagination, remove `dark:` classes
- [ ] 11.2 Restyle `resources/views/cms/attribute-values/index.blade.php`: same standardized treatment

## 12. Final verification & testing

### Build & visual verification

- [ ] 12.1 Run `npm run build` to verify production CSS/JS compiles without errors
- [ ] 12.2 Search for `dark:` in `resources/views/cms/` — confirm zero results
- [ ] 12.3 Search for `#09b6a2` and `#1e293b` and `#f8fafc` and `#c0c1c6` in `resources/views/cms/` — confirm zero hardcoded hex values remain (except in comments)
- [ ] 12.4 Visually verify all CMS views render correctly with the new palette
- [ ] 12.5 Verify no Livewire bindings, wire directives, or form submissions are broken
- [ ] 12.6 Verify sidebar navigation, dropdowns, and Alpine interactions still work
- [ ] 12.7 Verify mobile sidebar slide-in works on small screens
- [ ] 12.8 Verify SweetAlert2 confirm dialogs and Toastify toasts use turquesa colors
- [ ] 12.9 Verify Livewire custom pagination renders with turquesa active state and result count text

### Full test suite execution

- [ ] 12.10 Run `php artisan test` (or `php artisan test --parallel`) and confirm ALL tests pass
- [ ] 12.11 Verify CMS CRUD tests pass: `AdminCrudTest`, `AttributesCrudTest`, `BlogCrudTest`, `CatalogCrudTest`, `ConfigCrudTest`, `ProductsCrudTest`, `ResourcesCrudTest`, `SettingsPageSeoCrudTest`, `TestimonialsCrudTest` — these inject data via `Livewire::test(Controller::class)->set('field', 'value')->call('save')` testing create, edit, delete flows
- [ ] 12.12 Verify CMS access tests pass: `CmsAccessTest` — tests auth and route protection
- [ ] 12.13 Verify CMS feature tests pass: `CommercialRequestsTest`, `ContactMessagesTest`, `DashboardProfileTest`, `PermissionSystemTest`
- [ ] 12.14 Verify Unit tests pass: `CmsModelsTest`, `SubmoduleTest`
- [ ] 12.15 Verify Web tests pass: `WebFunctionalTest`, `WebSmokeTest`
- [ ] 12.16 Confirm no files under `tests/` were modified — run `git diff --name-only tests/` and verify empty output
- [ ] 12.17 If any test fails, identify the accidental functional change, revert it, and re-run tests until all pass
