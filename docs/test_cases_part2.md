
---

| Use Case ID | UC_011 |
|---|---|
| Use Case Name | Redeem Reward Item |
| Description | An authenticated customer redeems a reward item from the catalogue by spending their accumulated loyalty points. The system deducts the points and records the redemption. |
| Primary Actor | Authenticated Customer |
| Include use cases | UC_007 (View Rewards Catalogue) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer is authenticated. 2. Customer has sufficient loyalty points. 3. The chosen reward item is available. |
| | 1.1 | Customer navigates to `/rewards` and views the rewards catalogue. |
| | 1.2 | Customer clicks "Redeem" on a reward item. |
| | 1.3 | A POST request is sent to `/rewards/redeem/{id}` (requires auth middleware). |
| | 1.4 | System checks the customer's current points balance against the reward's required points. |
| | 1.5 | Points are sufficient: system deducts the required points from the customer's balance. |
| | 1.6 | System creates a redemption record in the database. |
| | 1.7 | System redirects the customer with a success message: "Reward redeemed successfully!" |
| | Post-Condition | Customer's points are deducted. Redemption record is saved. Updated points balance is reflected on profile. |
| Alternate Flow | — | Customer has exactly the required points → redeems successfully; points balance becomes 0. |
| Robust Flow | Pre-Condition | Customer has insufficient points, the reward no longer exists, or a duplicate request is sent. |
| | 2.1 | Insufficient points: system returns an error "You do not have enough points to redeem this reward." |
| | 2.2 | Reward not found: system returns HTTP 404 "Reward item no longer available." |
| | 2.3 | Duplicate/rapid submission (double-click): second request is rejected gracefully; points are not deducted twice. |

---

| Use Case ID | UC_012 |
|---|---|
| Use Case Name | Submit Product Review |
| Description | An authenticated customer submits a star rating (1–5) and a text review for a menu product they have previously ordered. The review is saved and displayed on the product listing. |
| Primary Actor | Authenticated Customer |
| Include use cases | UC_005 (Place Order — customer must have ordered the product) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer is authenticated. 2. Customer has previously ordered the product. 3. Customer is on the product review section of the menu page. |
| | 1.1 | Customer selects a star rating (1 to 5 stars). |
| | 1.2 | Customer types their review text in the feedback field. |
| | 1.3 | Customer clicks "Submit Review". A POST request is sent to `/reviews/{product}`. |
| | 1.4 | System validates the input (rating required; text must not exceed max length). |
| | 1.5 | System saves the review in the database, linked to the product and the customer. |
| | 1.6 | System returns a success message and the review appears under the product. |
| | Post-Condition | Review is saved in the DB. The product's average rating is updated. Review is visible to all menu visitors. |
| Alternate Flow | — | Customer submits only a star rating without text → system accepts if text is optional per system configuration. |
| Robust Flow | Pre-Condition | Rate limit is exceeded or the customer has not ordered the product. |
| | 2.1 | Rate limit exceeded (> 3 reviews/min): system returns HTTP 429 "Too many requests. Please try again later." |
| | 2.2 | Customer attempts to review a product they have not ordered: system rejects with "You can only review products you have ordered." |
| | 2.3 | Customer is not authenticated: auth middleware redirects to `/c/login`. |

---

| Use Case ID | UC_013 |
|---|---|
| Use Case Name | View My Profile / Edit Profile |
| Description | An authenticated customer views their profile page showing their name, email, loyalty points balance, and order history. The customer can update their name, email, or password. |
| Primary Actor | Authenticated Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer is authenticated. 2. Customer navigates to `/my-profile` (protected by auth middleware). |
| | 1.1 | System loads the customer's data from the database (name, email, points, order history). |
| | 1.2 | System renders the profile page with current information and an editable form. |
| | 1.3 | Customer updates name and/or email and clicks "Save" → PATCH to `/profile`. |
| | 1.4 | System validates the updated fields (email uniqueness, required fields). |
| | 1.5 | System saves the updated information to the database. |
| | 1.6 | System displays a success message: "Profile updated successfully." |
| | Post-Condition | Customer's updated information is saved in the database and reflected on the profile page. |
| Alternate Flow | — | Customer only views profile without making any changes → no request is sent; read-only display. |
| Robust Flow | Pre-Condition | Customer submits invalid or conflicting data (e.g., email already used by another account). |
| | 2.1 | System detects the new email is already registered to a different user. |
| | 2.2 | System displays a validation error: "This email address is already in use." |
| | 2.3 | Customer corrects the email and resubmits the form. |

---

| Use Case ID | UC_014 |
|---|---|
| Use Case Name | View Real-time Orders Dashboard (Staff) |
| Description | Authenticated staff members access the staff dashboard at `/staff/orders` to view all incoming and active orders in real-time, with automatic updates powered by Firebase. |
| Primary Actor | Staff |
| Include use cases | Login — Staff/Admin via Laravel Auth |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Staff is authenticated with `role = staff`. 2. Staff navigates to `/staff/orders`. |
| | 1.1 | The `auth` and `staff` middleware validate the user's role and authentication status. |
| | 1.2 | System queries the database for all active orders (status: Pending, Preparing). |
| | 1.3 | System renders the staff dashboard with all active orders shown as cards (order reference, table, items, status). |
| | 1.4 | System initialises a Firebase real-time listener on the page. |
| | 1.5 | When a customer places a new order, Firebase pushes an event to the dashboard. |
| | 1.6 | The new order card appears on the dashboard automatically without a page reload. |
| | Post-Condition | Staff dashboard displays all active orders and receives new orders in real-time. |
| Alternate Flow | — | No active orders exist → dashboard displays "No pending orders at this time." |
| Robust Flow | Pre-Condition | Staff is unauthenticated, has the wrong role, or Firebase is unavailable. |
| | 2.1 | Unauthenticated access: system redirects to `/login`. |
| | 2.2 | Wrong role (e.g., customer accessing staff route): middleware rejects access and redirects appropriately. |
| | 2.3 | Firebase unavailable: dashboard shows existing DB data; a polling fallback or "Live updates paused" warning is displayed. |

---

| Use Case ID | UC_015 |
|---|---|
| Use Case Name | Update Order Status (Staff) |
| Description | Staff changes the status of an order (Pending → Preparing → Served, or Cancelled). The change is saved to the database and pushed to Firebase so the customer's tracking page updates instantly. |
| Primary Actor | Staff |
| Include use cases | UC_014 (View Real-time Orders Dashboard) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Staff is authenticated. 2. At least one active order is visible on the dashboard. |
| | 1.1 | Staff clicks the "Update Status" button on an order card and selects the new status. |
| | 1.2 | A PATCH request is sent to `/staff/orders/{reference}` with the new status value. |
| | 1.3 | System validates that the status transition is valid (e.g., cannot revert from Served to Pending). |
| | 1.4 | System updates the `status` field of the order in the database. |
| | 1.5 | System writes the updated status to Firebase. |
| | 1.6 | The customer's tracking page receives the Firebase event and updates the status badge automatically. |
| | Post-Condition | Order status is updated in the DB. Customer tracking page reflects the new status in real-time. |
| Alternate Flow | — | Staff selects "Cancelled" → order status set to "Cancelled"; customer tracking page shows cancellation notice. |
| Robust Flow | Pre-Condition | An invalid status value is submitted or the order is already in a final state (Paid). |
| | 2.1 | Invalid status value: server-side validation rejects the request and returns a validation error. |
| | 2.2 | Order already Paid: system prevents the status update and returns "Cannot change status of a paid order." |
| | 2.3 | Firebase write fails: database is still updated; system logs the Firebase failure and retries in background. |

---

| Use Case ID | UC_016 |
|---|---|
| Use Case Name | Access Cashier View (Staff) |
| Description | Staff accesses the cashier interface at `/staff/cashier` to see all orders that have been served and are awaiting payment processing. |
| Primary Actor | Staff |
| Include use cases | UC_017 (Mark Order as Paid) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Staff is authenticated with `role = staff`. 2. At least one order has status "Served". |
| | 1.1 | Staff navigates to `/staff/cashier`. |
| | 1.2 | The `auth` and `staff` middleware validate the request. |
| | 1.3 | System queries the database for all orders with status = "Served". |
| | 1.4 | System renders the cashier view, displaying each served order with: table number, items, quantities, and total amount. |
| | Post-Condition | Cashier view is displayed. Staff can see all orders pending payment and can proceed to mark them as paid. |
| Alternate Flow | — | No served orders exist → cashier page displays "No orders awaiting payment." |
| Robust Flow | Pre-Condition | Staff is unauthenticated or the database query fails. |
| | 2.1 | Unauthenticated access: system redirects to `/login`. |
| | 2.2 | Database query error: system displays an error page and advises staff to refresh or contact the administrator. |
| | 2.3 | Staff role middleware fails: access is denied; user redirected to their appropriate dashboard. |

---

| Use Case ID | UC_017 |
|---|---|
| Use Case Name | Mark Order as Paid (Staff) |
| Description | Staff processes payment for a served order by marking it as "Paid" in the system. This triggers loyalty point calculation for authenticated customers and makes the receipt available. |
| Primary Actor | Staff |
| Include use cases | UC_018 (Generate PDF Receipt), UC_010 (Earn Loyalty Points — if authenticated customer) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Order has status "Served". 2. Staff is on the cashier view at `/staff/cashier`. |
| | 1.1 | Staff clicks "Mark as Paid" on the order in the cashier view. |
| | 1.2 | A PATCH request is sent to `/staff/orders/{reference}/pay`. |
| | 1.3 | System validates the order exists and its current status is "Served". |
| | 1.4 | System updates the order status to "Paid" in the database. |
| | 1.5 | If the order is linked to an authenticated customer, system calculates and awards loyalty points. |
| | 1.6 | System makes the "Generate Receipt" link available for this order. |
| | Post-Condition | Order status is "Paid". Loyalty points awarded to authenticated customer (if applicable). PDF receipt can be generated. |
| Alternate Flow | — | Order belongs to a guest customer → step 1.5 is skipped; only the status is updated to "Paid". |
| Robust Flow | Pre-Condition | The order is already paid, the status is not "Served", or a concurrent payment attempt is made. |
| | 2.1 | Order already "Paid": system ignores the duplicate request; no changes made; no double points awarded. |
| | 2.2 | Status is not "Served" (e.g., still "Preparing"): system rejects with "Order must be Served before it can be marked as Paid." |
| | 2.3 | Database lock prevents concurrent updates: only the first request succeeds; the second is rejected gracefully. |

---

| Use Case ID | UC_018 |
|---|---|
| Use Case Name | Generate PDF Receipt (Staff) |
| Description | Staff generates and downloads a professional PDF receipt for a paid order using `barryvdh/laravel-dompdf`. The receipt contains an itemised list, totals, table number, and timestamp. |
| Primary Actor | Staff |
| Include use cases | UC_017 (Mark Order as Paid) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Order status is "Paid". 2. Staff navigates to `/staff/orders/{reference}/receipt` or clicks "Generate Receipt". |
| | 1.1 | Staff clicks the "Generate Receipt" button for a paid order. |
| | 1.2 | A GET request is sent to `/staff/orders/{reference}/receipt`. |
| | 1.3 | System fetches the order and all its items from the database. |
| | 1.4 | `laravel-dompdf` renders a pre-designed HTML receipt template into a PDF document. |
| | 1.5 | System streams the PDF as a downloadable file response. |
| | 1.6 | The browser downloads the receipt PDF. |
| | Post-Condition | A PDF receipt is successfully generated and downloaded. The receipt includes all order details, items, totals, and the restaurant branding. |
| Alternate Flow | — | Staff needs to re-print → same route generates a fresh PDF on demand without any state change. |
| Robust Flow | Pre-Condition | The order is not found, is not yet paid, or the PDF rendering fails. |
| | 2.1 | Order not found: system returns HTTP 404 "Order not found." |
| | 2.2 | Order not yet paid: system returns an error "Receipt can only be generated for paid orders." |
| | 2.3 | `dompdf` rendering failure: system returns HTTP 500 and advises staff to retry or use the CSV export option. |

---

| Use Case ID | UC_019 |
|---|---|
| Use Case Name | View Order History (Staff) |
| Description | Staff views a paginated list of all historical orders (Paid, Served, Cancelled) at `/staff/history` for reference and record-keeping. |
| Primary Actor | Staff |
| Include use cases | UC_020 (Generate Sales Report — extends) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Staff is authenticated with `role = staff`. 2. Historical order records exist in the database. |
| | 1.1 | Staff navigates to `/staff/history`. |
| | 1.2 | Middleware validates the staff role and authentication. |
| | 1.3 | System queries the database for all non-active orders (Paid, Cancelled, Served), ordered by date descending. |
| | 1.4 | System renders the history table with pagination (25 records per page). |
| | 1.5 | Each row shows: reference number, table, status, total amount, and order date. |
| | Post-Condition | Order history table is displayed. Staff can browse, search, or filter historical orders. |
| Alternate Flow | — | Staff filters by date range → system re-queries orders within the specified date range. |
| Robust Flow | Pre-Condition | No historical orders exist, or the dataset is very large causing a timeout. |
| | 2.1 | No orders found: system displays "No order history found for the selected period." |
| | 2.2 | Large dataset: pagination limits results to 25 per page to prevent performance issues. |
| | 2.3 | DB query timeout: system displays an error message with a retry button. |

---

| Use Case ID | UC_020 |
|---|---|
| Use Case Name | Generate Sales Report (Staff) |
| Description | Staff generates a summary sales report for a selected date range, showing total revenue, number of orders, and top-selling products. The report can be viewed on-screen or exported. |
| Primary Actor | Staff |
| Include use cases | UC_019 (View Order History) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Staff is authenticated. 2. Paid orders exist in the system. 3. Staff navigates to `/staff/report`. |
| | 1.1 | Staff selects a start date and end date for the report period. |
| | 1.2 | Staff clicks "Generate Report". A GET request is sent to `/staff/report` with date parameters. |
| | 1.3 | System queries the database to aggregate order totals and product sales within the date range. |
| | 1.4 | System calculates: total revenue, total number of paid orders, and top-selling products. |
| | 1.5 | System renders the report view with summary statistics and a breakdown table. |
| | Post-Condition | Sales report is displayed on-screen for the selected period. Staff can review or export the data. |
| Alternate Flow | — | Staff exports as PDF → `laravel-dompdf` generates a downloadable PDF version of the report. |
| Robust Flow | Pre-Condition | No orders exist in the selected date range, or the aggregation query takes too long. |
| | 2.1 | No data found: system displays "No sales data found for the selected period." |
| | 2.2 | Long-running query: a loading spinner is displayed; query uses database indexing to minimise delay. |
| | 2.3 | Query timeout: system displays a timeout message and suggests narrowing the date range. |
