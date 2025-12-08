
# Zenora - IT Infrastructure Management Suite

## 1. Project Overview

Zenora is a comprehensive, web-based IT infrastructure management application built using the Laravel framework and the Filament admin panel. It serves as a centralized dashboard for monitoring and managing all critical IT assets and services within an organization. This tool is designed to replace scattered spreadsheets and documents with a single, secure, and collaborative platform, providing administrators with a clear and real-time view of their entire IT landscape.

---

## 2. Implemented Features & Modules

### 1. Main Landing Page
-   **URL:** `/` (Root URL)
-   **Design:** A modern, visually appealing landing page built with Tailwind CSS, introducing the "Zenora" application.
-   **Functionality:** Provides clear navigation for different user roles.
    -   **Admin Login Button:** Directs administrators to the Filament admin panel (`/admin`).
    -   **Get Support Button:** Directs users to the public support ticket form (`/support`).

### 2. Public-Facing Support Form
-   **URL:** `/support`
-   **Functionality:** Allows any user to create a new support ticket without needing to log in.
-   **Design:** A clean, responsive form built with Tailwind CSS.
-   **Fields:** Full Name, Email Address, Office Location, Support Category, Subject, Problem Description.
-   **Feedback:** Displays a success message upon successful submission.

### 3. Core Admin Dashboard (Filament)
-   **Authentication:** Secure login for administrators.
-   **Dashboard Widgets:** Provides at-a-glance statistics and summaries of key infrastructure components.

### 4. User & Office Management
-   **Modules:** `Users`, `Offices`, `Cities`
-   **Functionality:** Manage company offices, city locations, and administrator accounts. Assign users to specific offices.

### 5. Domain & Hosting Management
-   **Modules:** `Domains`, `Hostings`, `Projects`
-   **Functionality:** Track domain registration/expiry, hosting account details, and associated web projects.

### 6. Server & Network Infrastructure
-   **Modules:** `Servers`, `Server Types`, `Subnets`, `Access Points`, `WiFi SSIDs`
-   **Functionality:** Catalogue all physical and virtual servers, define server roles, manage IP subnets, and keep an inventory of all wireless access points and their SSIDs.

### 7. ISP & Connectivity Management
-   **Module:** `ISPs`
-   **Functionality:** A comprehensive system to manage all Internet Service Provider connections. 
-   **Fields:** Office, Connection Name, Provider, Speed, Circuit/Customer ID, Connection Type, Location, Static IP, Status, Billing Details, Portal Credentials, and Installation Date.
-   **Navigation:** Located under the "Network" group with a `globe-alt` icon.

---

## 3. Current Task: P2P / VPN Links Module

**Objective:** To create a new module for managing all Point-to-Point (P2P) radio links and Virtual Private Network (VPN) connections between company offices.

**Action Plan:**

1.  **Migration & Model:**
    -   Create a `p2p_links` table with fields for link type, status, connected offices, device/server credentials, and VPN-specific details.
    -   Generate a `P2pLink` model.

2.  **Filament Resource:**
    -   Create a `P2pLinkResource` to manage the records.
    -   **Navigation:** Place it in the "Network" group with the `heroicon-o-arrows-right-left` icon.
    -   **Form UI:**
        -   Use a Tabbed layout:
            -   **Tab 1 (Link Details):** `name`, `link_type` (Select: P2P Radio, Site-to-Site VPN), `status`, `office_a_id`, `office_b_id`.
            -   **Tab 2 (Device/Server Access):** `device_url`, `username`, `password`. For managing the radio link device or the main VPN server.
            -   **Tab 3 (VPN Client Credentials):** `vpn_server_ip`, `vpn_user`, `vpn_password`. These fields will be conditionally visible only if `link_type` is a VPN.
            -   **Tab 4 (Remarks):** A textarea for additional notes.
    -   **Table View:** Display key columns: `name`, `link_type`, `status`, and the names of the two connected offices.
