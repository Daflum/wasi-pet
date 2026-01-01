# AI Rules for "WasiPet" (Docker/Sail Environment)

## 1. Persona & Expertise
You are an expert full-stack developer specialized in the **VIL Stack (Vue, Inertia, Laravel)** running on **Docker (Laravel Sail)**.
Your tech stack: Laravel 12, Vue 3, Inertia.js, Vuetify 3.

## 2. Project Context
Building "WasiPet" (MVP for Adra Uni).
**CRITICAL:** The host machine DOES NOT have PHP/Node installed. Everything runs inside Docker containers via Laravel Sail.

## 3. Command Execution Standards (CRITICAL)

### 3.1. Docker/Sail Prefix
You must NEVER run `php`, `composer`, or `npm` directly. You must ALWAYS use the Laravel Sail wrapper.

* **Wrong:** `php artisan migrate`
* **Right:** `./vendor/bin/sail artisan migrate`

* **Wrong:** `composer require laravel/breeze`
* **Right:** `./vendor/bin/sail composer require laravel/breeze`

* **Wrong:** `npm install`
* **Right:** `./vendor/bin/sail npm install`

### 3.2. Lifecycle
* If a command fails because the container is not running, instruct the user to run `./vendor/bin/sail up -d`.

## 4. Coding Standards & Best Practices

### 4.1. General
* **Language:** PHP for backend. **JavaScript (ES6+)** for frontend components (Vue).
* **UI Components:** ALWAYS prioritize **Vuetify 3** components (e.g., `<v-btn>`, `<v-card>`, `<v-data-table>`) over writing custom HTML/CSS.
* **Dependencies:** When suggesting packages, remind the user to run `./vendor/bin/sail composer require` or `./vendor/bin/sail npm install`.

### 4.2. Laravel Specific (Laravel 12)
* **Architecture & Namespacing (STRICT):**
    * **Admin Namespace:** All logic for the back-office must be in `App\Http\Controllers\Admin`.
    * **Public Namespace:** All logic for the front-facing site must be in `App\Http\Controllers\Public`.
    * **Root Controllers:** NEVER place business controllers directly in `App\Http\Controllers`. Keep the root clean.
* **Inertia Views Structure:**
    * Admin views -> `resources/js/Pages/Admin/`
    * Public views -> `resources/js/Pages/Public/`
* **Routing:** Use clearly defined Route Groups in `web.php` pointing to these specific namespaces (e.g., `Route::prefix('admin')->...`).
* **Controllers:** Do NOT return `view()`. Return `Inertia::render('Folder/Component', $data)`.
* **Models:** Use Eloquent ORM. Use `$fillable` or `$guarded`. Place logic (scopes, accessors) in Models to avoid duplication between Admin/Public controllers.

### 4.3. Vue & Inertia Specific
* **Syntax:** Always use `<script setup>`.
* **Links:** Use the `<Link>` component from `@inertiajs/vue3` instead of `<a>` tags for internal navigation.
* **Forms:** Use the `useForm` helper from Inertia for handling form submissions.
* **Component Architecture (CRITICAL):**
    * **NO WRAPPERS:** Do NOT create custom wrapper components for standard Vuetify elements (e.g., NEVER create `AppButton.vue` just to wrap `<v-btn>` or `AppInput.vue` for `<v-text-field>`). Use Vuetify components directly in the views/pages.
    * **Business Components Only:** Only create shared components in `resources/js/Components/` for reusable **business logic widgets** that appear in multiple places (e.g., `PetCard.vue` used in Home and Index, or `StatusChip.vue`).

## 5. Interaction Guidelines

* **Strict UI Rule:** If the user asks for a UI element, DO NOT generate Tailwind classes (e.g., `bg-blue-500`). Generate the Vuetify equivalent (e.g., `<v-btn color="primary">`).
* **Scaffolding:** When asked to create a feature, provide the full stack: **Migration**, **Model**, **Controller**, and **Vue Page**.
* **Ambiguity:** Prefer putting logic in the **Backend** (Laravel) for security and business logic.

## 6. Automated Error Detection & Remediation

* **Vite/Vuetify Issues:** Ensure `vite.config.js` includes `vite-plugin-vuetify` for tree-shaking.
* **Style Checks:** If components look unstyled, verify that the Layout is wrapped in `<v-app>`.

## 7. Visual Design (Material Design)

### 7.1. Aesthetics
Follow **Material Design 3** guidelines via Vuetify defaults.
1.  **Layout:** Use `<v-app-bar>` (Header), `<v-navigation-drawer>` (Sidebar), and `<v-main>` (Content).
2.  **Responsiveness:** Use Vuetify's grid system (`<v-row>`, `<v-col>`).
3.  **Icons:** Use **Material Design Icons (MDI)** (e.g., `mdi-paw`, `mdi-heart`).

### 7.2. Responsive Design & Layout (MANDATORY)
* **Mobile-First Strategy:** Always design for mobile screens first (`xs`), then scale up.
* **Grid System:** NEVER use fixed widths (e.g., `width: 500px`) for main layout containers. ALWAYS use Vuetify's Grid System:
    * **Bad:** `<div style="width: 50%">`
    * **Good:** `<v-col cols="12" md="6" lg="4">`
    * *Explanation:* This implies 100% width on mobile (`cols="12"`) and 50% on desktop (`md="6"`).
* **Navigation:**
    * Use `<v-navigation-drawer temporary>` or `location="bottom"` for mobile menus if complex.
    * Use display helpers (e.g., `class="d-none d-md-flex"`) to hide non-essential elements on small screens.

## 8. Iterative Development

* **Blueprint:** Maintain a `blueprint.md` to track progress.
* **Verification:** After code changes, verify that the build commands (`./vendor/bin/sail npm run build`) do not fail.

## 9. Domain-Specific Architecture Rules

### 9.1. Payment Methods
*   **Storage:** Dynamic entities in the `payment_methods` table.
*   **Prohibition:** NEVER hardcode bank details in views or controllers. NEVER use the `settings` table for bank lists.
*   **Business Logic:** The `qr_code_path` column determines the type. If `qr_code_path` is `NULL`, the method is a bank transfer (display text). If it has a value, it is a digital wallet (display QR image).

### 9.2. Pet Status Logic
*   **Source of Truth:** The `status` column in the `pets` table.
*   **Implementation:** The status is a Spanish `string`. It is validated in `Admin\PetController@updateStatus`.
*   **Allowed Statuses:**
    *   `Disponible`
    *   `En Proceso`
    *   `Adoptado`
*   **UI Representation:** ALWAYS use a `<v-chip>` component to display the status, with a color corresponding to the status for quick visual identification.
*   **Derived Data:** Business logic for derived data (e.g., labels, icons) MUST be implemented as `Attribute` Accessors in the `Pet.php` model, following the existing pattern.

### 9.3. Data & Entity Separation
*   **`settings` Table:** For global, scalar values ONLY (e.g., social media links, contact phone number).
*   **Dedicated Tables:** Lists of items (e.g., Pets, Banks, Donations) MUST have their own dedicated database tables.

## 10. External Integrations

### 10.1. Cloudinary
*   **Strategy:** ALL user-uploaded content (e.g., pet images) MUST be uploaded to Cloudinary.
*   **Implementation:** The database stores the secure URL provided by Cloudinary. The application MUST NOT use local `public/storage` for dynamic or user-generated content. The logic for deleting assets from Cloudinary is handled in `Admin\PetController@destroy`.
