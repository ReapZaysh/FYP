# Test Cases — Bossku House QR Digital Ordering System
**Version:** 1.0

---

| Use Case ID | UC_001 |
|---|---|
| Use Case Name | Scan QR Code |
| Description | A Guest Customer scans the QR code placed on their restaurant table. The system redirects them to the menu page with the table number automatically stored in the session. |
| Primary Actor | Guest Customer |
| Include use cases | UC_002 (Browse Menu by Category) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. A unique QR code is placed on the restaurant table. 2. The customer has a mobile device with a working camera and active internet connection. |
| | 1.1 | Customer opens their camera app and points it at the QR code on the table. |
| | 1.2 | The device reads the QR code and opens the browser with URL `/menu?table={id}`. |
| | 1.3 | The system reads the `table` query parameter from the URL. |
| | 1.4 | The system stores the table number in the customer's session/local storage. |
| | 1.5 | The system loads the menu page with the table number pre-filled. |
| | Post-Condition | The customer is on the menu page. The table number is saved in the session and will be automatically attached to any order placed. |
| Alternate Flow | — | Customer manually types `/menu` in the browser without scanning → menu loads but table field is empty; customer must enter table number manually before placing an order. |
| Robust Flow | Pre-Condition | The QR code is damaged, unreadable, or the internet connection is unavailable. |
| | 2.1 | The camera fails to decode the QR code, or the browser displays a connection error. |
| | 2.2 | The system displays a user-friendly error message (e.g., "Unable to connect. Please check your internet."). |
| | 2.3 | The customer retries after ensuring network connectivity, or manually navigates to the menu URL. |

---

| Use Case ID | UC_002 |
|---|---|
| Use Case Name | Browse Menu by Category |
| Description | The customer views the restaurant menu and filters products by selecting a category (e.g., Drinks, Main Course). The system displays only products belonging to the selected category. |
| Primary Actor | Guest Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. The customer is on the `/menu` page. 2. At least one category and one product exist in the database. |
| | 1.1 | The system fetches all categories from the database and renders them as filter tabs. |
| | 1.2 | The customer clicks on a category name (e.g., "Main Course"). |
| | 1.3 | The system filters products by the selected category. |
| | 1.4 | The system renders the filtered product list, showing each product's name, image, price, and description. |
| | Post-Condition | Only products belonging to the selected category are displayed on the menu page. |
| Alternate Flow | — | Customer selects "All" → system displays all available products regardless of category. |
| Robust Flow | Pre-Condition | A selected category contains no products, or the database query fails. |
| | 2.1 | System receives an empty result set for the selected category or a database error occurs. |
| | 2.2 | If no products found: system displays "No items available in this category." |
| | 2.3 | If database error: system displays a generic error message and logs the exception. |

---

| Use Case ID | UC_003 |
|---|---|
| Use Case Name | Add Item to Cart |
| Description | The customer clicks "Add to Cart" on a product. The system adds it to the session-based cart and updates the cart count and total price in real-time. |
| Primary Actor | Guest Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. The customer is on the `/menu` page. 2. The product is available and visible on the menu. |
| | 1.1 | Customer clicks the "Add to Cart" button on a product. |
| | 1.2 | A POST request is sent to `/cart/add/{product}`. |
| | 1.3 | The system adds the product to the session cart with a quantity of 1. |
| | 1.4 | If the product is already in the cart, the system increments its quantity by 1. |
| | 1.5 | The system returns the updated cart count and the cart icon badge is refreshed. |
| | Post-Condition | The item is in the session cart. The cart icon reflects the updated item count. |
| Alternate Flow | — | Customer adds the same item multiple times → system increments quantity each time rather than duplicating the entry. |
| Robust Flow | Pre-Condition | The product has been deleted by the admin or the customer's session has expired. |
| | 2.1 | System attempts to find the product by ID in the database. |
| | 2.2 | Product not found: system returns HTTP 404 and displays "Product is no longer available." |
| | 2.3 | Session expired: system creates a new session; the customer must re-add items. |

---

| Use Case ID | UC_004 |
|---|---|
| Use Case Name | Manage Cart (Adjust Qty / Remove / Clear) |
| Description | The customer views their cart and can increase or decrease item quantities, remove individual items, or clear the entire cart before placing an order. |
| Primary Actor | Guest Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. The customer has at least one item in the cart. 2. Customer navigates to `/cart`. |
| | 1.1 | System loads all cart items from the session and displays them with quantities and subtotals. |
| | 1.2 | Customer clicks "+" to increase quantity → system increments qty and recalculates total. |
| | 1.3 | Customer clicks "–" to decrease quantity → system decrements qty; if qty reaches 0, item is removed. |
| | 1.4 | Customer clicks "Remove" → POST to `/cart/remove/{product}` → item removed from session cart. |
| | 1.5 | Customer clicks "Clear Cart" → DELETE to `/cart/clear` → all items removed from session. |
| | Post-Condition | Cart reflects the updated quantities/items. Grand total is recalculated and displayed correctly. |
| Alternate Flow | — | Customer decreases quantity to zero → system automatically removes that item from the cart without an explicit remove action. |
| Robust Flow | Pre-Condition | Customer attempts to remove a product not currently in the cart, or session expires mid-action. |
| | 2.1 | System receives remove request for a product ID not in the cart. |
| | 2.2 | System returns gracefully with no error; cart remains unchanged. |
| | 2.3 | If session expires: cart becomes empty; customer is shown an informational message to re-add items. |

---

| Use Case ID | UC_005 |
|---|---|
| Use Case Name | Place Order |
| Description | The customer submits their cart as an order. The system validates the cart, saves the order to the database with a unique reference number, and notifies the staff dashboard via Firebase in real-time. |
| Primary Actor | Guest Customer |
| Include use cases | UC_001 (Scan QR Code — table ID), Generate Unique Order Reference, Receive Real-time Order Updates |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. The cart has at least one item. 2. A valid table number is stored in the session. 3. Customer is on the `/cart` or checkout page. |
| | 1.1 | Customer clicks "Place Order" / "Checkout". |
| | 1.2 | A POST request is sent to `/order` (throttled at 5 requests/min). |
| | 1.3 | System validates the cart is not empty and table number is present. |
| | 1.4 | System creates an `Order` record in the database with status = "Pending". |
| | 1.5 | System creates `OrderItem` records for each cart item linked to the order. |
| | 1.6 | System generates a unique reference number and stores it with the order. |
| | 1.7 | System pushes a new-order notification to Firebase for the staff dashboard. |
| | 1.8 | System clears the session cart and redirects the customer to `/track/{reference}`. |
| | Post-Condition | Order is saved in the DB with status "Pending". Staff dashboard receives real-time notification. Customer lands on the order tracking page with their reference number. |
| Alternate Flow | — | Authenticated customer places order → system flags the order with the customer's user ID so loyalty points can be awarded when the order is paid. |
| Robust Flow | Pre-Condition | Rate limit exceeded, or Firebase is unreachable, or the database write fails. |
| | 2.1 | Rate limit exceeded: system returns HTTP 429 "Too Many Requests"; customer must wait before retrying. |
| | 2.2 | Firebase unreachable: order is still saved to DB; customer receives reference number; staff sees it on next refresh. |
| | 2.3 | DB write fails: transaction is rolled back; system displays "Order failed, please try again." |

---

| Use Case ID | UC_006 |
|---|---|
| Use Case Name | Track Order Status |
| Description | The customer uses their unique order reference number to view the real-time status of their order (Pending → Preparing → Served). The page updates automatically via Firebase. |
| Primary Actor | Guest Customer |
| Include use cases | Receive Real-time Order Updates (Firebase) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. An order has been successfully placed. 2. The customer has the reference number. 3. Customer navigates to `/track/{reference}`. |
| | 1.1 | System queries the database for the order using the reference number. |
| | 1.2 | System renders the tracking page showing order items, table number, and current status. |
| | 1.3 | System initialises a Firebase real-time listener on the page. |
| | 1.4 | When staff updates the order status, Firebase pushes the update to the page. |
| | 1.5 | The status badge on the tracking page updates automatically without a page reload. |
| | Post-Condition | The tracking page reflects the latest order status in real-time. |
| Alternate Flow | — | Order status is "Served" → page displays "Your order has been served!" and stops further status updates. |
| Robust Flow | Pre-Condition | An invalid reference is entered, or Firebase connection is lost. |
| | 2.1 | Invalid reference: system cannot find the order in the database. |
| | 2.2 | System returns HTTP 404 and displays "Order not found. Please check your reference number." |
| | 2.3 | Firebase disconnected: page shows last known status with a "Live updates paused — reconnecting…" warning. |

---

| Use Case ID | UC_007 |
|---|---|
| Use Case Name | View Rewards Catalogue |
| Description | Any visitor (guest or authenticated) can browse the rewards catalogue to see available reward items and their required loyalty point costs. Only authenticated customers can redeem rewards. |
| Primary Actor | Guest Customer / Authenticated Customer |
| Include use cases | UC_011 (Redeem Reward Item — authenticated only) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer navigates to `/rewards`. 2. At least one reward item has been configured by the admin. |
| | 1.1 | System queries the rewards table and fetches all available reward items. |
| | 1.2 | System renders the rewards catalogue showing each item's name, image, and required points. |
| | 1.3 | If the customer is authenticated, the system displays their current points balance and enables the "Redeem" button. |
| | 1.4 | If the customer is a guest, the "Redeem" button is disabled or replaced with a "Login to Redeem" prompt. |
| | Post-Condition | Rewards catalogue is displayed. Authenticated customers can proceed to redeem; guests are prompted to login. |
| Alternate Flow | — | Guest customer clicks "Login to Redeem" → redirected to `/c/login` page. |
| Robust Flow | Pre-Condition | No rewards are configured, or the database query fails. |
| | 2.1 | System queries rewards table and receives an empty result set. |
| | 2.2 | System displays "No rewards available at this time." message. |
| | 2.3 | If DB query fails: system logs the error and displays a generic error message. |

---

| Use Case ID | UC_008 |
|---|---|
| Use Case Name | Register Account |
| Description | A Guest Customer creates a new customer account by providing their name, email address, and password. Upon successful registration, the customer is automatically logged in. |
| Primary Actor | Guest Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer is not logged in. 2. Customer navigates to `/c/register`. |
| | 1.1 | System renders the registration form with fields: Name, Email, Password, Confirm Password. |
| | 1.2 | Customer fills in all required fields and clicks "Register". |
| | 1.3 | System validates input: required fields, valid email format, password minimum 8 characters, passwords match. |
| | 1.4 | System hashes the password and creates a new User record in the database with `role = customer`. |
| | 1.5 | System automatically logs the customer in and creates an auth session. |
| | 1.6 | System displays a success message and redirects the customer to the menu page. |
| | Post-Condition | A new customer account is created and saved in the database. The customer is authenticated and on the menu page. |
| Alternate Flow | — | Customer already has an account → directed to the `/c/login` page. |
| Robust Flow | Pre-Condition | Customer provides invalid or incomplete details in the registration form. |
| | 2.1 | System detects validation errors (e.g., email already registered, passwords do not match, missing fields). |
| | 2.2 | System highlights the problematic fields and displays inline error messages (e.g., "Email has already been taken."). |
| | 2.3 | Customer corrects the errors and resubmits the form. |

---

| Use Case ID | UC_009 |
|---|---|
| Use Case Name | Login (Customer) |
| Description | A registered customer logs into their account via the customer login page at `/c/login` to access authenticated features such as loyalty points and reward redemption. |
| Primary Actor | Authenticated Customer |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer has a registered account. 2. Customer is not currently logged in. 3. Customer navigates to `/c/login`. |
| | 1.1 | System renders the login form with Email and Password fields. |
| | 1.2 | Customer enters their email and password and clicks "Login". |
| | 1.3 | System validates the credentials against the database. |
| | 1.4 | Credentials match: system creates an authenticated session for the customer. |
| | 1.5 | System redirects the customer to the menu page. |
| | Post-Condition | Customer is authenticated. Session is active. Customer can access loyalty points, rewards, and profile features. |
| Alternate Flow | — | Customer tries to access a protected route while not logged in → redirected to `/c/login` with the intended URL preserved. |
| Robust Flow | Pre-Condition | Customer enters incorrect credentials or the account does not exist. |
| | 2.1 | System checks credentials and finds no match in the database. |
| | 2.2 | System displays: "These credentials do not match our records." (same message for both wrong password and unregistered email, to prevent user enumeration). |
| | 2.3 | After repeated failed attempts, Laravel throttle middleware temporarily locks the login form and displays a cooldown message. |

---

| Use Case ID | UC_010 |
|---|---|
| Use Case Name | Earn Loyalty Points |
| Description | After an authenticated customer's order is marked as Paid by staff, the system automatically calculates and credits loyalty points to the customer's account based on their order total. |
| Primary Actor | Authenticated Customer |
| Include use cases | UC_005 (Place Order) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Customer is authenticated and their user ID is linked to the order. 2. Staff has marked the order as Paid (UC_017). |
| | 1.1 | Staff clicks "Mark as Paid" on a served order. |
| | 1.2 | System confirms the order belongs to an authenticated customer. |
| | 1.3 | System calculates loyalty points based on the order total (e.g., 1 point per RM1 spent). |
| | 1.4 | System updates the customer's `points` balance in the users table. |
| | 1.5 | Customer views their profile at `/my-profile` and sees the updated points balance. |
| | Post-Condition | Customer's loyalty points balance is incremented. Points are visible on the customer's profile page. |
| Alternate Flow | — | Guest customer (unauthenticated) places the order → no user ID linked; points step is skipped entirely. |
| Robust Flow | Pre-Condition | The database update for points fails after the order is marked paid. |
| | 2.1 | System successfully marks order as Paid but the points update query fails. |
| | 2.2 | System logs the failure with the order reference and user ID for manual correction. |
| | 2.3 | Order remains marked as Paid; an admin can manually adjust points via the admin panel. |
