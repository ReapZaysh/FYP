# Q&A Project Defense — Bossku House QR Digital Ordering System

This document contains comprehensive, highly polished answers to the critical technical defense questions for the **Bossku House** Final Year Project (FYP).

---

## 1. What are the strengths of your module?

### ⚡ Hybrid Real-Time Architecture
The system uniquely combines **Laravel 12** on the backend (providing secure routing, validation rules, Eloquent ORM skeleton, and PDF compilation via DomPDF) with the **Firebase Realtime Database (NoSQL)**. This bypasses expensive WebSocket servers (like Pusher or Soketi) or resource-heavy HTTP polling, allowing kitchen/staff dashboards and customer tracking screens to sync status changes and sound/vibration alerts instantly under 100ms.

### 🍃 Zero-Friction Guest Experience
Customers do not need to undergo a frustrating sign-up process to place orders or track preparation progress. By extracting table IDs directly from QR URLs (e.g., `/menu/table-5`) and mapping order states in browser `localStorage`, the frontend dynamically displays tracking timelines and handles real-time alerts while keeping the operational flow frictionless.

### 🔒 Secure loyalty points & Voucher Validation
To prevent NoSQL client-side data tampering, points validation, calculations, and deductions are processed entirely on the backend server before updating Firebase. Loyalty points are calculated strictly on the backend via `floor($orderTotal)` during cashier checkout, and voucher status (`is_used`) is checked atomically.

### 👥 Granular Role-Based Access Control (RBAC)
The architecture implements secure user roles (`admin`, `staff`, `customer`). Admin and staff credentials are authenticated via standard Laravel session guards mapping to Eloquent models, while customers are validated through a custom `FirebaseUserProvider` that pulls user records dynamically from Firebase NoSQL.

---

## 2. If you had three more months, what improvements would you make?

### 📶 Offline-First PWA (Progressive Web App)
I would configure the customer ordering module as a PWA with a Service Worker and **IndexedDB** local storage. If a customer's cellular connection or the restaurant's Wi-Fi drops, they could still browse the cached menu and add items to their cart. Once connectivity is restored, the queued order payload would automatically sync to Firebase.

### 💳 Direct e-Wallet & Payment Gateway Integration
Currently, payments are verified manually by cashiers at the counter. I would integrate payment gateways (such as Stripe, FPX, or Touch 'n Go eWallet) directly into the customer checkout flow. This would allow automated payment status transitions in Firebase immediately upon successful transaction completion.

### 🖨️ Direct Hardware-Level Thermal Printing (ESC/POS)
Instead of forcing cashiers to open standard browser print dialogs, I would write a background microservice utilizing native **ESC/POS command protocols** to auto-print tickets directly to network-connected thermal printers in the kitchen the second an order status changes to "Submitted".

### 📊 Advanced Prediction & Inventory Tracking
I would expand the Admin Analytics module by adding simple forecasting algorithms based on historical order metrics to predict busy hours and estimate ingredient usage (e.g., alert staff when featured items are selling out).

---

## 3. What is the biggest technical challenge encountered in this project?

### 🧩 UUID-to-Integer Type Casting in Laravel Auth Session
* **Context**: Laravel's default session guard maps authentication IDs to integer keys. 
* **The Challenge**: Customer identifiers are stored in Firebase NoSQL as UUID strings (e.g., `3f91a92f-eb71-4ad4-bc90-7ee065e547ca`). When Eloquent loaded these records, it cast the alphanumeric UUID string into an integer (`3`) due to the default `$keyType = 'int'` and `$incrementing = true` configuration. This caused Laravel to store `3` in the session, and subsequent page loads looked up `users/3` in Firebase, resulting in silent authentication failures.
* **The Resolution**: I configured the `User` model to explicitly use a string primary key (`protected $keyType = 'string'; public $incrementing = false;`) and wrote a custom `FirebaseUserProvider` implementing `UserProvider` to properly capture and bridge NoSQL document structures into Laravel's auth ecosystem.

### 🌐 Ephemeral Filesystem & SMTP Network Port Blocking on Railway
* **Context**: The production environment was hosted on Railway, which operates on an ephemeral container filesystem and blocks standard SMTP ports (25, 465, 587) to prevent spam.
* **The Challenge**: Standard SQLite databases reset on every deployment, destroying verification tokens and local file stores. Simultaneously, Gmail SMTP connection attempts timed out due to Railway’s network limitations, blocking registration OTPs and password resets.
* **The Resolution**:
  1. I bypassed the relational SQLite database for password resets by building a fully custom Firebase-backed reset token manager (`savePasswordResetToken`).
  2. I routed outgoing emails to the application log engine (`MAIL_MAILER=log`) and wrote distinctive, high-visibility log markers (`========== OTP CODE ==========`). This allowed both customers and developers to easily verify codes by inspecting the Railway Deploy Logs.

---

## 4. If the project were restarted today, what would you do differently?

### 🛢️ Unified Real-Time Database Structure (e.g. Supabase or Firestore)
Instead of maintaining a hybrid setup (SQLite for local framework cache/sessions and Firebase RTDB for live business operations), I would use a single database engine like **Supabase (PostgreSQL)** or **Firestore**. Supabase provides relational data integrity, schema migrations, and native WebSockets for real-time tracking, eliminating the need to write custom synchronization logic (`syncOrder`) to bridge SQL catalog seeders with NoSQL trees.

### 🌐 Decoupled Single Page Application (SPA) Frontend
I would decouple the customer ordering interface from Laravel Blade templates and build it as a lightweight SPA using **Next.js** or **Vue**. This would provide smoother page transitions, instant client-side rendering, and a cleaner separation between the client application and the backend API (Laravel acting purely as a stateless JSON API).

### 🔑 Native Firebase Authentication SDK
Rather than bridging credentials manually through Laravel's session guards and provider drivers, I would initialize the client-side Firebase Authentication SDK. This would simplify third-party social integrations (Google/Apple login) and enable direct database rules validation (`security.rules`) based on active token payloads.
