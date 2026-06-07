
---

| Use Case ID | UC_021 |
|---|---|
| Use Case Name | View Admin Dashboard |
| Description | The admin logs in and accesses the main admin dashboard at `/admin/dashboard`, which displays key business metrics including total orders, total revenue, number of products, and registered users. |
| Primary Actor | Admin |
| Include use cases | Login — Staff/Admin via Laravel Auth |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. User is authenticated with `role = admin`. 2. Admin navigates to `/admin/dashboard`. |
| | 1.1 | The `auth` and `admin` middleware validate the user's authentication and role. |
| | 1.2 | System queries the database for key metrics: total orders, total revenue, product count, user count. |
| | 1.3 | System renders the admin dashboard with summary cards and navigation sidebar. |
| | 1.4 | Admin sees quick-access links to: Categories, Products, Rewards, Reviews, Analytics, and QR Management. |
| | Post-Condition | Admin dashboard is displayed with up-to-date business metrics. Admin can navigate to all management sections. |
| Alternate Flow | — | Non-admin authenticated user attempts access → `admin` middleware rejects and redirects them to their role's home page. |
| Robust Flow | Pre-Condition | Admin is unauthenticated, or the database metrics query fails. |
| | 2.1 | Unauthenticated access: system redirects to `/login`. |
| | 2.2 | DB metrics query fails: dashboard loads with error widgets displaying "Data unavailable." |
| | 2.3 | System logs the exception for developer review; admin can refresh to retry. |

---

| Use Case ID | UC_022 |
|---|---|
| Use Case Name | Manage Categories (Admin CRUD) |
| Description | The admin creates, reads, updates, and deletes product categories via the admin panel. Category changes are immediately reflected on the customer-facing menu. |
| Primary Actor | Admin |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated with `role = admin`. 2. Admin navigates to `/admin/categories`. |
| | 1.1 | System fetches all categories from the database and renders them in a management table. |
| | 1.2 | **Create:** Admin clicks "New Category", fills in the name, and submits → POST to `/admin/categories`. |
| | 1.3 | System validates the input (name required, unique) and saves the new category to the database. |
| | 1.4 | **Edit:** Admin clicks "Edit" on an existing category, updates the name → PATCH to `/admin/categories/{id}`. |
| | 1.5 | System validates and updates the category record in the database. |
| | 1.6 | **Delete:** Admin clicks "Delete" → DELETE to `/admin/categories/{id}`. |
| | 1.7 | System removes the category from the database. |
| | Post-Condition | Category changes are persisted to the database and immediately visible on the customer menu page. |
| Alternate Flow | — | Admin attempts to delete a category that has products assigned → system warns: "This category has products. Reassign or delete products first." |
| Robust Flow | Pre-Condition | Admin submits duplicate or empty category name. |
| | 2.1 | Empty name: system displays validation error "Category name is required." |
| | 2.2 | Duplicate name: system displays validation error "A category with this name already exists." |
| | 2.3 | Admin corrects the input and resubmits the form. |

---

| Use Case ID | UC_023 |
|---|---|
| Use Case Name | Manage Products (Admin CRUD) |
| Description | The admin creates, reads, updates, and deletes menu products. Each product has a name, description, price, image, and belongs to a category. |
| Primary Actor | Admin |
| Include use cases | UC_022 (Manage Categories — at least one category must exist) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated. 2. At least one category exists. 3. Admin navigates to `/admin/products`. |
| | 1.1 | System fetches all products with their categories and renders them in a management table. |
| | 1.2 | **Create:** Admin clicks "New Product", fills in name, description, price, selects category, uploads image → POST to `/admin/products`. |
| | 1.3 | System validates fields (name required, price positive, image valid format/size) and saves the product. |
| | 1.4 | **Edit:** Admin clicks "Edit", updates any field → PATCH to `/admin/products/{id}`. System saves changes. |
| | 1.5 | **Delete:** Admin clicks "Delete" → DELETE to `/admin/products/{id}`. Product removed from DB. |
| | Post-Condition | Product changes are saved. New/updated products are immediately visible on the customer menu. |
| Alternate Flow | — | Admin uploads a new image during edit → old image is replaced; new image is stored in `/storage/app/public`. |
| Robust Flow | Pre-Condition | Admin submits invalid price, unsupported image format, or image exceeding size limit. |
| | 2.1 | Negative or zero price: system displays "Price must be a positive number." |
| | 2.2 | Invalid image format (e.g., `.exe`, `.pdf`): system displays "Only JPG, PNG, and WEBP images are allowed." |
| | 2.3 | Image too large (> 2MB): system displays "Image file must be under 2MB." |

---

| Use Case ID | UC_024 |
|---|---|
| Use Case Name | Manage Rewards Catalog (Admin) |
| Description | The admin creates, updates, and deletes reward items in the loyalty rewards catalogue, setting the loyalty point cost required to redeem each item. |
| Primary Actor | Admin |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated. 2. Admin navigates to `/admin/rewards`. |
| | 1.1 | System fetches all reward items and renders them in a management table. |
| | 1.2 | **Create:** Admin clicks "New Reward", fills in name, description, and point cost → POST to `/admin/rewards`. |
| | 1.3 | System validates the fields (name required, points must be a positive integer) and saves the reward. |
| | 1.4 | **Edit:** Admin updates the name or point cost → PATCH to `/admin/rewards/{id}`. System saves changes. |
| | 1.5 | **Delete:** Admin removes an expired or unavailable reward → DELETE to `/admin/rewards/{id}`. |
| | Post-Condition | Reward catalog is updated. Changes are immediately visible to customers on the `/rewards` page. |
| Alternate Flow | — | Admin sets point cost to 0 → system rejects with "Points cost must be at least 1." |
| Robust Flow | Pre-Condition | Admin enters a negative point cost or attempts to delete a reward that has been redeemed. |
| | 2.1 | Negative point cost: system displays validation error "Points must be a positive integer." |
| | 2.2 | Delete redeemed reward: system prevents deletion due to referential integrity and displays "This reward has existing redemptions and cannot be deleted." |
| | 2.3 | Admin deactivates the reward instead (if soft-delete is supported) as an alternative to deletion. |

---

| Use Case ID | UC_025 |
|---|---|
| Use Case Name | Moderate Customer Reviews (Admin) |
| Description | The admin views all customer reviews across all products and can delete any review that is inappropriate, offensive, or spam. Deleted reviews are permanently removed from the system. |
| Primary Actor | Admin |
| Include use cases | None |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated. 2. At least one customer review exists. 3. Admin navigates to `/admin/reviews`. |
| | 1.1 | System fetches all reviews from the database (all products, all customers). |
| | 1.2 | System renders the review moderation table with: product name, customer name, star rating, review text, and date. |
| | 1.3 | Admin reads a review and identifies it as inappropriate or spam. |
| | 1.4 | Admin clicks "Delete" on the review → DELETE to `/admin/reviews/{product}/{code}`. |
| | 1.5 | System removes the review record from the database. |
| | 1.6 | The moderation table refreshes; the deleted review is no longer shown. |
| | Post-Condition | The review is permanently deleted from the database and no longer visible on the product menu page. |
| Alternate Flow | — | Admin reads the review and finds it acceptable → no action is taken; review remains published. |
| Robust Flow | Pre-Condition | The review was already deleted by another session (stale page), or the database delete fails. |
| | 2.1 | Review not found (already deleted): system returns HTTP 404. |
| | 2.2 | Admin refreshes the moderation page to see the current state. |
| | 2.3 | Database error during deletion: system displays an error message and advises the admin to retry. |

---

| Use Case ID | UC_026 |
|---|---|
| Use Case Name | View Analytics (Admin) |
| Description | The admin views a detailed analytics dashboard at `/admin/analytics` showing total revenue, order volume by status, top-selling products, and sales trends over time. |
| Primary Actor | Admin |
| Include use cases | UC_027 (Export Reports — extends) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated with `role = admin`. 2. Paid order data exists in the system. 3. Admin navigates to `/admin/analytics`. |
| | 1.1 | System queries the database to aggregate: total revenue, total orders, orders by status, and top 5 selling products. |
| | 1.2 | System renders the analytics dashboard with summary cards and data visualisations (charts/tables). |
| | 1.3 | Admin selects a date range filter and submits. |
| | 1.4 | System re-queries the database for the specified period and refreshes the dashboard. |
| | Post-Condition | Analytics dashboard displays accurate, up-to-date business data for the selected period. Admin can export the data. |
| Alternate Flow | — | No sales data exists for the selected period → all metrics show zero values with a note "No data available." |
| Robust Flow | Pre-Condition | The aggregation query is complex and times out, or the chart library fails to load. |
| | 2.1 | Query timeout: system serves cached analytics data and displays "Showing cached results. Live data may differ." |
| | 2.2 | Chart library fails to load: system falls back to a plain HTML table view of the analytics data. |
| | 2.3 | Admin narrows the date range to reduce query complexity and retries. |

---

| Use Case ID | UC_027 |
|---|---|
| Use Case Name | Export Reports (Admin) |
| Description | The admin exports sales and analytics data in CSV or PDF format from the analytics page for offline analysis, printing, or sharing with stakeholders. |
| Primary Actor | Admin |
| Include use cases | UC_026 (View Analytics) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated. 2. The analytics page is loaded. 3. Admin selects the desired export format (CSV or PDF). |
| | 1.1 | Admin clicks "Export CSV" or "Export PDF" button on the analytics page. |
| | 1.2 | A GET request is sent to `/admin/analytics/export` with the format parameter (and optional date range). |
| | 1.3 | System fetches the relevant aggregated order data from the database. |
| | 1.4 | For CSV: system formats the data into comma-separated values and streams it as a `.csv` file download. |
| | 1.5 | For PDF: `laravel-dompdf` renders the analytics data into a formatted PDF and streams it as a download. |
| | 1.6 | Browser downloads the generated file. |
| | Post-Condition | A report file (CSV or PDF) is downloaded to the admin's device containing all relevant sales and analytics data. |
| Alternate Flow | — | Admin applies a date filter on the analytics page before exporting → the exported file contains only data from the selected period. |
| Robust Flow | Pre-Condition | There is no data to export, PDF rendering fails, or the dataset is excessively large. |
| | 2.1 | No data available: system generates an empty file with column headers and a note "No data found for this period." |
| | 2.2 | PDF rendering failure: system returns HTTP 500 and advises admin to use CSV export as an alternative. |
| | 2.3 | Very large dataset: system applies a row limit or pagination to the export to prevent memory exhaustion. |

---

| Use Case ID | UC_028 |
|---|---|
| Use Case Name | Manage QR Table Codes (Admin) |
| Description | The admin generates and manages unique QR codes for each restaurant table. Each QR code encodes a unique URL (`/menu?table={id}`) that redirects customers to the menu with their table pre-selected. |
| Primary Actor | Admin |
| Include use cases | UC_001 (Scan QR Code — customer-facing result) |

| Scenario | Step | Action |
|---|---|---|
| Main Flow | Pre-Condition | 1. Admin is authenticated. 2. Admin navigates to the QR Table Management section of the admin panel. 3. A QR code generation library is installed and configured. |
| | 1.1 | Admin enters the number of tables or selects a specific table number to generate a QR code for. |
| | 1.2 | System constructs the unique URL for each table: `/menu?table={id}`. |
| | 1.3 | System uses the QR code library to encode the URL into a QR code image. |
| | 1.4 | System displays all generated QR code images on the page with their table labels. |
| | 1.5 | Admin downloads or prints the QR code images for physical placement on the tables. |
| | Post-Condition | QR codes are generated and available for download/print. Each QR code correctly links to its corresponding table on the menu. |
| Alternate Flow | — | Admin regenerates a QR code for one specific table (e.g., the physical code is damaged) → system generates a new QR for that single table only; other tables are unaffected. |
| Robust Flow | Pre-Condition | The QR code generation library is not installed, or an invalid table number is submitted. |
| | 2.1 | Library not installed: system displays "QR Code feature is currently unavailable. Please contact the system administrator." (Pending — Appendix C, Issue #3 of SRS). |
| | 2.2 | Invalid table number (0, negative, or non-numeric): system displays validation error "Please enter a valid table number." |
| | 2.3 | Admin installs the required QR library (e.g., `simplesoftwareio/simple-qrcode`) via Composer and retries. |

---

*End of Test Cases Document*
*Total Use Cases Documented: 28*
*Actors: Guest Customer · Authenticated Customer · Staff · Admin*
