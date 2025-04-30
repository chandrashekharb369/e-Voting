# 🗳️ Online Voting System

[![PHP](https://img.shields.io/badge/Built%20with-PHP-blue?style=for-the-badge&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-lightblue?style=for-the-badge&logo=mysql)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/UI-Bootstrap-purple?style=for-the-badge&logo=bootstrap)](https://getbootstrap.com/)
[![Status](https://img.shields.io/badge/Project%20Status-Active-brightgreen?style=for-the-badge)]

> A secure and efficient **online voting platform** built with PHP and MySQL. Supports voter registration, candidate management, and real-time result display.

---

## 🔑 Key Features

✅ Secure Voter Registration with Aadhar verification  
✅ Candidate Management Panel (Add, Edit, Delete)  
✅ Admin Authentication & Dashboard  
✅ Cast Vote Interface (1 vote per user)  
✅ Live Vote Counting & Result Visualization  
✅ Voter List with Aadhar photo & Zoom  
✅ Auto-generated 9-digit Application ID  
✅ Age Validation (only 18+ can apply)

---

## ⚙️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap  
- **Backend**: PHP  
- **Database**: MySQL  
- **Server**: XAMPP (Apache + MySQL)  
- **Other Tools**: jQuery, AJAX (optional)


---

## 🛠️ How to Run Locally

### Step 1: Setup XAMPP

- Install [XAMPP](https://www.apachefriends.org/)
- Move the project folder to:  
  `/opt/lampp/htdocs/vote/` (Linux)  
  or `C:/xampp/htdocs/vote/` (Windows)

### Step 2: Import Database

- Open `phpMyAdmin`
- Create a database, e.g., `voting_system`
- Import the SQL file: `voting_system.sql`

### Step 3: Start Server

```bash
sudo /opt/lampp/lampp start  # (Linux)
# OR
Start Apache and MySQL in XAMPP Control Panel (Windows)
