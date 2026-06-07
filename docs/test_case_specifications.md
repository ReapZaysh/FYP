# Test Case Specification — Bossku House QR Digital Ordering System
**Version:** 1.0 | **Project:** FYP — Bossku House

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-01 |
| **Test Case Name** | New Customer Registration |
| **Requirement Traceability** | Section 2.3: User Classes (Authenticated Customers); REQ-9 |
| **Test Case Description** | This test case verifies that a new customer can successfully create an account in the Bossku House system by providing valid name, email, and password credentials. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Customer Registration page (`/c/register`) |
| 2 | User Authentication module (Laravel Breeze) |

**Pre-condition:** User is on the `/c/register` page and is not currently logged in.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Valid name, email address, and matching password (min. 8 characters). | • New user account created in the `users` table with `role = customer`. • Customer is automatically logged in. • Customer is redirected to the menu page. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/c/register` page. |
| 2 | Enter a valid full name (e.g., "Ali Hassan"). |
| 3 | Enter a valid email address (e.g., "ali@email.com"). |
| 4 | Enter a password with at least 8 characters. |
| 5 | Re-enter the same password in the confirmation field. |
| 6 | Click the "Register" button. |
| 7 | System validates the input data and creates the user record. |

**Post-condition**
- Customer account is successfully created and saved in the database.
- Customer session is active and role is set to `customer`.
- Customer is redirected to the menu page.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-02 |
| **Test Case Name** | Customer Login with Valid Credentials |
| **Requirement Traceability** | Section 2.3: User Classes (Authenticated Customers); Section 5.3: Security Requirements |
| **Test Case Description** | This test case verifies that a registered customer can successfully log into the Bossku House system using a valid email and password at the customer login page. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Customer Login page (`/c/login`) |
| 2 | Laravel Authentication middleware |

**Pre-condition:** The customer already has a registered account. The customer is not currently logged in.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Registered email address and correct password. | • Authentication session is created. • Customer is redirected to the menu page. • Authenticated features (rewards, profile) become accessible. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/c/login` page. |
| 2 | Enter the registered email address. |
| 3 | Enter the correct password. |
| 4 | Click the "Login" button. |
| 5 | System validates the credentials against the database. |
| 6 | System creates an authenticated session. |
| 7 | Customer is redirected to the menu page. |

**Post-condition**
- Customer is authenticated and an active session exists.
- Customer can access protected routes such as `/my-profile` and `/rewards/redeem`.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-03 |
| **Test Case Name** | QR Code Table Redirection |
| **Requirement Traceability** | Section 3.1: QR Table Redirection; REQ-1, REQ-2, REQ-3 |
| **Test Case Description** | This test case verifies that scanning a table QR code correctly redirects the customer to the menu page with the table number automatically stored in the session. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | QR Code redirection URL mechanism |
| 2 | Session/local storage management for table ID |
| 3 | Menu page (`/menu/{table}`) |

**Pre-condition:** A unique QR code exists for a restaurant table. The customer has a mobile device with camera and internet access.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer scans the table QR code (e.g., encodes URL `/menu?table=5`). | • Browser opens the menu page at `/menu?table=5`. • Table number `5` is stored in the customer's session. • Menu page loads with table number pre-filled. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Customer opens the camera app on their mobile device. |
| 2 | Customer points the camera at the QR code on the restaurant table. |
| 3 | Device reads and decodes the QR code URL. |
| 4 | Browser opens the decoded URL (e.g., `/menu?table=5`). |
| 5 | System reads the `table` query parameter from the URL. |
| 6 | System stores the table number in the customer's session. |
| 7 | Menu page loads and displays all available products. |

**Post-condition**
- Customer is on the menu page.
- Table number is saved in the session and will be automatically attached to any order placed in this session.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-04 |
| **Test Case Name** | Browse Menu by Category |
| **Requirement Traceability** | Section 3.2: Menu Browsing and Ordering; REQ-4 |
| **Test Case Description** | This test case verifies that a customer can filter and view menu products by selecting a specific product category on the menu page. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Menu page (`/menu`) |
| 2 | Category filter component |
| 3 | Product listing module |

**Pre-condition:** Customer is on the menu page. At least one category and one product exist in the database.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer clicks on a category name (e.g., "Main Course"). | • Only products belonging to the "Main Course" category are displayed. • Each product shows its name, image, price, and description. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/menu` page. |
| 2 | Observe the list of available categories displayed as filter tabs. |
| 3 | Click on the "Main Course" category tab. |
| 4 | System filters products by the selected category. |
| 5 | System renders only the products belonging to "Main Course". |
| 6 | Verify each product card shows name, image, price, and description. |
| 7 | — |

**Post-condition**
- Only products from the selected category are visible on the menu.
- Products from other categories are hidden from view.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-05 |
| **Test Case Name** | Add Item to Cart |
| **Requirement Traceability** | Section 3.2: Menu Browsing and Ordering; REQ-5 |
| **Test Case Description** | This test case verifies that a customer can successfully add a product to the shopping cart from the menu page, and that the cart count and total are updated in real-time. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Menu page product listing |
| 2 | Cart session management (`/cart/add/{product}`) |

**Pre-condition:** Customer is on the `/menu` page. At least one product is available.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer clicks "Add to Cart" on a product. | • Product is added to the session cart with quantity 1. • Cart icon badge updates to reflect the new item count. • If the item already exists in the cart, quantity is incremented by 1. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/menu` page. |
| 2 | Locate a product (e.g., "Nasi Lemak Special"). |
| 3 | Click the "Add to Cart" button on the product. |
| 4 | System sends a POST request to `/cart/add/{product}`. |
| 5 | System adds the product to the session cart. |
| 6 | Cart icon badge updates to show the new item count. |
| 7 | — |

**Post-condition**
- The product is stored in the session cart.
- The cart count displayed in the navigation bar is updated correctly.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-06 |
| **Test Case Name** | Manage Cart — Adjust Quantity and Remove Items |
| **Requirement Traceability** | Section 3.2: Menu Browsing and Ordering; REQ-5 |
| **Test Case Description** | This test case verifies that a customer can increase/decrease item quantities, remove individual items, and clear the entire cart from the cart page, with totals recalculating correctly. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Cart page (`/cart`) |
| 2 | Cart session management (add, remove, clear routes) |

**Pre-condition:** Customer has at least one item in the cart. Customer is on the `/cart` page.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer clicks "+" to increase quantity. | • Item quantity increments by 1. • Subtotal and grand total recalculate immediately. |
| Customer clicks "–" to decrease quantity to zero. | • Item is automatically removed from the cart. |
| Customer clicks "Remove" on an item. | • Item is removed from the cart. Grand total updates. |
| Customer clicks "Clear Cart". | • All items removed. Cart shows empty state message. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to the `/cart` page. |
| 2 | Click "+" on an item to increase its quantity. |
| 3 | Verify the item quantity and subtotal update correctly. |
| 4 | Click "–" to decrease quantity until it reaches zero. |
| 5 | Verify the item is removed from the cart automatically. |
| 6 | Click "Remove" on another item. |
| 7 | Click "Clear Cart" and verify the cart is emptied. |

**Post-condition**
- Cart correctly reflects the updated item quantities or is empty after clearing.
- Grand total is accurate and consistent with the remaining items.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 4 (High) |
| **Test Case Number** | TC-07 |
| **Test Case Name** | Place Order |
| **Requirement Traceability** | Section 3.2: Menu Browsing and Ordering; REQ-6, REQ-7 |
| **Test Case Description** | This test case verifies that a customer can successfully place an order, generating a unique reference number, saving the order to the database, and triggering a real-time Firebase notification on the staff dashboard. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Cart page checkout function |
| 2 | Order creation module (`/order`) |
| 3 | Firebase real-time notification service |
| 4 | Unique order reference number generator |

**Pre-condition:** Customer has at least one item in the cart. A valid table number is stored in the session.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer clicks "Place Order" with a non-empty cart and a valid table number in session. | • Order saved to the database with status "Pending". • Unique reference number generated and stored. • Session cart is cleared. • Customer redirected to `/track/{reference}`. • Staff dashboard receives a Firebase push notification. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Ensure at least one item is in the cart and a table number is in the session. |
| 2 | Navigate to the `/cart` page. |
| 3 | Click the "Place Order" / "Checkout" button. |
| 4 | System validates the cart and session data. |
| 5 | System creates an Order and OrderItems record in the database. |
| 6 | System generates a unique reference number for the order. |
| 7 | System pushes a notification to Firebase for the staff dashboard. |

**Post-condition**
- Order is saved in the database with status "Pending".
- A unique reference number is assigned to the order.
- Customer is on the order tracking page displaying their reference number.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 3 (Medium) |
| **Test Case Number** | TC-08 |
| **Test Case Name** | Track Order Status in Real-time |
| **Requirement Traceability** | Section 3.3: Staff Order Processing; REQ-7, REQ-8 |
| **Test Case Description** | This test case verifies that a customer can track their order status in real-time using their reference number, and that the status updates automatically on the tracking page when staff changes it via Firebase. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Order tracking page (`/track/{reference}`) |
| 2 | Firebase real-time listener |

**Pre-condition:** A successful order has been placed. The customer has the unique reference number.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Customer navigates to `/track/{reference}`. | • Tracking page displays order items, table number, and current status. • Status badge updates automatically (without page refresh) when staff changes the status via Firebase. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Navigate to `/track/{reference}` using the order's reference number. |
| 2 | Verify the page displays order items, table number, and current status ("Pending"). |
| 3 | On the staff dashboard, change the order status to "Preparing". |
| 4 | Observe the tracking page without refreshing. |
| 5 | Verify the status badge changes to "Preparing" automatically via Firebase. |
| 6 | On the staff dashboard, change the status to "Served". |
| 7 | Verify the tracking page updates to "Served" automatically. |

**Post-condition**
- Tracking page reflects the correct and current order status in real-time.
- No manual page refresh is required for the status to update.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Use Case Testing |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-09 |
| **Test Case Name** | View Rewards Catalogue and Redeem Reward |
| **Requirement Traceability** | Section 3.4: Customer Engagement; REQ-9, REQ-10 |
| **Test Case Description** | This test case verifies that an authenticated customer with sufficient loyalty points can successfully redeem a reward item from the rewards catalogue, and that their points balance is correctly deducted. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Rewards catalogue page (`/rewards`) |
| 2 | Reward redemption module (`/rewards/redeem/{id}`) |
| 3 | Loyalty points balance in the users table |

**Pre-condition:** Customer is authenticated. Customer has sufficient loyalty points. At least one reward item is available in the catalogue.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Authenticated customer with sufficient points clicks "Redeem" on a reward item. | • Customer's points balance is deducted by the reward's required point cost. • A redemption record is created in the database. • Success message displayed: "Reward redeemed successfully!" |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as a customer with sufficient loyalty points. |
| 2 | Navigate to the `/rewards` page. |
| 3 | Note the current points balance displayed. |
| 4 | Click "Redeem" on an available reward item. |
| 5 | System sends a POST request to `/rewards/redeem/{id}`. |
| 6 | System verifies the customer has sufficient points. |
| 7 | System deducts the points and displays a success message. |

**Post-condition**
- Customer's points balance is reduced by the correct amount.
- A redemption record exists in the database.
- Updated points balance is visible on the customer's profile page.

---

| **Tested By** | [Your Name] |
|---|---|
| **Test Type** | Functional Testing |
| **Test Design Technique** | Equivalence Partitioning |
| **Risk Number** | 2 (Low) |
| **Test Case Number** | TC-10 |
| **Test Case Name** | Submit Product Review |
| **Requirement Traceability** | Section 3.4: Customer Engagement; REQ-11 |
| **Test Case Description** | This test case verifies that an authenticated customer who has previously ordered a product can successfully submit a star rating and text review, which is saved and displayed on the menu page. |

**Item(s) to be tested**

| # | Item |
|---|---|
| 1 | Product review form on the menu page |
| 2 | Review submission module (`/reviews/{product}`) |
| 3 | Product reviews display |

**Pre-condition:** Customer is authenticated. Customer has previously ordered the product being reviewed.

| **Specifications** | |
|---|---|
| **Input** | **Expected Output / Result** |
| Valid star rating (1–5) and review text submitted for a product the customer has ordered. | • Review is saved in the database linked to the product and customer. • Review text and rating appear on the product listing. • Product's average rating is updated. |

**Test Procedure Steps**

| Step | Action |
|---|---|
| 1 | Log in as a customer who has previously ordered a product. |
| 2 | Navigate to the menu page and find the reviewed product. |
| 3 | Select a star rating (e.g., 4 stars). |
| 4 | Type a review (e.g., "Great food, highly recommended!"). |
| 5 | Click "Submit Review". |
| 6 | System sends a POST request to `/reviews/{product}`. |
| 7 | System validates and saves the review to the database. |

**Post-condition**
- Review is stored in the database.
- The review text and star rating are visible under the product on the menu page.
- The product's overall average rating reflects the new review.
