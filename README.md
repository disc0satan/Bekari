# 🥐 Bekari – Bakery Management System

## 📌 Introduction
The **Bakery Management System (BMS)** is a robust relational database–driven solution designed to streamline internal bakery operations with a strong focus on **product freshness**, inventory accuracy, and sales integrity.


Unlike a standard retail website, this system centralizes "back-of-house" processes—from recipe formulation and batch production tracking to multi-branch inventory transfers and customer sales management.

### Key System Logic:
* ❌ **Automatic Expiry Filtering:** Expired products are automatically excluded from sales.
* 📦 **Dynamic Sync:** Inventory updates in real-time across all branches.
* 📊 **Traceability:** Production and sales data remain accurate and auditable.
<img width="311" height="194" alt="Screenshot 2026-02-19 at 3 36 09 AM" src="https://github.com/user-attachments/assets/b93ca034-08b7-4885-955e-887eced9b74e" />
---

## 🚀 Core Features

### 🛒 Sales & Customer Relationship Management
* **Dynamic Ordering:** Supports multi-item orders while validating branch-specific availability and filtering out expired stock.
* **Smart Discounting:** Applies automatic discounts to products nearing expiration to reduce waste.
* **Loyalty & CRM:** Tracks customer purchase history and calculates loyalty points; prevents duplicate profiles via phone validation.
* **Professional Invoicing:** Generates printable, searchable invoice records with a clean layout.
<img width="365" height="239" alt="Screenshot 2026-02-19 at 3 34 50 AM" src="https://github.com/user-attachments/assets/a7b2eb42-ac6c-4a9a-9ce4-171a7e9f6e4b" />

### 🍞 Production & Inventory Logistics
* **Batch Tracking:** Logs every production batch with timestamps and automated expiration monitoring.
* **Recipe & Waste Management:** Centralized database for ingredient costs, recipes, and production waste analysis.
* **Branch Transfers:** Move specific batches between locations with role-based notifications and accurate stock updates.<img width="542" height="357" alt="Screenshot 2026-02-19 at 3 33 48 AM" src="https://github.com/user-attachments/assets/4b5d538c-dffb-4f32-8764-c1ac3878ce04" />


### 🔔 System Notifications
* **Real-time Alerts:** Low stock, expiring products, and incoming batch transfer warnings.
* **Role-Specific Filtering:** Users only see alerts relevant to their specific permissions and branch.
<img width="496" height="292" alt="Screenshot 2026-02-19 at 3 31 45 AM" src="https://github.com/user-attachments/assets/51a4835b-3354-41c4-94d2-3be08d8b7671" />

### 🔐 Administration & Business Intelligence
* **Role-Based Access Control (RBAC):** Distinct permissions for Admin, Branch Manager, Hub Worker, and Staff.
* **Advanced Analytics:** Monthly sales reports, production summaries, and top-selling product analysis.
<img width="402" height="292" alt="Screenshot 2026-02-19 at 3 31 10 AM" src="https://github.com/user-attachments/assets/25c637e6-2490-448d-b794-c9ec893d7ed7" />
<img width="433" height="292" alt="Screenshot 2026-02-19 at 3 31 20 AM" src="https://github.com/user-attachments/assets/6ed2f892-c40c-4b5e-b1be-b18c5a46498c" />

---

## 🎨 UI/UX Design
The system is built for **high-efficiency environments**. The interface prioritizes speed and clarity through:
* Clean, minimal dashboard layouts tailored to specific user roles.
* Reduced-click workflows for complex actions like batch transfers.
* Clear visual hierarchy for stock levels and freshness indicators.

---

## 🛠 Tech Stack
* **Backend:** PHP
* **Database:** MySQL (Relational)
* **Local Server:** XAMPP / Apache
* **Frontend:** HTML, CSS, JavaScript

---

## ⚙ Setup Instructions

### 1. Database Setup
1. Open **phpMyAdmin** in XAMPP.
2. Create a new database (e.g., `bekari`).
3. **Import** the provided `.sql` file found in the project folder.

### 2. Configuration
Open your database connection file (e.g., `db_connect.php`) and update your credentials:
```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bekari";

$conn = mysqli_connect($servername, $username, $password, $dbname);
?>
