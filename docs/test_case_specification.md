# APPENDIX A - Test Case Specification (Revised)

## 1. Customer Module

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-01 |
| **Test Case Name** | New Customer Registration |
| **Requirement Traceability** | Section 2.3: User Classes (Authenticated Customers) |
| **Test Case Description** | This test case verifies that a new customer can successfully create an account in the Bossku House system using valid credentials. |
| **Item(s) to be tested** | |
| 1 | Registration page |
| 2 | User Authentication module |
| **Pre-condition:** | User is on the registration page and not currently logged in. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Valid name, email, and password. | • Account created in the users table. <br> • User redirected to the customer menu. |
| **Test Procedure Steps** | |
| 1 | Navigate to the `/c/register` page. |
| 2 | Enter a valid name, email, and password. |
| 3 | Click the "Register" button. |
| 4 | System validates input data. |
| 5 | System creates the user record. |
| 6 | Customer menu is displayed. |
| **Post-condition** | |
| • | Customer successfully registered into the system. |
| • | Customer session is active and role is set to 'customer'. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-02 |
| **Test Case Name** | Customer Login |
| **Requirement Traceability** | REQ-19: Track loyalty points for registered customers. |
| **Test Case Description** | This test case verifies that a registered customer can successfully log in using their credentials. |
| **Item(s) to be tested** | |
| 1 | Login page |
| 2 | Authentication module |
| **Pre-condition:** | A valid customer account already exists in the system. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Registered Email. <br> • Correct Password. | • Username and password accepted. <br> • User redirected to the Customer Menu. |
| **Test Procedure Steps** | |
| 1 | Navigate to the `/c/login` page. |
| 2 | Enter registered email and password. |
| 3 | Click the "Sign In" button. |
| 4 | System validates credentials. |
| 5 | System authenticates the user. |
| 6 | Customer menu is displayed with "Welcome" message. |
| **Post-condition** | |
| • | User successfully logged into the system. |
| • | Customer session is active and points are visible. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-03 |
| **Test Case Name** | QR Menu Access & Table Binding |
| **Requirement Traceability** | REQ-1, REQ-2, REQ-3: QR redirection and table identification. |
| **Test Case Description** | This test case verifies that scanning a table-specific QR code correctly binds the table ID to the session. |
| **Item(s) to be tested** | |
| 1 | QR Redirection Module |
| 2 | Session Management |
| **Pre-condition:** | System is active and the specific table URL (e.g., table 5) is accessible. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Scan QR or access `/menu/5`. | • Redirected to Menu page. <br> • Table ID '5' stored in background session. |
| **Test Procedure Steps** | |
| 1 | Use a mobile device to scan the QR code for Table 5. |
| 2 | System processes the URL parameter. |
| 3 | System redirects to the digital menu. |
| 4 | Verify that the table number is preserved during ordering. |
| **Post-condition** | |
| • | Customer is on the correct menu for their table. |
| • | Any items added to cart are tied to Table 5. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-04 |
| **Test Case Name** | Shopping Cart Management |
| **Requirement Traceability** | REQ-7: Quantity control in the cart. |
| **Test Case Description** | This test case verifies that users can manage items in their virtual cart before checkout. |
| **Item(s) to be tested** | |
| 1 | Shopping Cart Modal |
| 2 | Price Calculation Engine |
| **Pre-condition:** | Products are displayed and available for selection. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Click 'Add to Cart'. <br> • Adjust quantities. | • Cart total updates in real-time. <br> • Items are stored correctly in the session. |
| **Test Procedure Steps** | |
| 1 | Navigate to the Menu page. |
| 2 | Click "Add to Cart" on a product. |
| 3 | Open the cart view. |
| 4 | Click the "+" or "-" buttons to change quantities. |
| 5 | Verify that the subtotal and total are correct. |
| **Post-condition** | |
| • | Cart state reflects the intended order correctly. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-05 |
| **Test Case Name** | Order Placement & Reference Tracking |
| **Requirement Traceability** | REQ-8: Reference number generation for orders. |
| **Test Case Description** | This test case verifies that an order can be successfully placed and tracked via a unique reference. |
| **Item(s) to be tested** | |
| 1 | Checkout Module |
| 2 | Order Tracking Page |
| **Pre-condition:** | Cart is not empty and table ID is set. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Submit order via checkout. | • Unique Reference generated (e.g., BK-1001). <br> • Redirected to Tracking page. |
| **Test Procedure Steps** | |
| 1 | Proceed to the checkout screen from the cart. |
| 2 | Enter any required order notes. |
| 3 | Click "Place Order". |
| 4 | System generates a unique reference number. |
| 5 | Navigate to the tracking page for that reference. |
| **Post-condition** | |
| • | Order is saved in MySQL and Firebase. |
| • | Staff dashboard notified in real-time. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-06 |
| **Test Case Name** | Product Review Submission |
| **Requirement Traceability** | REQ-18: Customers can leave star ratings and reviews. |
| **Test Case Description** | This test case verifies that customers can provide feedback on products. |
| **Item(s) to be tested** | |
| 1 | Review Submission Form |
| 2 | Feedback Database |
| **Pre-condition:** | User is viewing a specific product's review section. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • 5-star rating. <br> • Comment text. | • Review saved successfully. <br> • Success notification displayed. |
| **Test Procedure Steps** | |
| 1 | Locate the review section under a product. |
| 2 | Select a star rating (1-5). |
| 3 | Type a text comment. |
| 4 | Click "Submit Review". |
| **Post-condition** | |
| • | Review is visible to the admin and potentially other customers. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-07 |
| **Test Case Name** | Reward Redemption System |
| **Requirement Traceability** | REQ-20: Redeeming earned points for rewards. |
| **Test Case Description** | This test case verifies that loyalty points can be redeemed for restaurant rewards. |
| **Item(s) to be tested** | |
| 1 | Reward Redemption Module |
| 2 | Points Calculation Engine |
| **Pre-condition:** | User is logged in and has sufficient loyalty points. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Click 'Redeem' on a reward. | • Points deducted from balance. <br> • Reward record created for staff verification. |
| **Test Procedure Steps** | |
| 1 | Navigate to the `/rewards` page. |
| 2 | Select an available reward (e.g., "Free Drink"). |
| 3 | Click the "Redeem" button. |
| 4 | System checks point eligibility. |
| 5 | Verify points are deducted from the user profile. |
| **Post-condition** | |
| • | Redemption is logged and pending staff approval/usage. |

---

## 2. Staff Module

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-08 |
| **Test Case Name** | Real-time Order Management (Staff) |
| **Requirement Traceability** | REQ-9, REQ-10: Firebase dashboard and status updates. |
| **Test Case Description** | This test case verifies that staff can update order statuses in real-time. |
| **Item(s) to be tested** | |
| 1 | Staff Dashboard |
| 2 | Firebase Sync Module |
| **Pre-condition:** | Staff is logged in; an active order exists. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Change status to 'Preparing'. | • Database status updated. <br> • Customer tracking page reflects change instantly. |
| **Test Procedure Steps** | |
| 1 | Access the Staff Orders dashboard. |
| 2 | Locate an incoming order. |
| 3 | Click the "Preparing" button. |
| 4 | Check the customer tracking page for the update. |
| 5 | Click the "Served" button once ready. |
| **Post-condition** | |
| • | Order workflow progressed correctly. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-09 |
| **Test Case Name** | Cashier & Receipt Generation |
| **Requirement Traceability** | REQ-11: Payment processing and receipt generation. |
| **Test Case Description** | This test case verifies the payment workflow and receipt output. |
| **Item(s) to be tested** | |
| 1 | Cashier Module |
| 2 | Receipt Generation Engine |
| **Pre-condition:** | Order status is 'Served'. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Mark as 'Paid'. | • Order status set to 'Paid'. <br> • Professional receipt is viewable/printable. |
| **Test Procedure Steps** | |
| 1 | Navigate to the Cashier section. |
| 2 | Search for the order reference. |
| 3 | Click "Mark as Paid". |
| 4 | Click "View Receipt". |
| 5 | Verify all items and totals on the receipt. |
| **Post-condition** | |
| • | Transaction completed and logged for analytics. |

---

## 3. Admin Module

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-10 |
| **Test Case Name** | Dashboard Analytics Overview (Admin) |
| **Requirement Traceability** | REQ-16: Dashboard with metrics and growth trends. |
| **Test Case Description** | This test case verifies that business metrics are correctly aggregated on the dashboard. |
| **Item(s) to be tested** | |
| 1 | Admin Dashboard Metrics |
| 2 | Growth Calculation Engine |
| **Pre-condition:** | Sales data from multiple days exists. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • View dashboard home. | • Revenue and Order counts displayed. <br> • Growth % compared to yesterday is accurate. |
| **Test Procedure Steps** | |
| 1 | Log in as Admin. |
| 2 | Observe the summary cards for Revenue and Orders. |
| 3 | Verify that growth trends (Up/Down) are calculated correctly. |
| 4 | Check the "Top Sellers" list for accuracy. |
| **Post-condition** | |
| • | Admin has accurate business intelligence. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-11 |
| **Test Case Name** | Product Management (CRUD with Image) |
| **Requirement Traceability** | REQ-13: CRUD products with image uploads to Firebase. |
| **Test Case Description** | This test case verifies that products can be managed with cloud-stored images. |
| **Item(s) to be tested** | |
| 1 | Product Creation Form |
| 2 | Firebase Storage Integration |
| **Pre-condition:** | Admin is logged in. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • New product data. <br> • Image file upload. | • Product saved in Firebase Database. <br> • Image URL stored in record. |
| **Test Procedure Steps** | |
| 1 | Go to Admin -> Products -> Create. |
| 2 | Fill in name, description, and price. |
| 3 | Select a category. |
| 4 | Upload a product image from the computer. |
| 5 | Click "Save". |
| 6 | Verify the product appears on the menu with the image. |
| **Post-condition** | |
| • | Product is live and ready for ordering. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-12 |
| **Test Case Name** | Product Availability & Featured Toggle |
| **Requirement Traceability** | REQ-5, REQ-6: Featured and Availability toggles. |
| **Test Case Description** | This test case verifies visibility controls for individual products. |
| **Item(s) to be tested** | |
| 1 | Product Visibility Toggle |
| 2 | Featured Section Module |
| **Pre-condition:** | Products exist in the database. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Toggle 'Is Available' to OFF. | • Product is hidden or disabled on customer menu. |
| **Test Procedure Steps** | |
| 1 | Edit an existing product. |
| 2 | Uncheck "Is Available" and Save. |
| 3 | Check "Is Featured" and Save. |
| 4 | Verify customer menu reflected these changes. |
| **Post-condition** | |
| • | Product visibility state is correctly managed. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-13 |
| **Test Case Name** | Category Management & Sorting |
| **Requirement Traceability** | REQ-12: CRUD categories and sort order. |
| **Test Case Description** | This test case verifies category ordering on the navigation bar. |
| **Item(s) to be tested** | |
| 1 | Category CRUD |
| 2 | Nav Bar Sort Engine |
| **Pre-condition:** | Multiple categories exist. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Set 'Sort Order' to 1. | • Category appears first on the menu bar. |
| **Test Procedure Steps** | |
| 1 | Edit a Category (e.g., 'Main Course'). |
| 2 | Update the 'Sort Order' value. |
| 3 | Save and refresh the Customer Menu. |
| 4 | Confirm the category position in the list. |
| **Post-condition** | |
| • | Menu navigation is correctly ordered. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-14 |
| **Test Case Name** | Admin Review Moderation |
| **Requirement Traceability** | REQ-14: Moderate (delete) customer product reviews. |
| **Test Case Description** | This test case verifies the moderation of user feedback. |
| **Item(s) to be tested** | |
| 1 | Review Management Dashboard |
| **Pre-condition:** | Inappropriate review exists. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Delete specific review. | • Review removed from database and UI. |
| **Test Procedure Steps** | |
| 1 | Navigate to Admin -> Reviews. |
| 2 | Locate the inappropriate review. |
| 3 | Click the Delete button. |
| 4 | Verify it is gone from the product page. |
| **Post-condition** | |
| • | Menu feedback is kept professional. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-15 |
| **Test Case Name** | Admin Reward Management |
| **Requirement Traceability** | REQ-15: Manage reward point system. |
| **Test Case Description** | This test case verifies the configuration of loyalty rewards. |
| **Item(s) to be tested** | |
| 1 | Reward CRUD Module |
| **Pre-condition:** | Admin is logged in. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • New reward details. | • Reward available for customer redemption. |
| **Test Procedure Steps** | |
| 1 | Go to Admin -> Rewards. |
| 2 | Click "Add Reward". |
| 3 | Enter points cost and description. |
| 4 | Save and verify on customer reward page. |
| **Post-condition** | |
| • | Reward catalog updated. |

---

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-16 |
| **Test Case Name** | Admin Analytics Export |
| **Requirement Traceability** | REQ-17: Export analytics to CSV/Excel. |
| **Test Case Description** | This test case verifies the data portability of sales metrics. |
| **Item(s) to be tested** | |
| 1 | Analytics Export Engine |
| **Pre-condition:** | Sales history is populated. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Request CSV/Excel export. | • File download initiated. <br> • File contains correct sales rows. |
| **Test Procedure Steps** | |
| 1 | Navigate to Analytics. |
| 2 | Select a date range. |
| 3 | Click the Export button. |
| 4 | Open and verify the downloaded file. |
| **Post-condition** | |
| • | Data exported successfully for reporting. |

---

## 4. Security & Access Control

| | |
| :--- | :--- |
| **Tested By:** | Syahiran (Student/Developer) |
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-17 |
| **Test Case Name** | Role-Based Access Control (RBAC) |
| **Requirement Traceability** | Section 5.3: Security Requirements (RBAC Enforcement). |
| **Test Case Description** | This test case verifies that unauthorized roles cannot access restricted routes. |
| **Item(s) to be tested** | |
| 1 | Middleware Protection |
| 2 | Role Authorization Module |
| **Pre-condition:** | Logged in as 'Customer'. |
| **Specifications** | |
| **Input** | **Expected Output/Result** |
| • Attempt access to `/admin/dashboard`. | • Access Denied (403). <br> • Redirected to unauthorized page. |
| **Test Procedure Steps** | |
| 1 | Log in as a Customer. |
| 2 | Manually type `/admin` in the browser address bar. |
| 3 | Verify that the system blocks the request. |
| 4 | Log in as Staff and attempt to access `/admin/analytics`. |
| 5 | Verify access is again blocked. |
| **Post-condition** | |
| • | System integrity and data privacy maintained. |
