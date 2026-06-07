# 3. TEST STRATEGY
## Bossku House QR Digital Ordering System
**Version:** 1.0 | **Project:** FYP — Bossku House

---

## 3.1 Test Objective

The primary objective of the testing phase for the **Bossku House QR-Based Digital Food Ordering System** is to verify and validate that the system fulfils all functional requirements as specified in the Software Requirements Specification (SRS) and behaves correctly across all defined use cases, user roles, and operating conditions.

The specific objectives of this test effort are:

| # | Objective |
|---|---|
| 1 | **Verify Functional Correctness** — Confirm that each system feature (QR redirection, menu browsing, cart management, order placement, order tracking, rewards, reviews, staff operations, and admin management) operates according to its defined requirements. |
| 2 | **Validate Role-Based Access Control (RBAC)** — Ensure that the system enforces strict role separation between Guest Customers, Authenticated Customers, Staff, and Admins, preventing unauthorised access to protected routes and functions. |
| 3 | **Validate Real-time Communication** — Confirm that Firebase Realtime Database correctly synchronises order status updates between the staff dashboard and the customer tracking page without requiring a manual page refresh. |
| 4 | **Validate Data Integrity** — Ensure that all database transactions (order creation, points updates, reward redemptions) are accurate, consistent, and correctly stored in the MySQL database. |
| 5 | **Identify and Document Defects** — Detect any defects, failures, or deviations from expected behaviour and record them systematically for resolution. |
| 6 | **Validate Security Mechanisms** — Confirm that authentication middleware, session management, and input validation correctly protect against unauthorised actions, brute-force login attempts, and data manipulation. |
| 7 | **Validate PDF Receipt Generation** — Verify that the system correctly generates a downloadable PDF receipt for each completed and paid order via `barryvdh/laravel-dompdf`. |
| 8 | **Assess Mobile Usability** — Confirm that the customer-facing interface is responsive and usable on mobile devices, particularly for QR code scanning and the menu ordering flow. |

The testing effort will cover a total of **28 test cases** (TC-01 to TC-28) mapped to all system use cases (UC01 to UC25) and tracing directly to functional requirements REQ-1 through REQ-18.

---

## 3.2 Scope

### 3.2.1 In-Scope

The following system components, features, and user roles are **within scope** for this test plan:

**Customer Features (Guest and Authenticated):**
- QR code table scanning and menu redirection (UC01)
- Menu browsing and category filtering (UC02)
- Shopping cart management — add, adjust quantity, remove, clear (UC03, UC04)
- Order placement and unique reference generation (UC05)
- Real-time order status tracking via Firebase (UC06)
- Customer account registration and login (UC08, UC09)
- Loyalty points earning mechanism (UC10)
- Reward catalogue viewing and redemption (UC07, UC11)
- Product review and star rating submission (UC12)
- Customer profile viewing and editing (UC13)

**Staff Features:**
- Real-time orders dashboard powered by Firebase (UC14)
- Order status update (Pending → Preparing → Served) (UC15)
- Cashier view for payment processing (UC16)
- Marking orders as Paid and loyalty points award (UC17)
- PDF receipt generation via `laravel-dompdf` (UC18)
- Order history and sales report access (UC19)

**Admin Features:**
- Admin analytics dashboard (UC20)
- Product category management — CRUD (UC21)
- Product management — CRUD with image upload (UC22)
- Rewards catalogue management (UC23)
- Customer review moderation and deletion (UC25)
- Data analytics viewing and report export (CSV/PDF) (UC24)

**Security and Cross-cutting Concerns:**
- Invalid login attempts and throttle behaviour
- Access control — staff cannot access admin routes and vice versa
- Placing an order without a table number (edge case)

---

## 3.3 Scope

### 3.3.1 Features Tested

The following table lists all features to be tested, mapped to their test cases, requirements, and user roles:

| Feature | Use Case | Test Case(s) | Requirements | Priority |
|---|---|---|---|---|
| Customer Registration | UC08 | TC-01 | REQ-9 | High |
| Customer Login | UC09 | TC-02 | SRS §5.3 Security | High |
| QR Code Table Redirection | UC01 | TC-03 | REQ-1, REQ-2, REQ-3 | High |
| Browse Menu by Category | UC02 | TC-04 | REQ-4 | High |
| Add Item to Cart | UC03 | TC-05 | REQ-5 | High |
| Manage Cart (Adjust / Remove / Clear) | UC04 | TC-06 | REQ-5 | Medium |
| Place Order | UC05 | TC-07 | REQ-6, REQ-7 | High |
| Track Order Status (Real-time) | UC06 | TC-08 | REQ-7, REQ-8 | High |
| View Rewards Catalogue & Redeem | UC07, UC11 | TC-09 | REQ-9, REQ-10 | Medium |
| Submit Product Review | UC12 | TC-10 | REQ-11 | Medium |
| View / Edit Customer Profile | UC13 | TC-11 | SRS §2.3 | Low |
| Staff: Real-time Orders Dashboard | UC14 | TC-12 | REQ-7 | High |
| Staff: Update Order Status | UC15 | TC-13 | REQ-8 | High |
| Staff: Cashier View | UC16 | TC-14 | REQ-12 | High |
| Staff: Mark Order as Paid | UC17 | TC-15 | REQ-12 | High |
| Staff: Generate PDF Receipt | UC18 | TC-16 | REQ-13 | High |
| Staff: View Order History | UC19 | TC-17 | REQ-14 | Medium |
| Staff: View Sales Report | UC19 | TC-18 | REQ-14 | Medium |
| Admin: Analytics Dashboard | UC20 | TC-19 | REQ-18 | Medium |
| Admin: Manage Categories | UC21 | TC-20 | REQ-15 | Medium |
| Admin: Manage Products | UC22 | TC-21 | REQ-15 | Medium |
| Admin: Manage Rewards Catalogue | UC23 | TC-22 | REQ-16 | Low |
| Admin: Moderate Reviews | UC25 | TC-23 | REQ-17 | Low |
| Admin: View Analytics & Export | UC24 | TC-24, TC-25 | REQ-18 | Medium |
| Security: Invalid Login | — | TC-26 | SRS §5.3 | High |
| Security: Order Without Table | — | TC-27 | REQ-3 | High |
| Security: RBAC Enforcement | — | TC-28 | SRS §5.3, §2.5 | High |

**Features NOT Tested (Out of Scope):**
- Online payment gateway integration (pending per SRS Appendix C)
- Dynamic QR code generation in the Admin panel (pending per SRS Appendix C)
- Load/stress testing and performance benchmarking under high user volumes
- Third-party email notification services (not implemented in current scope)

---

## 3.4 Test Process

The testing process for the Bossku House system follows a structured, phase-based approach aligned with the IEEE 829 standard for software test documentation. The overall process consists of the following stages:

### Stage 1: Test Planning
- Define test objectives, scope, and strategy (this document).
- Identify all features to be tested and map them to use cases and requirements.
- Assign test cases to testers based on user roles.
- Prepare the test schedule (see Section 6).

### Stage 2: Test Environment Preparation
- Configure the local development environment (PHP 8.2, Laravel 12, MySQL, Firebase).
- Run `php artisan migrate --seed` to populate the database with test data.
- Generate and print test QR codes for at least 3 table numbers (Table 1, 2, 5).
- Create test accounts for all roles: Admin, Staff, and Customer (with pre-loaded loyalty points).
- Verify network access between the development server and the test smartphone.

### Stage 3: Test Design
- Review all 28 test case specifications (TC-01 to TC-28).
- Verify each test case is traceable to at least one SRS requirement.
- Confirm that alternate flows and exception flows are covered by test cases.
- Apply test design techniques:

| Technique | Applied To |
|---|---|
| **Use Case Testing** | All primary functional flows (TC-01, 03–08, 12–25) |
| **Equivalence Partitioning (EP)** | Login, registration, cart quantity, review rating fields (TC-02, TC-06, TC-10) |
| **Boundary Value Analysis (BVA)** | Password length (min 8 chars), points balance (exact match), review rating (1–5) |
| **Error Guessing** | Invalid QR code, empty cart checkout, wrong password, duplicate email |

### Stage 4: Test Execution

Test cases will be executed in the order defined in the Test Schedule (Section 6), progressing from authentication, to core ordering flow, to staff/admin operations, and finally to security testing.

**Execution steps for each test case:**

| Step | Activity |
|---|---|
| 1 | Verify all pre-conditions are met before beginning the test. |
| 2 | Execute each test procedure step sequentially as documented in the Test Case Specification. |
| 3 | Record the actual output/result observed during execution. |
| 4 | Compare the actual result against the expected result. |
| 5 | Mark the test case as **Pass** or **Fail** based on the comparison. |
| 6 | If the test fails, log a defect with: TC number, steps to reproduce, actual vs. expected result, severity, and a screenshot/recording if applicable. |

### Stage 5: Defect Management

All defects identified during testing will be recorded in a **Defect Log** containing the following fields:

| Field | Description |
|---|---|
| **Defect ID** | Unique identifier (e.g., DEF-001) |
| **TC Reference** | The test case that identified the defect |
| **Description** | Clear description of the failure |
| **Steps to Reproduce** | Numbered steps to replicate the defect |
| **Expected Result** | The correct behaviour as per SRS |
| **Actual Result** | The observed incorrect behaviour |
| **Severity** | Critical / High / Medium / Low |
| **Status** | Open / In-Fix / Resolved / Closed |

After defects are fixed by the developer, failed test cases will be **re-executed** to confirm resolution.

### Stage 6: Test Reporting
- Compile all test results into a **Test Summary Report**.
- Calculate pass/fail rates per feature and overall.
- Document any unresolved defects or known limitations.
- Submit the final test report along with the STP.

---

## 3.5 Item Pass/Fail Criteria

### Overall Pass Criteria
The **Bossku House** system will be considered to have **passed** the testing phase if:

| # | Criterion |
|---|---|
| 1 | All **High-priority** test cases (TC-01, TC-02, TC-03, TC-05, TC-07, TC-08, TC-12, TC-13, TC-14, TC-15, TC-16, TC-26, TC-27, TC-28) achieve a **Pass** status. |
| 2 | At least **90% of all test cases** (≥ 26 out of 28) achieve a **Pass** status. |
| 3 | No **Critical** or **High** severity defects remain **unresolved** at the time of submission. |
| 4 | All Firebase real-time update tests (TC-08, TC-12) successfully demonstrate status propagation within **3 seconds**. |
| 5 | PDF receipt generation (TC-16) produces a valid, downloadable PDF containing the correct order data. |
| 6 | RBAC enforcement tests (TC-26, TC-28) confirm that no unauthorised role can access restricted routes. |

### Individual Test Case Pass Criteria

A single test case is marked **PASS** if:
- The actual output **exactly matches** the expected output defined in the Test Case Specification.
- All post-conditions are verified as true after the test execution.
- No unhandled system errors (e.g., HTTP 500, unformatted exceptions) occur during the test.

A single test case is marked **FAIL** if:
- The actual output **does not match** the expected output.
- The system crashes, throws an unhandled exception, or returns an incorrect HTTP status code.
- Any post-condition is not satisfied after execution.
- The system response time exceeds the limit defined in the SRS (e.g., > 2 seconds for menu load, > 3 seconds for Firebase update).

### Severity Classification for Defects

| Severity | Definition | Example |
|---|---|---|
| **Critical** | System crash, data loss, or security breach. Testing cannot continue. | Database transaction fails, order not saved; admin route accessible without login |
| **High** | Core feature completely non-functional. Workaround is not possible. | QR code does not store table ID; order placement fails; Firebase does not push updates |
| **Medium** | Feature partially functional. Workaround is available. | Cart total does not recalculate after removal; PDF receipt has incorrect formatting |
| **Low** | Minor cosmetic or non-blocking issue. Does not affect core functionality. | Broken product image placeholder; UI misalignment on a specific screen size |

---

## 3.6 Suspension Criteria and Resumption Requirements

### 3.6.1 Suspension Criteria

Testing activities for the **Bossku House** system will be **suspended** if any of the following conditions are met:

| # | Suspension Condition | Rationale |
|---|---|---|
| 1 | **Critical Environment Failure** — The local Laravel development server (`php artisan serve`) fails to start or crashes unexpectedly during testing. | Testing cannot proceed without a functional application environment. |
| 2 | **Database Unavailability** — The MySQL database becomes inaccessible, corrupted, or returns persistent connection errors. | All functional test cases depend on database read/write operations. |
| 3 | **Critical Defect Blocking Progress** — A Critical-severity defect is discovered in a core module (e.g., order placement, authentication) that prevents execution of dependent test cases. | For example, if TC-07 (Place Order) fails critically, TC-08 (Track Order), TC-12 (Staff Dashboard), and TC-15 (Mark as Paid) cannot be executed meaningfully. |
| 4 | **Firebase Service Outage** — The Firebase Realtime Database is unreachable or returns persistent errors, preventing real-time update tests from being executed. | TC-08 and TC-12 require Firebase to be fully operational. |
| 5 | **More Than 30% Test Case Failure** — If more than 8 out of 28 test cases fail consecutively, indicating a systemic implementation issue. | Mass failures suggest a fundamental code problem requiring developer investigation before testing can resume. |
| 6 | **Tester Unavailability** — The primary tester is unable to continue due to unforeseen personal circumstances. | Testing requires human execution and cannot be automated at this stage. |

### 3.6.2 Resumption Requirements

Testing activities will **resume** only after the following conditions are satisfied:

| # | Suspension Cause | Resumption Requirement |
|---|---|---|
| 1 | Critical Environment Failure | The Laravel server must be restored and verified to be running correctly. A fresh `php artisan serve` session must be confirmed stable for at least 10 minutes before resuming. |
| 2 | Database Unavailability | The MySQL database must be restored (or re-seeded via `php artisan migrate:fresh --seed`). All test data (products, users, test accounts) must be verified to exist before resumption. |
| 3 | Critical Defect Blocking Progress | The developer must resolve the critical defect and confirm the fix. The affected test case must be re-executed and pass before dependent test cases resume. |
| 4 | Firebase Service Outage | Firebase Realtime Database must be confirmed operational. The developer must verify the Firebase SDK connection by checking the Kreait Firebase configuration and the Firebase Console status page. |
| 5 | Mass Test Case Failure | The developer must investigate and resolve the root cause of the systemic failures. A root cause analysis must be documented. At least the first 5 previously-failing test cases must pass before full testing resumes. |
| 6 | Tester Unavailability | An alternative tester must be briefed using the Test Case Specification document. All pre-conditions and test environment states must be verified as intact before the alternative tester begins. |

> **Note:** All suspension events and their resolution must be documented in the test log, including the date and time of suspension, the cause, the resolution action taken, and the date and time of resumption.

---

## 3.7 Entry and Exit Criteria

### 3.7.1 Entrance Criteria

The following conditions **must be fully satisfied** before any test case execution begins:

| # | Entrance Criterion | Verified By |
|---|---|---|
| 1 | **Application is deployed and running** — The Laravel application is accessible via `http://127.0.0.1:8000` (or local network IP for mobile testing) using `php artisan serve`. | Developer / Primary Tester |
| 2 | **Database is seeded** — `php artisan migrate:fresh --seed` has been executed successfully. Test categories, products, rewards, and user accounts (Admin, Staff, Customer) exist in the database. | Developer |
| 3 | **Firebase is configured and operational** — The Kreait Laravel Firebase SDK is connected to the active Firebase project. The Realtime Database is accessible, and the service account JSON key is correctly placed in the project directory. | Developer |
| 4 | **Test accounts are created and verified** — The following accounts exist and are accessible: Admin (`admin@bossku.com`), Staff (`staff@bossku.com`), and Customer (`customer@bossku.com` with ≥ 100 pre-loaded loyalty points). | Developer |
| 5 | **QR codes are prepared** — Test QR codes for at least 3 table numbers (Table 1, Table 2, Table 5) have been generated and are accessible for scanning (printed or displayed on screen). | Developer |
| 6 | **Test devices are ready** — The laptop/desktop and test smartphone are powered, connected to the same Wi-Fi network, and can access the development server via the local network IP. | Primary Tester |
| 7 | **Test Case Specifications are reviewed** — All testers have read and understood the test case specifications for the test cases assigned to them. | All Testers |
| 8 | **Test documentation is prepared** — Test result recording sheets (or digital equivalent) are ready for each tester to log actual results, pass/fail status, and defect notes. | Primary Tester |
| 9 | **No Critical build errors** — The application compiles without errors (`npm run build` completes successfully; no PHP fatal errors appear in the Laravel log). | Developer |

### 3.7.2 Exit Criteria

Testing is considered **complete** and the test phase may be formally closed when all of the following conditions are met:

| # | Exit Criterion |
|---|---|
| 1 | **All 28 test cases have been executed** — Every test case from TC-01 to TC-28 has been run at least once and a result (Pass or Fail) has been recorded. |
| 2 | **Pass Rate Achieved** — At least 90% of all test cases (≥ 26 of 28) have a status of **Pass**. |
| 3 | **All High-priority test cases pass** — All test cases with a Risk Number of 4 (High) have achieved a Pass status. |
| 4 | **No unresolved Critical or High severity defects** — All Critical and High-severity defects logged during testing have been resolved by the developer and verified by re-testing. |
| 5 | **Re-testing of fixed defects is complete** — Any test case that initially failed and was re-executed after a bug fix now has a confirmed Pass status. |
| 6 | **User Acceptance Testing (UAT) is complete** — The peer/UAT tester has executed the customer-facing test cases (TC-03 to TC-11) and provided sign-off. |
| 7 | **Test Summary Report is completed** — A Test Summary Report has been compiled documenting all test results, defect logs, pass/fail rates, and any remaining known issues. |
| 8 | **Test evidence is archived** — All test result records, defect logs, screenshots, and screen recordings have been saved and are available for inclusion in the FYP submission. |

---

## 3.8 Risks and Contingencies

The following risks have been identified for the testing phase of the **Bossku House** system, along with their likelihood, impact, and mitigation strategies:

| # | Risk | Likelihood | Impact | Mitigation / Contingency |
|---|---|---|---|---|
| 1 | **Firebase Service Outage** — Firebase Realtime Database is temporarily unavailable, preventing real-time update tests (TC-08, TC-12) from being executed. | Low | High | Monitor Firebase Console status page. Re-schedule Firebase-dependent tests to a different time. As a contingency, verify that the system falls back to DB polling as defined in RF14-C. |
| 2 | **Environment Configuration Error** — Incorrect `.env` configuration (database credentials, Firebase JSON key path) causes the application to fail on startup. | Medium | Critical | Maintain a backup `.env.testing` file with verified credentials. Follow the environment setup checklist in Section 5.5 step-by-step. |
| 3 | **QR Code Scanning Failure** — The test smartphone fails to read the printed QR code due to lighting conditions, camera quality, or print resolution. | Medium | Medium | Use a digital QR code displayed on a secondary screen instead of a printed copy. Use an online QR reader app as a fallback for manual URL entry. |
| 4 | **Database Corruption During Testing** — Executing test cases that write/delete data causes the database to reach an inconsistent state, blocking subsequent tests. | Low | High | After each major test phase, run `php artisan migrate:fresh --seed` to reset the database to a clean state. Maintain separate test data sets for each phase. |
| 5 | **Session/Cookie Conflicts** — Running multiple role-based tests in the same browser causes session data from one role (e.g., Admin) to interfere with another role's test (e.g., Customer). | High | Medium | Use separate browser profiles or use Incognito/Private mode for each role. Use different browsers for different roles (e.g., Chrome for Admin, Firefox for Customer). |
| 6 | **Mobile Device Incompatibility** — The customer-facing UI does not render correctly on the specific test smartphone's browser, affecting TC-03 (QR Scan) and mobile responsiveness tests. | Low | Medium | Test on both Android (Chrome) and iOS (Safari) where possible. Use browser DevTools responsive mode as a fallback for mobile UI verification. |
| 7 | **Time Constraint** — The 2-week testing window is insufficient to complete all 28 test cases due to defect fixing and re-testing cycles. | Medium | Medium | Prioritise High-risk test cases (Risk Number 4) first. If time is critically short, Medium and Low priority tests may be deferred to a post-submission phase with documentation of the deferral. |
| 8 | **PDF Generation Library Failure** — `barryvdh/laravel-dompdf` fails to render the PDF receipt (TC-16) due to missing PHP extensions or incorrect configuration. | Low | Medium | Verify PHP `gd`, `mbstring`, and `dom` extensions are enabled in `php.ini`. Check `dompdf` configuration in `config/dompdf.php`. Test with a simple HTML template first. |
| 9 | **Peer Tester Unavailability** — The UAT peer tester is unavailable on the scheduled UAT day (Week 2, Day 5). | Medium | Low | Schedule UAT with at least 1 week advance notice. Identify a backup UAT tester. If no peer is available, the developer may conduct UAT independently, with results clearly marked as self-tested. |
| 10 | **Rate Limiting Interference** — Laravel's throttle middleware (e.g., 5 orders/min, 3 reviews/min) triggers during sequential testing, causing test cases to fail with HTTP 429 responses. | Medium | Low | Add a 60-second wait between repeated submissions of the same endpoint during testing. Consider temporarily increasing throttle limits in the `.env` for the test environment, documenting the change. |

---

## 3.9 Test Level

### 3.9.1 Black Box Testing

**Black Box Testing** is the primary testing technique applied to the **Bossku House** system. In this approach, the tester interacts with the system exclusively through its user interface and defined endpoints, without knowledge of or access to the internal source code or database logic during test execution.

**Rationale for Selection:**
The Bossku House system is a web application with clearly defined inputs (form fields, button clicks, URL parameters) and observable outputs (page content, database records, HTTP responses, Firebase updates). Black box testing is appropriate because:
- It tests the system from the end-user's perspective, aligning with the user roles (Customer, Staff, Admin).
- It validates that all functional requirements defined in the SRS are correctly implemented and observable through the UI.
- It is suitable for use case-based testing, which is the primary test design technique used in this project.

**Black Box Techniques Applied:**

| Technique | Description | Applied Test Cases |
|---|---|---|
| **Use Case Testing** | Test cases are derived directly from the use case scenarios (Main Flow, Alternate Flow, Robustness Flow) defined in the Test Cases document. | TC-01, TC-03 to TC-25 |
| **Equivalence Partitioning (EP)** | Input fields are divided into valid and invalid partitions. One representative value is tested from each partition. | TC-02 (Login — valid/invalid credentials), TC-06 (Cart — valid/zero/negative quantity), TC-10 (Review — valid rating 1–5 / invalid 0 or 6) |
| **Boundary Value Analysis (BVA)** | Values at the boundaries of valid input ranges are tested specifically. | Password field: 7 chars (invalid), 8 chars (valid minimum), 9 chars (valid). Loyalty points: exact amount (valid), amount - 1 (invalid). Star rating: 1 (min), 5 (max). |
| **Error Guessing** | The tester uses experience to identify likely error-prone scenarios and test them explicitly. | TC-26 (Wrong password/email), TC-27 (No table number on checkout), TC-28 (Staff accessing admin route), RF cases for Firebase failure, QR damage |

**Scope of Black Box Testing in Bossku House:**
All 28 test cases (TC-01 to TC-28) are executed as black box tests. The tester interacts with the system via:
- Browser UI (Google Chrome for desktop; mobile browser for customer tests)
- Physical QR code scanning via smartphone camera
- Observation of on-screen messages, redirects, page content, and Firebase-driven updates

---

### 3.9.2 Risk Based Testing

**Risk-Based Testing** is applied as a complementary strategy to prioritise test execution based on the likelihood and impact of failure for each feature. This ensures that the most critical and high-risk features of the Bossku House system receive the greatest testing attention, particularly given the time-constrained FYP testing window.

**Risk Assessment Criteria:**

| Risk Number | Risk Level | Meaning |
|---|---|---|
| 4 | **High** | Feature failure would critically impact the system's core purpose or expose a security vulnerability. Must be tested first and must pass. |
| 3 | **Medium** | Feature failure would significantly degrade the user experience but would not completely halt core operations. |
| 2 | **Low** | Feature failure would have a minor impact. Testing is important but can be deferred if time is critical. |
| 1 | **Minimal** | Cosmetic or very low-impact issues. Testing is optional in the FYP context. |

**Risk Register — Feature-Level Assessment:**

| Feature | Risk No. | Justification |
|---|---|---|
| Customer Registration (TC-01) | 4 — High | Required for loyalty points and rewards. Failure blocks all authenticated customer features. |
| Customer Login (TC-02) | 4 — High | Core authentication. Failure blocks all role-protected routes. |
| QR Code Redirection (TC-03) | 4 — High | The system's defining feature. Failure means table ID is not set, making order placement impossible. |
| Add to Cart (TC-05) | 4 — High | Prerequisite for placing an order. Failure blocks the entire ordering flow. |
| Place Order (TC-07) | 4 — High | The most critical customer action. Failure means no orders can be received by staff. |
| Real-time Order Tracking (TC-08) | 4 — High | Core value proposition. Failure means customers cannot track their food. |
| Staff Real-time Dashboard (TC-12) | 4 — High | Staff cannot see orders if this fails, causing full operational failure. |
| Update Order Status (TC-13) | 4 — High | Failure means orders cannot progress from Pending, blocking payment and points. |
| Cashier View / Mark as Paid (TC-14, TC-15) | 4 — High | Revenue-critical. Failure means the restaurant cannot process payments. |
| PDF Receipt Generation (TC-16) | 4 — High | Required for customer receipts and financial records. |
| RBAC / Security Tests (TC-26, TC-27, TC-28) | 4 — High | Security violation risks are the highest-priority failures in any system. |
| Browse Menu (TC-04) | 3 — Medium | Important but degraded gracefully; customers can still see all items. |
| Manage Cart (TC-06) | 3 — Medium | Inconvenient if broken, but customer can still place orders with default quantities. |
| Rewards Catalogue & Redemption (TC-09) | 2 — Low | Engagement feature. Failure does not affect core ordering operations. |
| Submit Product Review (TC-10) | 2 — Low | Feedback feature. Does not affect core system functionality. |
| Admin CRUD Operations (TC-20 to TC-23) | 3 — Medium | Required for menu management; failure prevents menu updates but existing data still functions. |

**Risk-Based Test Execution Order:**

Test cases are scheduled in order of risk priority (highest risk first) to ensure that critical features are validated before the test window expires:

1. **Phase 1 (Critical — Risk 4):** TC-01, TC-02, TC-03, TC-05, TC-07, TC-08, TC-12, TC-13, TC-14, TC-15, TC-16, TC-26, TC-27, TC-28
2. **Phase 2 (Important — Risk 3):** TC-04, TC-06, TC-11, TC-17, TC-18, TC-19, TC-20, TC-21, TC-24, TC-25
3. **Phase 3 (Standard — Risk 2):** TC-09, TC-10, TC-22, TC-23

This priority order is reflected in the Test Schedule (Section 6), where High-priority phases are scheduled in Week 1 and the first half of Week 2.

---

*End of Section 3 — Test Strategy*
*Bossku House QR Digital Ordering System — Software Test Plan v1.0*
