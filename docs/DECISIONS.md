# Architecture & Design Decisions (docs/DECISIONS.md)

## 1. Tenancy Model: Single Database with Global Scopes
- **Decision:** Use a single database multi-tenant architecture with `tenant_id` and `branch_id` on all business models, enforced via `TenantScope` and `BranchScope`.
- **Rationale:** Optimizes resource usage, enables cross-tenant platform reporting for Super Admins, simplifies database migrations and backups, and seamlessly integrates with Laravel Filament panels and Spatie permissions.
- **Data Isolation:** Enforced at the model level via `BelongsToTenant` trait, route middleware `TenantMiddleware`, Filament multi-tenancy registration, and verified via dedicated automated isolation test suites.

## 2. Dynamic Modular Architecture
- **Decision:** Every business capability is packaged as a module inside `app/Modules/<ModuleName>/` with its own registration, migrations, models, services, actions, policies, and Filament resources.
- **Module Toggle Contract:** Each module is registered in `modules` and toggled per tenant in `tenant_modules`. When disabled for a tenant, its navigation items, routes, and API endpoints are hidden and rejected with `403 Module Disabled`.
- **Presets:** Five out-of-the-box presets are supported: "Call Center", "Real Estate Sales Team", "SaaS Sales", "Insurance", and "Solo Freelancer" (minimal modules).

## 3. Dedicated Filament Panels
- **Platform Panel (`/platform`):** Dedicated to Super Admin (Platform Owner) for managing tenants, plans, global AI keys, module catalog, and system health.
- **Business Panel (`/app`):** The primary operational hub for Tenant Owners, Branch Managers, Team Leads, QA Analysts, Trainers, Compliance Officers, HR, and Accountants.
- **Agent Panel (`/agent`):** Streamlined, focused interface for Sales Reps and Agents with quick call review, personal performance metrics, scripts, coaching action items, and practice role-play.
- **Portal Panel (`/portal`):** Client-facing and Partner-facing portal for Customer call review/CSAT and Reseller white-label management.

## 4. Multi-Provider AI Architecture via `laravel-ai-hub` with Deterministic Demo Driver
- **Decision:** Route all transcription, scoring, summarization, objection detection, coaching, and embeddings through `imrandevbd/laravel-ai-hub`.
- **Demo Mode:** When external API keys are not supplied or in offline/demo mode, a built-in deterministic AI analysis engine generates realistic transcriptions, scores (category breakdown, talk-to-listen ratios), sentiment timelines, action items, and coaching insights so the platform runs 100% end-to-end without requiring paid third-party API keys.

## 5. Performance & Speed via `drutogoti`
- **Decision:** Profiling and query optimization are active using `imrandevbd/drutogoti` with asynchronous logging and queue workers, ensuring zero overhead in production.

## 6. PDF & Document Generation via `laravel-unicode-pdf`
- **Decision:** All call scorecards, coaching summaries, agent payslips, invoices, and compliance reports utilize `imrandevbd/laravel-unicode-pdf` with multilingual font fallback (Bangla, Arabic, Hindi, CJK).
