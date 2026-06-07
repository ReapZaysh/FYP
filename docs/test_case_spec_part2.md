
---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-11 |
| **Test Case Name** | View and Edit Customer Profile |
| **Requirement Traceability** | Section 2.3: User Classes (Authenticated Customers); Section 5.3: Security Requirements |
| **Test Case Description** | This test case verifies that an authenticated customer can view their profile information (name, email, loyalty points) and successfully update their name and email address. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Customer profile page (`/my-profile`) |
| 2 | Profile update module (`PATCH /profile`) |
| 3 | Auth middleware protecting the route |

**Pre-condition:** Customer is authenticated. Customer navigates to `/my-profile`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated customer submits updated name and/or email address. | • Profile information is updated in the `users` table. • Success message displayed: "Profile updated successfully." • Updated information is immediately reflected on the profile page. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as an authenticated customer. |
| 2 | Navigate to the `/my-profile` page. |
| 3 | Verify the current name, email, and points balance are displayed correctly. |
| 4 | Update the name field with a new name. |
| 5 | Update the email field with a new valid email address. |
| 6 | Click the "Save" button. |
| 7 | System sends a PATCH request to `/profile` and saves the changes. |

**Post-condition**
- Customer's name and email are updated in the database.
- Updated information is displayed on the profile page.
- Customer remains logged in after the update.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-12 |
| **Test Case Name** | Staff View Real-time Orders Dashboard |
| **Requirement Traceability** | Section 3.3: Staff Order Processing; REQ-7 |
| **Test Case Description** | This test case verifies that authenticated staff can access the real-time orders dashboard, view all pending and active orders, and receive new orders automatically via Firebase without a page refresh. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Staff orders dashboard (`/staff/orders`) |
| 2 | Staff role middleware (`staff` middleware) |
| 3 | Firebase real-time order notification |

**Pre-condition:** Staff member is authenticated with `role = staff`. At least one active order exists in the database.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated staff navigates to `/staff/orders`. | • Dashboard displays all active orders (Pending/Preparing) with reference number, table, items, and status. • When a new customer order is placed, the order card appears on the dashboard automatically via Firebase. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as a user with `role = staff`. |
| 2 | Navigate to `/staff/orders`. |
| 3 | Verify the middleware grants access to the dashboard. |
| 4 | Verify all active orders are displayed with correct details. |
| 5 | From a separate session, have a customer place a new order. |
| 6 | Observe the staff dashboard without refreshing the page. |
| 7 | Verify the new order appears automatically on the dashboard. |

**Post-condition**
- Staff dashboard displays all active orders correctly.
- New orders appear in real-time without requiring a manual page refresh.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-13 |
| **Test Case Name** | Staff Update Order Status |
| **Requirement Traceability** | Section 3.3: Staff Order Processing; REQ-8 |
| **Test Case Description** | This test case verifies that staff can successfully update the status of an order (e.g., from Pending to Preparing, then to Served), and that the change is pushed to Firebase so the customer's tracking page updates instantly. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Order status update function (`PATCH /staff/orders/{reference}`) |
| 2 | Firebase status synchronisation |
| 3 | Customer order tracking page |

**Pre-condition:** Staff is authenticated. At least one active order is visible on the staff dashboard.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Staff clicks "Update Status" and selects "Preparing" for a Pending order. | • Order status updated to "Preparing" in the database. • Firebase pushes the update to the customer's tracking page. • Customer tracking page status badge changes to "Preparing" automatically. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as staff and navigate to `/staff/orders`. |
| 2 | Locate an order with status "Pending". |
| 3 | Click the "Update Status" button and select "Preparing". |
| 4 | System sends a PATCH request to `/staff/orders/{reference}`. |
| 5 | System updates the order status in the database. |
| 6 | System pushes the update to Firebase. |
| 7 | Verify the customer's tracking page updates to "Preparing" without a page reload. |

**Post-condition**
- Order status is "Preparing" in the database.
- Customer tracking page reflects the updated status in real-time.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-14 |
| **Test Case Name** | Staff Access Cashier View |
| **Requirement Traceability** | Section 3.5: Staff Operations and Payments; REQ-12 |
| **Test Case Description** | This test case verifies that authenticated staff can access the cashier view, which displays all orders that have been served and are awaiting payment processing. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Cashier view page (`/staff/cashier`) |
| 2 | Order query filtered by status "Served" |
| 3 | Staff role middleware |

**Pre-condition:** Staff is authenticated with `role = staff`. At least one order has been updated to status "Served".

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated staff navigates to `/staff/cashier`. | • Cashier view is displayed with all "Served" orders. • Each order shows: table number, order items, quantities, and total amount payable. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Ensure at least one order has status "Served" in the system. |
| 2 | Log in as staff and navigate to `/staff/cashier`. |
| 3 | Verify the middleware validates the staff role and grants access. |
| 4 | Verify all "Served" orders are listed with complete details. |
| 5 | Verify each order card displays table number, items, and total amount. |
| 6 | — |
| 7 | — |

**Post-condition**
- Cashier view displays all served orders pending payment.
- Staff can proceed to mark orders as paid from this view.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-15 |
| **Test Case Name** | Staff Mark Order as Paid |
| **Requirement Traceability** | Section 3.5: Staff Operations and Payments; REQ-12 |
| **Test Case Description** | This test case verifies that staff can mark a served order as "Paid", triggering loyalty point allocation for authenticated customers and making the PDF receipt available for generation. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Mark as Paid function (`PATCH /staff/orders/{reference}/pay`) |
| 2 | Loyalty points allocation logic |
| 3 | Receipt generation availability |

**Pre-condition:** Order has status "Served". Staff is on the cashier view (`/staff/cashier`).

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Staff clicks "Mark as Paid" on a served order. | • Order status updated to "Paid" in the database. • If the order is linked to an authenticated customer, their loyalty points balance is incremented. • "Generate Receipt" button/link becomes available for the order. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to `/staff/cashier` as authenticated staff. |
| 2 | Locate a served order linked to a registered customer. |
| 3 | Click the "Mark as Paid" button. |
| 4 | System sends a PATCH request to `/staff/orders/{reference}/pay`. |
| 5 | System updates the order status to "Paid" in the database. |
| 6 | System calculates and awards loyalty points to the customer. |
| 7 | Verify the "Generate Receipt" option appears for the order. |

**Post-condition**
- Order status is "Paid" in the database.
- Authenticated customer's loyalty points balance is increased.
- The receipt can now be generated for this order.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-16 |
| **Test Case Name** | Generate PDF Receipt |
| **Requirement Traceability** | Section 3.5: Staff Operations and Payments; REQ-13 |
| **Test Case Description** | This test case verifies that staff can generate and download a PDF receipt for a paid order using `barryvdh/laravel-dompdf`. The receipt must contain an itemised list, totals, table number, and timestamp. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | PDF receipt generation route (`GET /staff/orders/{reference}/receipt`) |
| 2 | `barryvdh/laravel-dompdf` integration |
| 3 | Receipt HTML template |

**Pre-condition:** Order status is "Paid". Staff has access to the paid order.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Staff clicks "Generate Receipt" for a paid order. | • A PDF file is generated and streamed as a browser download. • The PDF contains: order reference, table number, itemised list with quantities and prices, total amount, and generation timestamp. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the paid orders list or cashier view. |
| 2 | Locate a paid order. |
| 3 | Click the "Generate Receipt" button. |
| 4 | System sends a GET request to `/staff/orders/{reference}/receipt`. |
| 5 | System fetches order and item data from the database. |
| 6 | `laravel-dompdf` renders the receipt HTML template to PDF. |
| 7 | Browser downloads the PDF file. |

**Post-condition**
- A PDF receipt is downloaded to the staff's device.
- The receipt contains all correct order details, items, totals, and timestamp.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-17 |
| **Test Case Name** | Staff View Order History |
| **Requirement Traceability** | Section 3.5: Staff Operations and Payments; REQ-14 |
| **Test Case Description** | This test case verifies that authenticated staff can access the order history page and view a paginated list of all past orders (Paid, Served, Cancelled) with relevant details. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Order history page (`/staff/history`) |
| 2 | Order history query (non-active orders) |
| 3 | Pagination component |

**Pre-condition:** Staff is authenticated. Historical order records exist in the database.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated staff navigates to `/staff/history`. | • Order history table is displayed with all non-active orders (Paid, Cancelled, Served). • Each row shows: reference number, table number, status, total amount, and order date. • Records are paginated (25 per page). |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as authenticated staff. |
| 2 | Navigate to `/staff/history`. |
| 3 | Verify middleware grants access. |
| 4 | Verify the history table displays completed orders. |
| 5 | Verify each row contains reference, table, status, total, and date. |
| 6 | Verify pagination controls appear when records exceed 25. |
| 7 | — |

**Post-condition**
- Order history is displayed correctly with all relevant details.
- Pagination allows staff to navigate through historical records.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-18 |
| **Test Case Name** | Generate Sales Report |
| **Requirement Traceability** | Section 3.5: Staff Operations and Payments; REQ-14 |
| **Test Case Description** | This test case verifies that staff can generate a sales report for a selected date range, showing total revenue, order count, and top-selling products, with an option to export as PDF. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Sales report page (`/staff/report`) |
| 2 | Database aggregation query (revenue, order count, top products) |
| 3 | PDF export of report |

**Pre-condition:** Staff is authenticated. Paid orders exist in the system for the selected date range.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Staff selects a start and end date and clicks "Generate Report". | • Report page displays: total revenue, total paid orders, and top-selling products for the period. • Option to export report as a downloadable PDF is available. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as authenticated staff and navigate to `/staff/report`. |
| 2 | Select a start date (e.g., start of the current month). |
| 3 | Select an end date (e.g., today). |
| 4 | Click the "Generate Report" button. |
| 5 | System aggregates order data from the database for the selected period. |
| 6 | Verify the report shows total revenue, order count, and top products. |
| 7 | Click "Export PDF" and verify a PDF file is downloaded. |

**Post-condition**
- Sales report is displayed with accurate data for the selected date range.
- PDF export generates a downloadable file containing the report.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-19 |
| **Test Case Name** | Admin View Dashboard and Navigate to Features |
| **Requirement Traceability** | Section 3.6: Administrative Management; Section 5.3: Security (RBAC) |
| **Test Case Description** | This test case verifies that an authenticated admin can access the admin dashboard, view key metrics (total orders, revenue, products, users), and navigate to all management sections via the sidebar. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Admin dashboard (`/admin/dashboard`) |
| 2 | Admin role middleware (`admin` middleware) |
| 3 | Metrics query (orders, revenue, products, users) |

**Pre-condition:** User is authenticated with `role = admin`. User navigates to `/admin/dashboard`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated admin navigates to `/admin/dashboard`. | • Dashboard displays summary metric cards: total orders, total revenue, product count, and registered user count. • Sidebar navigation links to: Categories, Products, Rewards, Reviews, Analytics. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as a user with `role = admin`. |
| 2 | Navigate to `/admin/dashboard` (or via the `/dashboard` redirect). |
| 3 | Verify the `admin` middleware grants access. |
| 4 | Verify summary cards display: total orders, revenue, products, and users. |
| 5 | Verify the sidebar contains navigation links to all management sections. |
| 6 | Click each sidebar link and verify it navigates to the correct page. |
| 7 | — |

**Post-condition**
- Admin dashboard is accessible and displays accurate business metrics.
- All sidebar navigation links function correctly and lead to the appropriate management pages.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-20 |
| **Test Case Name** | Admin Manage Product Categories (CRUD) |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-15 |
| **Test Case Description** | This test case verifies that the admin can create a new product category, edit an existing category name, and delete a category. All changes must be immediately reflected on the customer-facing menu page. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Categories management page (`/admin/categories`) |
| 2 | Create category (`POST /admin/categories`) |
| 3 | Edit category (`PATCH /admin/categories/{id}`) |
| 4 | Delete category (`DELETE /admin/categories/{id}`) |

**Pre-condition:** Admin is authenticated. Admin navigates to `/admin/categories`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Admin creates a new category with a unique name (e.g., "Desserts"). | • New "Desserts" category is saved in the database. • Category appears as a filter tab on the customer menu. |
| Admin edits an existing category name. | • Category name is updated in the database and reflected on the menu. |
| Admin deletes a category with no assigned products. | • Category is removed from the database and no longer appears on the menu. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/categories`. |
| 2 | Click "New Category" and enter "Desserts" as the name. |
| 3 | Submit the form and verify "Desserts" appears in the categories list. |
| 4 | Click "Edit" on "Desserts", change the name to "Sweet Treats", and save. |
| 5 | Verify the name updates to "Sweet Treats" in the list. |
| 6 | Click "Delete" on "Sweet Treats" and confirm the deletion. |
| 7 | Verify "Sweet Treats" is removed from the categories list. |

**Post-condition**
- Category changes are persisted to the database.
- The customer-facing menu immediately reflects the created/updated/deleted categories.
