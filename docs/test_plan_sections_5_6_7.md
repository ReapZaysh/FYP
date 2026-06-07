# Test Plan Supplement — Bossku House QR Digital Ordering System
**Version:** 1.0 | **Project:** FYP — Bossku House

---

# 5. TEST ENVIRONMENT

## 5.1 Test Site

### 5.1.1 Developer Site

All functional testing for the **Bossku House** system will be conducted at the **developer's testing site**, which is a local development environment hosted on the developer's personal computer. The system will be run using Laravel's built-in development server (`php artisan serve`) and accessed via a local browser. Testing will simulate the restaurant environment by using multiple browser tabs or devices connected to the same local network.

The test site replicates the actual production environment as closely as possible, using the same codebase, database schema, and Firebase configuration used in the final deployment.

---

## 5.2 Facilities Required

### 5.2.1 Lab Space

Testing will be conducted in a **private study room or home office** environment that provides sufficient space for the tester to operate multiple devices simultaneously (laptop/desktop for admin/staff roles and a mobile device for customer testing). The space must be large enough to accommodate:

- 1 laptop/desktop computer (for admin and staff testing)
- 1 smartphone (for customer QR code scanning and mobile UI testing)
- Any printed QR codes for physical scanning tests

### 5.2.2 Furniture

The test environment requires:

| Item | Quantity | Purpose |
|---|---|---|
| Desk/Table | 1 | To place the laptop, testing notes, and printed QR codes |
| Chair | 1 | For the tester |
| Power strip/Extension cable | 1 | To power all testing devices simultaneously |

### 5.2.3 Power Infrastructure

| Requirement | Detail |
|---|---|
| Power outlets | Minimum 2 standard power sockets |
| Laptop power adapter | Standard laptop charger (compatible with development machine) |
| Mobile device charger | USB-C or Lightning charger for the test smartphone |
| UPS (Uninterruptible Power Supply) | Recommended to prevent data loss during extended test sessions |

All devices must be fully charged or connected to power during testing to avoid interruptions, particularly during Firebase real-time update tests that require sustained connectivity.

### 5.2.4 Communication Facilities

| Facility | Detail |
|---|---|
| Email | Required for tester communication and submission of test reports |
| Messaging (e.g., WhatsApp/Telegram) | For coordination between tester and project supervisor |
| Screen recording software | For documenting test evidence (e.g., OBS Studio, built-in screen recorder) |
| Document sharing | Google Drive or OneDrive for sharing test reports and evidence |

### 5.2.5 Network Connectivity

Network connectivity is **critical** for this project due to Firebase real-time synchronisation.

| Requirement | Specification |
|---|---|
| Internet connection type | Wi-Fi (broadband) or mobile hotspot |
| Minimum download speed | 10 Mbps |
| Minimum upload speed | 5 Mbps |
| Latency | < 100 ms (for real-time Firebase testing) |
| Wi-Fi coverage | Must cover both the laptop and the test smartphone simultaneously |
| Firebase project access | Active Firebase project with Realtime Database enabled |
| Local server | `php artisan serve` running on `http://127.0.0.1:8000` |

> **Note:** All devices used for testing (laptop and smartphone) must be connected to the **same Wi-Fi network** when testing QR code scanning and real-time order tracking features. For cross-device testing, the Laravel server must be accessible via the local network IP (e.g., `http://192.168.x.x:8000`).

---

## 5.3 Required Hardware and Software Specifications

### 5.3.1 Hardware Requirements

| # | Device | Specification | Role |
|---|---|---|---|
| 1 | Laptop / Desktop PC | Intel Core i5 or equivalent, 8 GB RAM, 256 GB SSD, Windows 10/11 or macOS | Development & Testing (Admin, Staff) |
| 2 | Smartphone | Android 10+ or iOS 14+ with a functional camera | Customer testing (QR scanning, mobile UI) |
| 3 | Wi-Fi Router | 802.11n/ac, minimum 100 Mbps | Network connectivity for all devices |
| 4 | Printer (optional) | Any standard inkjet/laser printer | Printing QR codes and test receipts |

### 5.3.2 Software Requirements

| # | Software | Version | Purpose |
|---|---|---|---|
| 1 | PHP | 8.2 or higher | Laravel backend runtime |
| 2 | Laravel Framework | 12.x | Core application framework |
| 3 | Composer | 2.x | PHP dependency management |
| 4 | MySQL / MariaDB | 8.0+ | Relational database for orders, products, users |
| 5 | Node.js & NPM | 18.x LTS | Frontend asset compilation (Vite) |
| 6 | Firebase Realtime Database | N/A (cloud service) | Real-time order status synchronisation |
| 7 | Google Chrome / Firefox | Latest stable version | Primary browser for testing |
| 8 | Safari (iOS) | Latest | Mobile browser testing on iPhone |
| 9 | XAMPP / Laragon | Latest | Local Apache + MySQL server environment |
| 10 | Git | 2.x | Version control |
| 11 | Visual Studio Code | Latest | Code editor and debugging |
| 12 | Postman (optional) | Latest | API endpoint testing |
| 13 | barryvdh/laravel-dompdf | ^2.x | PDF receipt generation testing |
| 14 | Kreait Laravel Firebase | ^5.x | Firebase SDK for Laravel |

---

## 5.4 Testing Group — Tester Involved

The testing team for the **Bossku House** system consists of the following members:

| # | Name | Role | Responsibilities |
|---|---|---|---|
| 1 | [Your Name] | Developer / Primary Tester | Responsible for executing all 28 functional test cases, recording results, and preparing the test report. Also responsible for unit-level testing of controllers and models. |
| 2 | [Supervisor Name] | Project Supervisor | Reviews test plans, provides guidance on test coverage, and validates that testing meets FYP requirements. |
| 3 | [Peer Tester / Friend Name] | User Acceptance Tester (UAT) | Simulates the role of a real customer by performing QR scanning, menu browsing, and order placement. Provides feedback on usability and mobile responsiveness. |
| 4 | [Staff Tester Name] | Staff Role Tester | Simulates the staff role by testing the orders dashboard, status updates, cashier view, and receipt generation. |

**Roles and Access Levels for Testing:**

| Role | System Access | Test Coverage |
|---|---|---|
| Guest Customer | Unauthenticated — Menu, Cart, Order, Tracking | TC-03 to TC-08 |
| Authenticated Customer | Authenticated — All guest features + Rewards, Reviews, Profile | TC-01, TC-02, TC-09 to TC-11 |
| Staff | `role = staff` — Staff dashboard, cashier, receipts, history | TC-12 to TC-18 |
| Admin | `role = admin` — Full admin panel | TC-19 to TC-28 |

---

## 5.5 Preparation and Training Required of the Test Team

Before testing begins, the following preparation and training activities must be completed:

| # | Activity | Responsible | Duration |
|---|---|---|---|
| 1 | **Environment Setup** — Install PHP 8.2, Composer, Node.js, MySQL, and configure the `.env` file with DB and Firebase credentials. | Developer | 1 day |
| 2 | **Database Seeding** — Run `php artisan migrate --seed` to populate the database with test categories, products, users (admin/staff/customer), and rewards. | Developer | 2 hours |
| 3 | **Firebase Configuration** — Ensure the Firebase Realtime Database is active and the Laravel Firebase SDK is correctly configured with the service account JSON key. | Developer | 2 hours |
| 4 | **Test Case Walkthrough** — Brief all testers on the test cases assigned to them, including expected inputs, outputs, and how to document results. | Developer | 2 hours |
| 5 | **QR Code Preparation** — Generate and print test QR codes for at least 3 table numbers (e.g., Table 1, Table 2, Table 5) for physical scanning tests. | Developer | 1 hour |
| 6 | **Mobile Device Setup** — Ensure the smartphone can access the development server via the local network IP address. | Developer | 30 minutes |
| 7 | **Test Account Creation** — Create test accounts for each role: admin, staff, and customer (with pre-loaded loyalty points for reward testing). | Developer | 30 minutes |
| 8 | **UAT Briefing** — Train the peer/UAT tester on how to use the system from a customer perspective, including QR scanning and order tracking. | Developer | 1 hour |

---

# 6. TEST SCHEDULE

The testing phase for the **Bossku House** system is planned to run over **two weeks**, following the completion of the core system development. The schedule below outlines the testing activities and their target completion dates.

| Phase | Activity | Test Cases | Target Start | Target End | Duration |
|---|---|---|---|---|---|
| **Phase 1** | Environment setup, database seeding, and test preparation | — | Week 1, Day 1 | Week 1, Day 1 | 1 day |
| **Phase 2** | Customer authentication testing (registration, login) | TC-01, TC-02 | Week 1, Day 2 | Week 1, Day 2 | 1 day |
| **Phase 3** | Core ordering flow testing (QR scan, browse, cart, place order, track) | TC-03 to TC-08 | Week 1, Day 3 | Week 1, Day 4 | 2 days |
| **Phase 4** | Customer engagement testing (rewards, reviews, profile) | TC-09 to TC-11 | Week 1, Day 5 | Week 1, Day 5 | 1 day |
| **Phase 5** | Staff operations testing (dashboard, status update, cashier, pay, receipt, history, report) | TC-12 to TC-18 | Week 2, Day 1 | Week 2, Day 2 | 2 days |
| **Phase 6** | Admin management testing (dashboard, categories, products, rewards, reviews, analytics, export) | TC-19 to TC-25 | Week 2, Day 3 | Week 2, Day 4 | 2 days |
| **Phase 7** | Security and edge case testing (invalid login, no table, RBAC) | TC-26 to TC-28 | Week 2, Day 4 | Week 2, Day 4 | 1 day |
| **Phase 8** | User Acceptance Testing (UAT) — peer tester simulates real customer | TC-03 to TC-11 | Week 2, Day 5 | Week 2, Day 5 | 1 day |
| **Phase 9** | Defect fixing and re-testing of failed test cases | All failed TCs | Week 2, Day 5 | As needed | Ongoing |
| **Phase 10** | Test report compilation and documentation | — | After testing | Submission date | 2 days |

**Total Estimated Testing Duration:** 2 weeks (10 working days)

### Test Schedule Summary Table

| Week | Day | Activity |
|---|---|---|
| Week 1 | Day 1 | Environment setup, Firebase config, QR code preparation, test account creation |
| Week 1 | Day 2 | TC-01 (Registration), TC-02 (Login) |
| Week 1 | Day 3 | TC-03 (QR Scan), TC-04 (Browse Menu), TC-05 (Add to Cart) |
| Week 1 | Day 4 | TC-06 (Manage Cart), TC-07 (Place Order), TC-08 (Track Order) |
| Week 1 | Day 5 | TC-09 (Rewards & Redeem), TC-10 (Submit Review), TC-11 (Profile Edit) |
| Week 2 | Day 1 | TC-12 (Staff Dashboard), TC-13 (Update Status), TC-14 (Cashier View) |
| Week 2 | Day 2 | TC-15 (Mark as Paid), TC-16 (Generate Receipt), TC-17 (Order History), TC-18 (Sales Report) |
| Week 2 | Day 3 | TC-19 (Admin Dashboard), TC-20 (Manage Categories), TC-21 (Manage Products) |
| Week 2 | Day 4 | TC-22 (Rewards Catalog), TC-23 (Moderate Reviews), TC-24 (Analytics), TC-25 (Export), TC-26–28 (Security) |
| Week 2 | Day 5 | UAT with peer tester, defect logging, re-testing, test report writing |

---

# 7. DEFINITIONS, ACRONYMS, AND ABBREVIATIONS

## 7.1 Definitions

| Term | Definition |
|---|---|
| **Test Case** | A set of conditions and steps used to determine whether a specific feature or function of the Bossku House system works as expected. Each test case includes input data, execution steps, and expected results. |
| **Test Case Specification** | A formal document that describes in detail how a particular test case should be executed, including its preconditions, input, expected output, procedure steps, and postconditions. |
| **Functional Testing** | A type of software testing that verifies that each feature of the Bossku House system operates in conformance with the specified functional requirements defined in the SRS. |
| **Use Case Testing** | A testing technique derived from use case scenarios. Tests are designed to validate that the system correctly handles the main flow, alternate flow, and robust (exception) flow of each use case. |
| **Equivalence Partitioning** | A black-box test design technique that divides input data into equivalent partitions so that test cases can be selected from each partition (valid and invalid) to represent the whole partition. |
| **Boundary Value Analysis (BVA)** | A test design technique that focuses on testing at the boundaries of input value ranges. For example, testing password fields with exactly 8 characters (minimum), 7 characters (just below), and 9 characters (just above). |
| **Pre-condition** | A condition or state that must be true before a test case can be executed. For example, "the customer must be authenticated" or "at least one product must exist in the database." |
| **Post-condition** | The expected state of the system after a test case has been successfully executed. |
| **Defect / Bug** | An error, flaw, or failure in the Bossku House system that causes it to produce an incorrect or unexpected result, or to behave in unintended ways. |
| **Real-time Update** | An instant data synchronisation between the system and the user's browser, implemented in Bossku House using Firebase Realtime Database. Status changes made by staff appear on the customer tracking page without a page reload. |
| **Role-Based Access Control (RBAC)** | A security mechanism that restricts system access based on the authenticated user's assigned role (Guest Customer, Authenticated Customer, Staff, Admin). |
| **QR Code** | Quick Response Code. A two-dimensional barcode used in Bossku House to encode a unique URL for each restaurant table. Customers scan it with their smartphone camera to be redirected to the menu with their table number pre-filled. |
| **Session** | A temporary storage mechanism in Laravel used to retain user data (such as table number and cart contents) across HTTP requests for the duration of a customer's visit. |
| **Middleware** | A Laravel component that filters HTTP requests entering the application. Used in Bossku House to enforce authentication (`auth`) and role-based access control (`admin`, `staff`). |
| **Throttle** | A Laravel middleware feature that limits the rate of incoming requests. Used in Bossku House to limit order placement (5/min) and review submission (3/min) to prevent spam and abuse. |
| **PDF Receipt** | A Portable Document Format file generated by `barryvdh/laravel-dompdf` containing a completed order's itemised list, totals, table number, and timestamp. |
| **Loyalty Points** | A reward system where authenticated customers earn points based on their order total. Points can be accumulated and redeemed for reward items in the rewards catalogue. |
| **UAT (User Acceptance Testing)** | Testing performed by end users (or representatives) to verify that the system meets their needs and works correctly in real-world usage scenarios. |

## 7.2 Abbreviations

| Abbreviation | Full Form |
|---|---|
| **SRS** | Software Requirements Specification |
| **FYP** | Final Year Project |
| **TC** | Test Case |
| **UC** | Use Case |
| **REQ** | Requirement (as used in SRS functional requirements, e.g., REQ-1) |
| **RBAC** | Role-Based Access Control |
| **QR** | Quick Response (as in QR Code) |
| **DB** | Database |
| **PDF** | Portable Document Format |
| **CSV** | Comma-Separated Values |
| **API** | Application Programming Interface |
| **HTTP** | Hypertext Transfer Protocol |
| **HTTPS** | Hypertext Transfer Protocol Secure |
| **UI** | User Interface |
| **UAT** | User Acceptance Testing |
| **MVC** | Model-View-Controller (Laravel's architectural pattern) |
| **CRUD** | Create, Read, Update, Delete |
| **PHP** | PHP: Hypertext Preprocessor |
| **SQL** | Structured Query Language |
| **BVA** | Boundary Value Analysis |
| **EP** | Equivalence Partitioning |
| **URL** | Uniform Resource Locator |
| **ORM** | Object-Relational Mapping (Laravel Eloquent) |
| **SDK** | Software Development Kit |
| **UPS** | Uninterruptible Power Supply |
| **LAN** | Local Area Network |
| **Wi-Fi** | Wireless Fidelity |
| **iOS** | iPhone Operating System (Apple mobile OS) |
| **RAM** | Random Access Memory |
| **SSD** | Solid State Drive |
| **NPM** | Node Package Manager |
| **RM** | Ringgit Malaysia (currency used in pricing, e.g., RM 8.50) |

---

*End of Document Sections 5, 6, and 7*
*Bossku House QR Digital Ordering System — Test Plan v1.0*
