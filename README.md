---

## Project Context

SparkMind is a **university group project** developed as part of an academic course.  
The **main pedagogical objective** is the **integration of multiple existing modules** (forum, events, donations, products, chatbot, etc.) into **one unified PHP MVC application**, while preserving some legacy code.  

- The project combines:
  - a more recent **object‑oriented MVC layer** (`Controllers/`, `Models/`, `Views/`),
  - with several **legacy / procedural modules** (`controller/`, `model/`, `view/`, `view/omar`).
- The work was done **collaboratively by a student team**, with shared Git history and incremental refactors.
- The codebase is **educational**, not a commercial product; it intentionally shows real‑world integration issues between old and new modules.

---

## Project Overview

SparkMind is a **community‑based solidarity platform** that centralizes:

- **Help requests** and support offers (front forms, admin validation).
- **Donations and groups** (legacy “aide” modules for managing donations and groups).
- **Events and reservations**, with **ticket management and QR codes** for check‑in.
- A **forum / post wall** with comments, reactions, and donation types.
- A **product module** (Omar’s module) for listing and viewing products with QR codes.
- A **back‑office / administration** area for users, help requests, forum content, and statistics.
- **Chatbot / AI assistance** to support users and moderators (where implemented in the codebase).

The goal is to offer a **single entry point** for these features via a shared router (`index.php?page=...`), while maintaining compatibility with historical modules.

---

## Main Features (Overview)

- **Authentication & User Profiles**
  - User registration, login, logout, password reset (`AuthController`).
  - Profile display and editing, profile photo upload, account deletion (`ProfileController`).

- **Forum (Posts, Comments, Likes, Reactions)**
  - Post list, detail, creation, editing, deletion (`post_list`, `post_detail`, `post_store`, `post_edit`, `post_update`, `post_delete`).
  - Comments (`comment_add`), notification pages, and integration with donation types.
  - AI helpers (`AIHelper`, `TrendAnalyzer`) to analyse trends and assist moderation (where used).

- **Events & Reservations (with Tickets & QR Codes)**
  - Event CRUD and dashboard (`events_dashboard`, `events_list`, `event_create`, `event_edit`, `event_update`, `event_show`, `event_delete`).
  - Public event pages and booking flow (`events_home`, `events_list_public`, `event_detail`, `booking_form`, `my_reservations`, `reservation_detail_public`).
  - Ticket management, QR‑code based scanning (`events_scan`) and ticket services (`TicketService`, `utils/QrGenerator*.php`).

- **Donations & Groups**
  - Legacy donation and group management via procedural controllers (`donC.php`, `groupeC.php`).
  - Front and backoffice screens for listing, creating, and viewing donations and groups (`frontoffice`, `browse_dons`, `browse_groupes`, `create_don`, `create_groupe`, `view_don`, `view_groupe`, `aide_dons`, `aide_don_create`, `aide_don_stats`, `aide_groupes`, `aide_create_groupe`).

- **Help Requests & Notifications**
  - Front forms for offering support and submitting help requests (`offer_support`, `demande`, `reponse` views).
  - Administration of help requests from the backoffice (`admin_help_requests`, `admin_help_request_action`).
  - Notification pages for end users (`notifications` route and `NotificationController`).

- **Product Marketplace (Omar Module)**
  - Product listing and details view under `view/omar` (`liste_produits.php`, `detailsfront.php`, `ajouterProduit.php`, etc.).
  - Product categories (`categorieC.php`, `ajouter_categorie`) and historic category management docs.
  - Product QR codes (generated from product data in `detailsfront.php`).

- **Administration / Backoffice**
  - Admin home and dashboards (`admin_home`).
  - User management (listing, blocking, unblocking, deletion, profile inspection: `admin_users`, `admin_user_profile`, `admin_block_user`, `admin_unblock_user`, `admin_delete_user`).
  - Forum moderation backoffice (`admin_forum`, `admin_forum_posts`, `admin_forum_comments`, `admin_forum_types`, `admin_forum_ai`).
  - Legacy backoffice views (`backoffice`, `backoffice_aide`, `view/Backoffice/`).

- **Chatbot / AI Assistance**
  - Dedicated controllers (`AIController.php`, `ChatbotController.php`).
  - Models and helpers (`AIHelper.php`, `Chatbot.php`, `TrendAnalyzer.php`) used to provide contextual assistance and content analysis.
  - Configuration through `Config/chatbot_config.php`.

---

## Technical Stack

- **Language & Architecture**
  - PHP with a **custom MVC architecture** (single front controller `index.php` and controller/model/view separation).

- **Database**
  - **MySQL / MariaDB** database named `sparkmind`.
  - Access mainly via **PDO** (parameterized queries) in the newer modules.

- **Web Server & Environment**
  - **Apache** via **XAMPP** (recommended development stack).
  - Project expected to be placed in `htdocs` (e.g. `C:\xampp\htdocs\sparkmind_mvc_100percent`).

- **Frontend**
  - **HTML / CSS / JavaScript** (custom templates, no heavy frontend framework).
  - Static assets located in `assets/` and `public/assets/`.

- **Utilities & Services**
  - **PDO‑based configuration** in `Config/config.php` and `config/config.php`.
  - Mail service (`services/MailService.php`), ticket service (`services/TicketService.php`).
  - QR code utilities in `utils/QrGenerator.php` and `utils/QrGeneratorAdvanced.php`.

---

## Installation & Setup (Local Environment)

### Prerequisites

- XAMPP with:
  - PHP (version consistent with your XAMPP distribution, typically **7.4+**),
  - MySQL / MariaDB,
  - Apache HTTP server.
- A recent web browser.

### 1. Copy the Project into `htdocs`

1. Clone or copy the folder `sparkmind_mvc_100percent` into:
   - `C:\xampp\htdocs\` on Windows,
   - or the equivalent `htdocs` / `www` directory on your system.
2. The base URL will then be:
   - `http://localhost/sparkmind_mvc_100percent/`

### 2. Create and Import the `sparkmind` Database

1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`.
2. Create a database named **`sparkmind`**.
3. Import at least:
   - `database.sql` (main schema and data),
   - optionally `database_ticket_migration.sql`, `test_data.sql` if you need ticket‑related data or test fixtures.
4. Internal documentation such as `index_documentation.html`, `README_*.md` files and `*_GUIDE*.md` may describe additional migrations or constraints.

### 3. Configure Database Connection Files

Default XAMPP credentials are preconfigured in `Config/config.php` and `config/config.php`:

```php
$host = 'localhost';
$db   = 'sparkmind';
$user = 'root';
$pass = '';
```

If your MySQL credentials differ, update `$user`, `$pass` and possibly `$db` accordingly.

### 4. Start Apache & MySQL

1. Launch the **XAMPP Control Panel**.
2. Start **Apache** and **MySQL**.
3. Ensure no other services are blocking ports 80/443/3306.

### 5. Access the Application via `localhost`

- **Main front page**  
  `http://localhost/sparkmind_mvc_100percent/index.php?page=front`

- **Some useful routes**
  - Authentication:
    - `index.php?page=login`
    - `index.php?page=register`
  - Profile:
    - `index.php?page=profile`
  - Admin backoffice:
    - `index.php?page=admin_home`
  - Forum:
    - `index.php?page=post_list`
    - `index.php?page=post_detail&id=1`
  - Events / reservations (admin):
    - `index.php?page=events_dashboard`
    - `index.php?page=events_list`
  - Public events:
    - `index.php?page=events_home`
    - `index.php?page=events_list_public`
  - Product module (Omar):
    - `index.php?page=produits`
    - `index.php?page=liste_produits`
    - `index.php?page=details_produit&id=1`

---

## Project Structure

SparkMind intentionally contains **two levels of architecture**:

- **MVC (new layer)**
  - `Controllers/` – object‑oriented controllers for:
    - front pages (`HomeController.php`),
    - authentication (`AuthController.php`),
    - profiles (`ProfileController.php`),
    - admin and forum admin (`AdminController.php`, `ForumAdminController.php`),
    - events, reservations, notifications, AI, etc.
  - `Models/` – business models:
    - users, posts, comments, reactions, donation types,
    - events and reservations (`EventModel.php`, `Reservation.php`),
    - help requests and notifications,
    - AI helpers (`AIHelper.php`, `TrendAnalyzer.php`, `Chatbot.php`).
  - `Views/` – main views:
    - `Views/front/`, `Views/public/` for front and public pages,
    - `Views/admin/`, `Views/Events/`, `Views/reservations/`, `Views/profile/`, `Views/notifications/`, `Views/ai/`, etc.
    - `Views/layout.php` for shared layout.

- **Legacy / Historical Modules**
  - `controller/`, `model/`, `view/` – procedural code for:
    - donations (`donC.php`, `donmodel.php`, related views in `view/Frontoffice` and `view/Backoffice`),
    - groups (`groupeC.php`, `groupemodel.php`),
    - products (`produitC.php`, `view/omar/*.php`),
    - categories (`categorieC.php`, `Model/categorie.php`).
  - These modules are progressively integrated but still use their own routing and templates.

- **Shared Assets and Utilities**
  - `assets/` and `public/assets/` – CSS, JS, shared images.
  - `uploads/`, `controller/uploads/`, `public/uploads/` – user‑generated content (post images, product images, etc.).
  - `services/` – services such as `MailService.php` and `TicketService.php`.
  - `utils/` – technical helpers (QR code generation, etc.).
  - `Config/` and `config/` – configuration files, including database connection and chatbot/Stripe configuration.

The **coexistence** of `Controllers/` / `Models/` / `Views/` with `controller/` / `model/` / `view/` is **intentional**: it reflects the integration of **older course modules** into a newer MVC structure during the project.

---

## Routing System

The application uses a **single entry point**:

- `index.php` at the project root, which reads the `page` query parameter:
  - `index.php?page=<route_name>`

Routing is implemented through a `switch` statement in `index.php` that:

- dispatches to **MVC controllers** (e.g. `HomeController`, `AuthController`, `ProfileController`, `AdminController`, `ForumAdminController`),
- or includes **legacy PHP views/controllers** directly from `view/`, `Views/`, and `controller/`.

**Examples of existing routes** (non‑exhaustive but all present in `index.php`):

- **Front / public**
  - `index.php?page=front`
  - `index.php?page=front_step`
  - `index.php?page=main`
  - `index.php?page=offer_support`
  - `index.php?page=demande`
  - `index.php?page=reponse`

- **Authentication & profile**
  - `index.php?page=login`
  - `index.php?page=register`
  - `index.php?page=logout`
  - `index.php?page=forgot_password`
  - `index.php?page=reset_password`
  - `index.php?page=profile`
  - `index.php?page=profile_edit`

- **Administration / backoffice**
  - `index.php?page=admin_home`
  - `index.php?page=admin_users`
  - `index.php?page=admin_help_requests`
  - `index.php?page=admin_help_request_action`
  - `index.php?page=admin_user_profile`
  - `index.php?page=admin_delete_user`
  - `index.php?page=admin_block_user`
  - `index.php?page=admin_unblock_user`
  - `index.php?page=admin_forum`
  - `index.php?page=admin_forum_posts`
  - `index.php?page=admin_forum_comments`
  - `index.php?page=admin_forum_types`
  - `index.php?page=admin_forum_ai`
  - `index.php?page=backoffice`
  - `index.php?page=backoffice_aide`

- **Forum / posts / notifications**
  - `index.php?page=post_list`
  - `index.php?page=post_detail&id=1`
  - `index.php?page=post_edit&id=1`
  - `index.php?page=post_store`
  - `index.php?page=post_update`
  - `index.php?page=post_delete`
  - `index.php?page=comment_add`
  - `index.php?page=notifications`

- **Events & reservations**
  - `index.php?page=events_dashboard`
  - `index.php?page=event_create`
  - `index.php?page=events_list`
  - `index.php?page=events_scan`
  - `index.php?page=event_edit&id=1`
  - `index.php?page=event_show&id=1`
  - `index.php?page=event_delete&id=1`
  - `index.php?page=event_update`
  - `index.php?page=reservations_list`
  - `index.php?page=reservation_create`
  - `index.php?page=events_home`
  - `index.php?page=events_list_public`
  - `index.php?page=event_detail&id=1`
  - `index.php?page=booking_form&id=1`
  - `index.php?page=my_reservations`
  - `index.php?page=reservation_detail_public&id=1&email=example@example.com`

- **Donations & groups (legacy “aide” modules)**
  - `index.php?page=frontoffice`
  - `index.php?page=browse_dons`
  - `index.php?page=browse_groupes`
  - `index.php?page=create_don`
  - `index.php?page=create_groupe`
  - `index.php?page=view_don`
  - `index.php?page=view_groupe`
  - `index.php?page=aide_dons`
  - `index.php?page=aide_don_create`
  - `index.php?page=aide_don_stats`
  - `index.php?page=aide_groupes`
  - `index.php?page=aide_create_groupe`

- **Product module (Omar)**
  - `index.php?page=produits`
  - `index.php?page=liste_produits`
  - `index.php?page=ajouter_produit`
  - `index.php?page=details_produit&id=1`
  - `index.php?page=ajouter_categorie`

Any unknown `page` value is redirected back to `index.php?page=front` by the default case in the router.

---

## Security Practices

Security is **partially implemented** and varies between modules, but includes:

- **PDO prepared statements**
  - Newer models use PDO with prepared statements to help prevent SQL injection.

- **Server‑side validation**
  - Controllers validate required fields (e.g. for posts, reservations, comments) before executing database operations.

- **Session‑based authentication**
  - Authentication and authorisation rely on `$_SESSION` (e.g. for logged‑in users, admin access, flash messages).

- **Output escaping**
  - HTML output is often escaped with `htmlspecialchars()` to limit XSS risks in user‑generated content.

- **Data integrity protections**
  - Category and product management modules are documented with specific integrity rules and cascades (`GESTION_CATEGORIES_README.md`, `README_CASCADE.md`, `SUPPRESSION_CASCADE.md`, `SUMMARY_MODIFICATIONS.md`).

**Known security limitations** (to be transparent for academic evaluation):

- CSRF protection is **not consistently implemented** across forms.
- Role / permission management is still **basic** (primarily admin vs regular users).
- File uploads (images, etc.) are controlled but can be **further hardened** (MIME checks, size limits, storage policy).
- Logging, rate limiting, and audit trails are **minimal or absent** in most modules.

---

## Known Limitations

- **Hybrid architecture**
  - Coexistence of new MVC modules and legacy procedural code (`Controllers/` vs `controller/`, `Views/` vs `view/`).

- **Partial refactors**
  - Some features (events, forum, authentication) are more refactored than others (historical donation/product modules), leading to heterogeneous code style.

- **Integration complexity**
  - Different coding conventions, database access patterns, and layouts had to be merged, which creates some duplication and technical debt.

- **Academic time constraints**
  - Certain improvements (global error handling, full test coverage, unified design system) are not fully implemented due to course deadlines.

---

## Future Improvements

- **Full MVC refactor**
  - Migrate remaining legacy modules (`controller/`, `model/`, `view/`) into the unified MVC layer; deprecate duplicated structures.

- **API REST**
  - Expose REST APIs for events, reservations, posts, products, and donations for integration with external clients or mobile apps.

- **Improved security**
  - Add CSRF tokens, strengthen role‑based access control (RBAC), enforce stricter validation and file upload policies, and add logging/auditing.

- **Automated tests**
  - Introduce unit and integration tests (e.g. with PHPUnit) for controllers, models, and critical services.

- **UI/UX consistency**
  - Unify the visual design between legacy and new modules (common layout, navigation, and design system).

- **Better AI integration**
  - Use AI modules more consistently for moderation, recommendation, summarisation, and contextual help across the platform.

---

## Team & Academic Context

- SparkMind was built as a **group project** by university students.
- The repository is used in a **university context** for learning, practice, and evaluation, not as a startup or commercial product.
- The project has an **educational purpose**: practise PHP/MVC, database design, legacy integration, and teamwork.
- The current version corresponds to the **2025–2026 academic year**, with incremental improvements over earlier course iterations.

---

## Additional Documentation

Several supplementary documents are included in the repository for specific topics:

- `GUIDE_DEMARRAGE.md` – quick start & legacy event module.
- `SUMMARY_MODIFICATIONS.md` – summary of important changes (e.g. protected category deletion).
- `GESTION_CATEGORIES_README.md`, `README_CASCADE.md`, `SUPPRESSION_CASCADE.md` – category management and referential integrity.
- `FIX_IMAGES_FRONTOFFICE.md`, `FIX_PHOTOS.md`, `NOUVEAU_DESIGN.md` – front‑office images and design evolution.

For module‑level details, please refer directly to:

- the corresponding controllers (`Controllers/`, `controller/`),
- models (`Models/`, `Model/`),
- and views (`Views/`, `view/`) within the codebase.
