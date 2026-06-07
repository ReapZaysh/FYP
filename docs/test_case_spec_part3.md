
---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-21 |
| **Test Case Name** | Admin Manage Products (CRUD) |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-15 |
| **Test Case Description** | This test case verifies that the admin can create a new menu product with all required fields (name, category, price, image, description), edit an existing product, and delete a product. Changes must immediately appear on the customer menu. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Products management page (`/admin/products`) |
| 2 | Create product (`POST /admin/products`) |
| 3 | Edit product (`PATCH /admin/products/{id}`) |
| 4 | Delete product (`DELETE /admin/products/{id}`) |

**Pre-condition:** Admin is authenticated. At least one category exists. Admin navigates to `/admin/products`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Admin creates a new product: name "Ayam Goreng", category "Main Course", price RM 8.50, with a valid image and description. | • Product saved in the database. • Product appears on the customer menu under "Main Course" with name, price, image, and description. |
| Admin edits the product price from RM 8.50 to RM 9.00. | • Updated price is saved and reflected on the menu. |
| Admin deletes the product. | • Product removed from database and no longer visible on the menu. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/products`. |
| 2 | Click "New Product" and fill in: name, category, price, description, and upload an image. |
| 3 | Submit the form and verify the product appears in the product list. |
| 4 | Navigate to the customer menu and verify the product is visible with correct details. |
| 5 | Return to admin panel, click "Edit" on the product, and change the price. |
| 6 | Save the edit and verify the updated price shows on the menu. |
| 7 | Click "Delete" on the product and verify it is removed from both the admin list and the customer menu. |

**Post-condition**
- Product changes are saved to the database.
- The customer-facing menu accurately reflects the created, edited, or deleted products.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-22 |
| **Test Case Name** | Admin Manage Rewards Catalog |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-16 |
| **Test Case Description** | This test case verifies that the admin can create a new reward item with a name and point cost, update an existing reward's point cost, and delete an expired reward. All changes must be reflected on the customer rewards catalogue. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Rewards management page (`/admin/rewards`) |
| 2 | Create reward (`POST /admin/rewards`) |
| 3 | Update reward point cost (`PATCH /admin/rewards/{id}`) |
| 4 | Delete reward (`DELETE /admin/rewards/{id}`) |

**Pre-condition:** Admin is authenticated. Admin navigates to `/admin/rewards`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Admin creates a new reward: "Free Drink" with a cost of 100 points. | • Reward saved in the database. • "Free Drink" appears on the customer `/rewards` page with a cost of 100 points. |
| Admin updates the point cost of "Free Drink" to 120 points. | • Updated cost is saved and reflected on the rewards catalogue. |
| Admin deletes "Free Drink" reward. | • Reward removed and no longer visible on the customer rewards page. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/rewards`. |
| 2 | Click "New Reward" and fill in name "Free Drink" and point cost "100". |
| 3 | Submit and verify "Free Drink" appears in the rewards list. |
| 4 | Navigate to `/rewards` as a customer and verify "Free Drink" is visible with 100 points cost. |
| 5 | Return to admin panel and click "Edit" on "Free Drink". |
| 6 | Change the point cost to 120 and save. Verify the update is reflected on the catalogue. |
| 7 | Click "Delete" and verify "Free Drink" is removed from the catalogue. |

**Post-condition**
- Reward changes are persisted to the database.
- The customer rewards catalogue page accurately reflects all current rewards and their point costs.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-23 |
| **Test Case Name** | Admin Moderate Customer Reviews (Delete) |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-17 |
| **Test Case Description** | This test case verifies that the admin can view all customer reviews across all products and permanently delete an inappropriate or spam review from the moderation panel. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Reviews moderation page (`/admin/reviews`) |
| 2 | Delete review function (`DELETE /admin/reviews/{product}/{code}`) |
| 3 | Product reviews display on the menu |

**Pre-condition:** Admin is authenticated. At least one customer review exists in the system. Admin navigates to `/admin/reviews`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Admin clicks "Delete" on an inappropriate review. | • Review is permanently removed from the database. • The review no longer appears on the customer-facing product listing. • The moderation table refreshes and the deleted review is no longer shown. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/reviews`. |
| 2 | Verify all existing reviews are listed (product name, customer, rating, text, date). |
| 3 | Identify an inappropriate or spam review. |
| 4 | Click the "Delete" button next to the selected review. |
| 5 | System sends a DELETE request to `/admin/reviews/{product}/{code}`. |
| 6 | System removes the review record from the database. |
| 7 | Verify the deleted review no longer appears on the moderation table or the product menu page. |

**Post-condition**
- The review is permanently deleted from the database.
- The review is no longer visible on the customer-facing product listing.
- The product's average rating is recalculated without the deleted review.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-24 |
| **Test Case Name** | Admin View Analytics Dashboard |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-18 |
| **Test Case Description** | This test case verifies that the admin can view the analytics dashboard displaying aggregated business data: total revenue, number of orders by status, top-selling products, and sales trends. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Analytics dashboard (`/admin/analytics`) |
| 2 | Database aggregation queries (revenue, order counts, top products) |
| 3 | Date range filter |

**Pre-condition:** Admin is authenticated. Paid orders exist in the system. Admin navigates to `/admin/analytics`.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated admin navigates to `/admin/analytics`. | • Dashboard displays: total revenue, total orders, order breakdown by status, and top 5 selling products. • Date range filter allows admin to view data for a specific period. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/analytics`. |
| 2 | Verify the middleware grants access and the dashboard loads. |
| 3 | Verify the total revenue figure matches the sum of all paid orders. |
| 4 | Verify the order count breakdown by status (Pending, Preparing, Served, Paid, Cancelled). |
| 5 | Verify the top-selling products list shows the most frequently ordered items. |
| 6 | Apply a date filter (e.g., current month) and verify the analytics update accordingly. |
| 7 | — |

**Post-condition**
- Analytics dashboard displays accurate, aggregated business data.
- Date range filter correctly narrows the analytics to the selected period.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-25 |
| **Test Case Name** | Admin Export Reports (CSV and PDF) |
| **Requirement Traceability** | Section 3.6: Administrative Management; REQ-18 |
| **Test Case Description** | This test case verifies that the admin can export the sales and analytics data in both CSV and PDF formats from the analytics page. The exported files must contain accurate, complete data. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Analytics export route (`GET /admin/analytics/export`) |
| 2 | CSV generation module |
| 3 | PDF generation via `barryvdh/laravel-dompdf` |

**Pre-condition:** Admin is authenticated. Paid orders and analytics data exist in the system. Admin is on the `/admin/analytics` page.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Admin clicks "Export CSV". | • A `.csv` file is downloaded containing all sales data (reference, date, total, status) with correct column headers. |
| Admin clicks "Export PDF". | • A formatted `.pdf` file is downloaded containing the analytics report with totals, top products, and date range. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as admin and navigate to `/admin/analytics`. |
| 2 | Click the "Export CSV" button. |
| 3 | Verify the browser downloads a `.csv` file. |
| 4 | Open the CSV and verify it contains correct headers and order data. |
| 5 | Return to the analytics page and click "Export PDF". |
| 6 | Verify the browser downloads a `.pdf` file. |
| 7 | Open the PDF and verify it contains the analytics report with totals and top products. |

**Post-condition**
- CSV file is downloaded and contains accurate, complete sales data.
- PDF file is downloaded and contains a well-formatted analytics report.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-26 |
| **Test Case Name** | Invalid Login — Wrong Password |
| **Requirement Traceability** | Section 5.3: Security Requirements (Authentication) |
| **Test Case Description** | This test case verifies that the system correctly rejects a login attempt with a wrong password and displays an appropriate error message without revealing which field is incorrect. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Customer Login page (`/c/login`) |
| 2 | Laravel authentication guard |
| 3 | Brute-force throttle middleware |

**Pre-condition:** A registered customer account exists. Customer is on the `/c/login` page.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Valid registered email with an incorrect password. | • Login is rejected. • Error message displayed: "These credentials do not match our records." • User remains on the login page. • No session is created. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/c/login` page. |
| 2 | Enter a valid registered email address. |
| 3 | Enter an incorrect password. |
| 4 | Click the "Login" button. |
| 5 | System checks credentials and finds no match. |
| 6 | Verify the error message "These credentials do not match our records." is displayed. |
| 7 | Verify the user remains on the login page and no session is created. |

**Post-condition**
- Login is rejected. No authentication session is created.
- Error message is displayed without revealing whether the email or password is wrong (preventing user enumeration).

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Boundary Value Analysis |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-27 |
| **Test Case Name** | Place Order Without Table Number |
| **Requirement Traceability** | Section 3.1: QR Table Redirection; REQ-3 |
| **Test Case Description** | This test case verifies that the system prevents a customer from placing an order without a valid table number in the session, displaying an appropriate prompt to enter or select a table number. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Order placement route (`POST /order`) |
| 2 | Session table number validation |
| 3 | Cart checkout form |

**Pre-condition:** Customer has items in the cart but has NOT scanned a QR code. No table number is stored in the session.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer attempts to place an order with a non-empty cart but no table number in the session. | • Order is NOT saved to the database. • System displays a prompt: "Please enter your table number to place an order." • Customer must provide a valid table number before the order can be submitted. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the menu manually (without scanning a QR code). |
| 2 | Add at least one item to the cart. |
| 3 | Navigate to the cart page (`/cart`). |
| 4 | Attempt to click "Place Order" without a table number in the session. |
| 5 | Verify the system rejects the order. |
| 6 | Verify a message prompts the customer to enter a table number. |
| 7 | Enter a valid table number and retry placing the order. |

**Post-condition**
- Order is not saved without a valid table number.
- After providing a table number, the order is successfully placed and the tracking page is displayed.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-28 |
| **Test Case Name** | Admin Unauthorised Access Attempt by Staff |
| **Requirement Traceability** | Section 5.3: Security Requirements (RBAC); Section 2.5: Design Constraints |
| **Test Case Description** | This test case verifies that the Role-Based Access Control (RBAC) system correctly prevents a Staff user from accessing Admin-only routes. The system must redirect the staff user appropriately without granting admin access. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Admin middleware (`admin` middleware) |
| 2 | Admin dashboard route (`/admin/dashboard`) |
| 3 | Admin analytics route (`/admin/analytics`) |

**Pre-condition:** User is authenticated with `role = staff`. User attempts to access an admin-protected route.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Staff user navigates directly to `/admin/dashboard` or any `/admin/*` route. | • Access is denied. • The `admin` middleware rejects the request. • Staff user is redirected to the staff dashboard (`/staff/orders`) or shown a 403 Forbidden error. • No admin data is exposed. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as a user with `role = staff`. |
| 2 | Manually navigate to `/admin/dashboard` in the browser address bar. |
| 3 | Verify the `admin` middleware intercepts the request. |
| 4 | Verify the staff user is NOT shown the admin dashboard. |
| 5 | Verify the user is redirected to `/staff/orders` or shown a 403 error page. |
| 6 | Repeat for `/admin/analytics` and `/admin/products`. |
| 7 | Verify no admin data is accessible at any point. |

**Post-condition**
- Staff user cannot access any admin-only routes.
- RBAC correctly enforces role separation between Staff and Admin.
- No sensitive admin data is exposed to unauthorised users.

---

*End of Test Case Specifications*
*Total Test Cases: 28 | Project: Bossku House QR Digital Ordering System*
*Requirement Traceability: REQ-1 to REQ-18 (SRS v1.0)*
