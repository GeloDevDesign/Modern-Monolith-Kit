# Module Documentation

> Auto-maintained by AI. Last updated: 2026-02-19

---

## Authentication
Handles user login, registration, password reset, and email verification.

- **Login**: Email/password authentication
- **Register**: New user registration with validation
- **Forgot Password**: Email-based password reset flow
- **Email Verification**: Post-registration email verification

**Files**: `AuthController.php`, `Pages/Auth/Login.vue`, `Pages/Auth/Register.vue`, `Pages/Auth/ForgotPassword.vue`, `Pages/Auth/ResetPassword.vue`, `Pages/Auth/VerifyEmail.vue`

---

## Users
Manage all system users, roles, and permissions.

- **List/View**: Browse and view user details
- **Add**: Create new users with role assignment
- **Edit**: Update user info, roles, and status

**Files**: `UserController.php`, `Pages/Users/Show.vue`, `Pages/Users/View.vue`, `Pages/Users/Add.vue`, `Pages/Users/Edit.vue`, `Models/User.php`

---

## Profile
Allows authenticated users to manage their own account.

- **Edit Profile**: Update personal information
- **Partials**: Modular sections (info, password, etc.)

**Files**: `ProfileController.php`, `Pages/Profile/Edit.vue`, `Pages/Profile/Partials/`

---

## Settings
System-wide configuration managed by admins.

- **General Settings**: App-level settings (name, timezone, etc.)

**Files**: `SystemSettingController.php`, `Pages/Settings/General.vue`, `Models/SystemSetting.php`

---

## Activity Logs
Track and audit user actions across the system (powered by Spatie Activity Log).

- **Index**: View chronological log of all system activities

**Files**: `Admin/ActivityLogController.php`, `Pages/ActivityLogs/Index.vue`

---

## Backups
Database and file backup management (powered by Spatie Backup).

- **Index**: View, create, and download backups

**Files**: `Pages/Backups/Index.vue`

---

## Home (Dashboard)
The main landing page after login. Displays key metrics and quick actions.

**Files**: `Pages/Home.vue`

---
