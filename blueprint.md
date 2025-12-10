# Project Blueprint

## 1. Project Overview

This project is a comprehensive IT infrastructure management dashboard built with the Laravel framework and Filament admin panel. It serves as a central hub for managing various network assets, including offices, sites, domains, hosting, servers, ISPs, P2P links, and VoIP extensions. The application is designed to provide a clear and organized view of the entire IT infrastructure, making it easier to track, manage, and audit network resources.

## 2. Implemented Features & Design

This section documents all the features, design choices, and architectural decisions implemented in the application.

### Core Architecture
*   **Framework:** Laravel 12
*   **Admin Panel:** Filament 3
*   **Database:** Default Laravel setup (likely MySQL or PostgreSQL)
*   **Authentication:** Standard Laravel Breeze/Jetstream with Filament integration.

### Modules & Resources

#### Sites Management
*   **Purpose:** Manages physical locations or data centers where offices are located.
*   **Fields:** Site Name, Status (Active, Inactive).
*   **Relationship:** A Site can have multiple Offices.

#### Offices Management
*   **Purpose:** Manages individual office branches.
*   **Fields:** Office Name, Site (linked via `site_id`), Status.
*   **Relationship:** An Office belongs to a Site.

#### ISP Management
*   **Purpose:** Manages Internet Service Provider (ISP) connections for each office.
*   **Key Features:** Office dropdown shows site for clarity, dynamic IP fields, encrypted credentials.

#### P2P Links Management
*   **Purpose:** Manages Point-to-Point (P2P) radio links, VPNs, or other direct connections between offices.
*   **Key Features:** Detailed tabs for Device A and Device B, endpoint-specific configurations (type, mode, IP, URL, credentials), enhanced office selection, encrypted passwords.

#### VoIP Extensions Management
*   **Purpose:** Manages VoIP phone extensions across all offices.
*   **Fields:** Extension Number (unique), Assigned User, Display Name, Office Location, Status, Remarks.
*   **Key Features:**
    *   **Flexible Assignment:** An extension can be assigned to either a system user (from a dropdown) or a custom display name (e.g., "Conference Room"). The form validates that at least one is provided.
    *   **Smart Identity Column:** The table view displays the user's name if available; otherwise, it shows the custom display name.
    *   **Unique Extension Numbers:** The system validates that each extension number is unique.
    *   **User & Office Linking:** Each extension can be linked to a specific user and an office.
    *   **Enhanced Office Selection:** The office dropdown shows the site name for better context.
    *   **Comprehensive Filtering:** The list view can be filtered by status, office, and site.

### Design & UI/UX
*   **Theme:** Default Filament theme.
*   **Layout:** Clean, tab-based forms for complex resources and simple, two-column layouts for others.
*   **Navigation:** Resources are logically grouped under the "Network" navigation item in the sidebar.
*   **Clarity:** Custom labels and relationship-based option labels are used extensively to provide context and prevent user error.

## 3. Current Task: VoIP Extension Resource Refinement (Completed)

This section outlines the plan and steps that were taken to fulfill the most recent user request.

### A. User Request

The user requested a refinement of the VoIP Extension resource. The requirement was to allow an extension to be associated with either a system user or a custom, manually-typed name (like "Reception"), instead of forcing a selection from the user model.

### B. Execution Plan & Steps Taken

The request was successfully implemented through the following steps:

1.  **Filament Resource Update (`VoipExtensionResource.php`):**
    *   **Form Logic:**
        *   The `user_id` `Select` field was made optional.
        *   The `display_name` `TextInput` field was also made optional.
        *   The `requiredWithout` validation rule was applied to both fields. This masterfully ensures that the form can only be submitted if *at least one* of the two fields is filled, providing maximum flexibility while maintaining data integrity.
    *   **Table View Logic:**
        *   The individual `user.name` and `display_name` columns were removed.
        *   A new, custom `TextColumn` named `identity` was created.
        *   Using `formatStateUsing`, this column was configured to intelligently display the user's name if one is linked, otherwise fallback to showing the `display_name`. This creates a clean and consolidated "Assigned To" column.
        *   The `searchable()` method for this column was configured to search in both the `user.name` and `display_name` fields.

2.  **Blueprint Update:**
    *   The `blueprint.md` file was updated to reflect this new, more flexible implementation of the VoIP Extension module.

### C. Summary of Changes

The VoIP Extension resource is now significantly more flexible. It correctly models the real-world scenario where a phone extension might not belong to a specific system user but to a location or a non-system person. The UI remains clean, and the validation ensures that essential assignment information is never left blank.
