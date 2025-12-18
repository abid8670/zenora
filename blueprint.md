# Project Blueprint

## Overview

This project is a full-stack web application built with Laravel and Filament for the admin panel. The application is designed to manage assets, employees, domains, hosting, and projects. The dashboard provides a statistical overview of these resources and tables for actionable insights, organized into logical groups for clarity.

## Implemented Features

### Employee Management

*   **Password Field:** Added a `password` field to the `Employee` resource.
    *   The password is required when creating a new employee.
    *   The password is automatically hashed before being saved to the database.
    *   The password field is `revealable`, allowing users to see the password they are typing.
*   **Compact UI:** The employee form has been made more compact by changing the layout from 2 columns to 3 columns.

### Domain Management

*   **Balanced UI:** The domain form layout has been reverted to a 2-column layout to ensure a balanced and clean user interface without empty spaces.

### Access Point Management

*   **Revealable Passwords:** The password fields in the "Management" tab and the "Associated SSIDs" repeater are now revealable.

### Subnet Management

*   **Scoped Unique Validation:** The `subnet_address` validation is now scoped to the `office_id`, allowing the same subnet address to be used in different offices, but preventing duplicates within the same office.

### Dashboard Widgets

*   **Assets & Employees Group:**
    *   **Stats Overview:** Displays key statistics about assets and employees (Total Assets, Total Employees, In Stock, Assigned).
    *   **Filters:** Allows filtering stats by office and asset category.

*   **Domain Management Group:**
    *   **Stats Overview:** Displays key statistics about domains (Total, Active, Expiring Soon, Expired).
    *   **Expiring Domains Table:** Lists domains expiring within 7 days. Includes a "Renew" action that opens a form with a date picker to set a new expiry date. Displays the correct number of days remaining.

*   **Hosting Management Group:**
    *   **Stats Overview:** Displays key statistics about hosting accounts (Total, Active, Expiring Soon, Expired).
    *   **Expiring Hostings Table:** Lists hosting accounts expiring within 7 days. Includes a "Renew" action that opens a form with a date picker to set a new expiry date. Displays the correct number of days remaining.

*   **Project Management Group:**
    *   **Recent Projects Table:** Displays the 5 most recent projects.

### Form Validations

*   **Domain Form:** The `registrar` field is now a required field to prevent database errors.

### Code Modifications

*   **`database/migrations`:** Created a new migration to add the `password` column to the `employees` table. The `subnets` table migration already had the correct composite unique key for `subnet_address` and `office_id`.
*   **`app/Filament/Resources/EmployeeResource.php`:**
    *   Added a `password` `TextInput` to the form.
    *   Made the password field required on create, and optional on edit.
    *   Added the `dehydrateStateUsing` method to hash the password before saving.
    *   Added the `revealable` method to the password field.
    *   Changed the form layout to 3 columns for a more compact UI.
*   **`app/Filament/Resources/DomainResource.php`:**
    *   Made the `registrar` field mandatory to fix the `NOT NULL` constraint violation.
    *   Reverted the form layout to 2 columns for a more balanced UI.
*   **`app/Filament/Resources/AccessPointResource.php`:**
    *   Added `->revealable()` to the password field in the "Management" tab.
    *   Added `->revealable()` to the password field within the "Associated SSIDs" repeater.
*   **`app/Filament/Resources/SubnetResource.php`:**
    *   Added a `unique()` validation rule to the `subnet_address` field, scoped to the `office_id`.
    *   Corrected a `TypeError` by changing the type hint in the validation closure from `Rule` to `Unique`.
*   **`app/Filament/Widgets/ExpiringDomains.php`:** Updated the "Renew" action to use a `DatePicker` form and corrected the calculation for the "Days to Expire" column.
*   **`app/Filament/Widgets/ExpiringHostings.php`:** Updated the "Renew" action to use a `DatePicker` form and corrected the calculation for the "Days to Expire" column.
*   **`app/Providers/Filament/ZenoraPanelProvider.php`:** Registered the `ExpiringDomains` and `ExpiringHostings` widgets.

## Current Request

### Request

The user reported a `TypeError` in the `SubnetResource` when trying to create a new subnet. The error indicated an incorrect type hint in the `unique` validation rule's closure.

### Steps Taken

1.  **Analyze Error:** Examined the stack trace and identified the root cause: the closure for `modifyRuleUsing` was type-hinting `Illuminate\Validation\Rule` instead of the correct `Illuminate\Validation\Rules\Unique`.
2.  **Fix Type Hint:**
    *   Modified `app/Filament/Resources/SubnetResource.php`.
    *   Corrected the type hint in the closure from `Rule` to `Unique`.
3.  **Update `blueprint.md`:** Documented the bug fix, explaining the cause and the resolution.
4.  **Notified the User:** Confirmed that the `TypeError` has been resolved.
