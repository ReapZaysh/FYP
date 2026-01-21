# Software Requirements Specification
## for bossku-house
### Version 1.0

---

## 1. Introduction

### 1.1 Purpose
The purpose of this document is to specify the software requirements for **bossku-house**, a QR-based digital food ordering system. This SRS covers the core ordering system, including the customer menu, order management for staff, and administrative analytics, as well as the newly proposed QR table redirection feature.

### 1.2 Document Conventions
This document follows standard software engineering documentation practices. 
- **Font**: Standard proportional font.
- **Priority**: Requirements are assigned priorities (High, Medium, Low).
- **ID**: Functional requirements are uniquely identified as `REQ-X`.

### 1.3 Intended Audience and Reading Suggestions
This document is intended for:
- **Developers**: For implementation details and technical constraints.
- **Project Managers**: For scope and milestone planning.
- **Testers**: For creating test cases based on functional requirements.
- **Stakeholders (Restaurant Owners)**: To understand the product features and benefits.

The suggested reading sequence is to start with the **Introduction (Section 1)** and **Overall Description (Section 2)** to get a high-level view, then proceed to **System Features (Section 3)** for detailed functional requirements.

### 1.4 Project Scope
**bossku-house** aims to modernize the dining experience by allowing customers to order food digitally via their mobile devices. By scanning a QR code on their table, customers are redirected to the menu with their table number pre-filled.
- **Objectives**: Reduce wait times, improve order accuracy, and provide real-time sales analytics.
- **Benefits**: Streamlined operations for staff and a more convenient experience for customers.

### 1.5 References
- Laravel Framework Documentation: [https://laravel.com/docs](https://laravel.com/docs)
- Firebase Documentation: [https://firebase.google.com/docs](https://firebase.google.com/docs)

---

## 2. Overall Description

### 2.1 Product Perspective
**bossku-house** is a new, self-contained web-based application designed to replace traditional paper-based ordering systems. It integrates with Firebase for real-time status updates and utilizes the Laravel framework for robust backend management.

### 2.2 Product Features
The system includes:
- **Customer Menu**: Browsing products by category.
- **QR Table Scanning**: Automatic table identification via QR code.
- **Cart Management**: Real-time cart updates.
- **Order Tracking**: Real-time order status tracking for customers.
- **Order Management**: Staff dashboard for processing and updating orders.
- **Analytics**: Admin dashboard for sales and performance monitoring.
- **Category & Product Management**: Admin tools for managing the menu.

### 2.3 User Classes and Characteristics
- **Customers**: Unauthenticated users who scan QR codes to browse and order.
- **Staff**: Authenticated users responsible for preparing orders and updating their status.
- **Admins**: Authenticated users with full control over the system, including menu management and analytics.

### 2.4 Operating Environment
- **Web Server**: Apache/Nginx with PHP 8.2+.
- **Database**: MySQL and Firebase.
- **Client**: Any modern web browser (Chrome, Safari, Firefox).

### 2.5 Design and Implementation Constraints
- **Framework**: Must use Laravel 12.
- **Security**: Must implement Role-Based Access Control (RBAC).
- **Responsiveness**: Must be mobile-friendly for customer use.
- **Connectivity**: Requires active internet connection for real-time Firebase syncing.

### 2.6 User Documentation
- **Administrator Guide**: Instructions for menu management and analytics.
- **Staff User Manual**: Quick guide for order processing.
- **Customer FAQ**: On-screen instructions for ordering.

### 2.7 Assumptions and Dependencies
- **Assumption**: Every table has a unique QR code.
- **Dependency**: Reliability of Firebase for real-time notifications.
- **Dependency**: Availability of high-speed internet in the restaurant.

---

## 3. System Features

### 3.1 QR Table Redirection
#### 3.1.1 Description and Priority
This feature allows customers to scan a QR code placed on their table to be instantly redirected to the restaurant menu. The system automatically detects and preserves the table number to ensure orders are tied to the correct location.
**Priority**: High

#### 3.1.2 Stimulus/Response Sequences
- **Stimulus**: Customer scans a table-specific QR code using their mobile device.
- **Response**: The system opens the browser to the menu URL with the table ID as a parameter.
- **Stimulus**: The customer selects items for their order.
- **Response**: The cart stores the items alongside the pre-identified table number.

#### 3.1.3 Functional Requirements
- **REQ-1**: The system shall support unique URLs for each table QR code (e.g., `/menu?table=5`).
- **REQ-2**: The system shall store the table number in the customer's session or local storage upon redirection.
- **REQ-3**: The table number must be automatically attached to any order initiated from that session.

### 3.2 Menu Browsing and Ordering
#### 3.2.1 Description and Priority
Customers can browse the menu by categories (e.g., Drinks, Main Course) and add items to a virtual cart.
**Priority**: High

#### 3.2.2 Stimulus/Response Sequences
- **Stimulus**: Customer clicks on a category name.
- **Response**: The system filters and displays only products belonging to that category.
- **Stimulus**: Customer clicks "Add to Cart" on a product.
- **Response**: The system adds the item to the cart and updates the total price in real-time.

#### 3.2.3 Functional Requirements
- **REQ-4**: The menu shall display product names, descriptions, prices, and images.
- **REQ-5**: The system shall allow customers to increase or decrease item quantities in the cart.
- **REQ-6**: The system shall generate a unique reference number for every successfully placed order.

### 3.3 Staff Order Processing
#### 3.3.1 Description and Priority
Staff members can view incoming orders in real-time and update their status (e.g., Preparing, Served, Cancelled).
**Priority**: High

#### 3.3.2 Functional Requirements
- **REQ-7**: The system shall provide a dashboard that updates automatically when a new order is placed (via Firebase).
- **REQ-8**: Staff shall be able to change order status to "Completed" once the food is served.

### 3.4 Administrative Management
#### 3.4.1 Description and Priority
Admins can manage the menu items and view business analytics.
**Priority**: Medium

#### 3.4.2 Functional Requirements
- **REQ-9**: Admins shall be able to create, read, update, and delete (CRUD) product categories.
- **REQ-10**: Admins shall be able to CRUD products, including uploading images and setting prices.
- **REQ-11**: The system shall provide a summary of total sales and popular items over a selected time period.

---

## 4. External Interface Requirements

### 4.1 User Interfaces
- **Customer UI**: A mobile-responsive web interface featuring a menu grid, a persistent shopping cart icon, and a simple checkout form.
- **Admin/Staff UI**: A comprehensive dashboard built with Tailwind CSS, featuring sidebar navigation and data tables for management.

### 4.2 Hardware Interfaces
- **Mobile Devices**: The system must be accessible via smartphones and tablets for customer use (scanning QR codes).
- **Desktop/Laptops**: For admin and staff access to management dashboards.

### 4.3 Software Interfaces
- **Database**: MySQL for structured data (users, products, orders).
- **Real-time Sync**: Kreait Laravel Firebase for real-time order status updates.
- **Authentication**: Laravel Breeze (Breeze/Sanctum) for role-based authentication.

### 4.4 Communications Interfaces
- **HTTP/HTTPS**: Communication between the client browser and the Laravel server.
- **WebSocket/Polling**: Used by Firebase for real-time data push notifications to the staff dashboard.

---

## 5. Other Nonfunctional Requirements

### 5.1 Performance Requirements
- **Load Time**: The menu page should load in under 2 seconds on a standard 4G connection.
- **Real-time Updates**: Order status changes should reflect on the customer's tracking page within 1 second of being updated by staff.

### 5.2 Safety Requirements
- **Data Backup**: Periodic database backups should be performed to prevent data loss.

### 5.3 Security Requirements
- **Authentication**: All admin and staff routes must be protected by authentication middleware.
- **Authorization**: Role-based access control (RBAC) must ensure staff cannot access admin analytics and vice versa.
- **Data Privacy**: Customer contact information (if collected) must be handled according to local data protection regulations.

### 5.4 Software Quality Attributes
- **Usability**: The QR scanning to ordering flow must be intuitive enough for first-time users without instructions.
- **Maintainability**: The codebase should follow Laravel's standard MVC architecture and PSR-12 coding standards.
- **Availability**: The system should aim for 99.9% uptime during restaurant operating hours.

---

## 6. Other Requirements

### 6.1 Database Requirements
- The database must support relationship integrity between products, categories, and orders.
- Optimized indexing on the `reference` column for fast order tracking.

---

## Appendix A: Glossary
- **SRS**: Software Requirements Specification.
- **QR Code**: Quick Response Code.
- **CRUD**: Create, Read, Update, Delete.
- **RBAC**: Role-Based Access Control.
- **FYP**: Final Year Project.

## Appendix B: Analysis Models
- **Data Flow**: QR Scan -> Menu Redirection (w/ Table ID) -> Selection -> Cart -> Order Database -> Staff Dashboard -> Firebase Status Update.

## Appendix C: Issues List
1. **TBD**: Specific QR code generation library to be used.
2. **Pending**: Integration of a payment gateway (currently assumes pay-at-counter).
3. **Information Needed**: Maximum number of concurrent users supported by the selected server hardware.
