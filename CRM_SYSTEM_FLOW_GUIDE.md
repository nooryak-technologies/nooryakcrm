# 📘 Complete CRM System Flow Guide

## 🎯 Understanding Your CRM Setup

You have a **Multi-Tenant SaaS CRM System** powered by Perfex CRM with the SaaS module. This means:
- **One main system** hosts multiple separate company instances
- Each company gets their own isolated CRM space
- Your setup has **2 types of users** with different access levels

---

## 👥 Two Types of Users (IMPORTANT!)

### 1. **STAFF/ADMIN Users** 👨‍💼
- **Who:** Your company employees, managers, sales team
- **Access:** Full CRM backend - manage everything
- **Login URL:** `https://vezan.nooryakcrm.com/admin`
- **What they can do:**
  - Manage leads, customers, invoices
  - View reports and analytics
  - Create projects, tasks, proposals
  - Manage system settings
  - Add other staff members

### 2. **CLIENT/CUSTOMER Users** 👤
- **Who:** Your customers (the people you sell to)
- **Access:** Limited frontend portal - view their own data only
- **Login URL:** `https://vezan.nooryakcrm.com/clients`
- **What they can do:**
  - View their invoices and estimates
  - Track their projects
  - Submit support tickets
  - Upload files
  - View proposals sent to them

---

## 🔐 Your Current Situation (Based on Screenshots)

### What Happened:
```
You received: Email with login credentials
           ↓
You tried: Login at ADMIN panel (/admin)
           ↓
Result: "Invalid email or password" ❌
           ↓
Then tried: Same credentials at CLIENT portal (/clients)
           ↓
Result: Successfully logged in as CLIENT ✅
```

### Why This Happened:
**Your credentials (`wezan.bahad@gmail.com` / `bahad@123`) are CLIENT credentials, NOT admin credentials.**

You're trying to access the **kitchen** (admin panel) but you have a key to the **dining room** (client portal).

---

## 🏢 Complete System Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                     NOORYAK CRM MAIN SYSTEM                         │
│                  (Main Server: nooryakcrm.com)                      │
└─────────────────────────────────────────────────────────────────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    │    PERFEX SAAS MODULE      │
                    │  (Creates Sub-companies)   │
                    └─────────────┬─────────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    │                           │
          ┌─────────▼────────┐       ┌─────────▼────────┐
          │  Company: VEZAN  │       │ Company: Other   │
          │  (Your Instance) │       │  (Other clients) │
          └─────────┬────────┘       └──────────────────┘
                    │
        ┌───────────┴───────────┐
        │                       │
   ┌────▼─────┐          ┌─────▼────┐
   │  ADMIN   │          │  CLIENT  │
   │  PORTAL  │          │  PORTAL  │
   └──────────┘          └──────────┘
```

---

## 🔄 Complete CRM Workflow (Step by Step)

### **PHASE 1: System Setup** 🛠️

```
Step 1: Super Admin Creates Company Instance
    ↓
Super Admin (Nooryak Technologies) creates "vezan" company
    ↓
Email sent: "Company CRM Instance created successfully"
    ↓
Contains: Admin panel URL + initial admin credentials
```

### **PHASE 2: Admin Access** 👨‍💼

```
Step 2: Admin Logs In
    ↓
Admin goes to: https://vezan.nooryakcrm.com/admin
    ↓
Enters: Admin email + password (NOT client credentials)
    ↓
Access: Full CRM Dashboard
```

**What Admin Sees:**
```
┌─────────────────────────────────────────────┐
│          ADMIN DASHBOARD                     │
├─────────────────────────────────────────────┤
│ → Dashboard (Analytics, Charts)              │
│ → Customers (All your clients)              │
│ → Leads (Potential customers)               │
│ → Sales (Invoices, Estimates, Proposals)    │
│ → Projects (Client projects)                │
│ → Tasks (Team tasks)                        │
│ → Contracts                                 │
│ → Support (Tickets)                         │
│ → Reports (Financial reports)               │
│ → Setup (System settings)                   │
└─────────────────────────────────────────────┘
```

### **PHASE 3: Lead Management** 🎯

```
Step 3: Admin Adds a Lead
    ↓
Leads → New Lead
    ↓
Fill: Name, Company, Email, Phone, Value
    ↓
Assign: To sales person
    ↓
Status: Customer, Contacted, Qualified, etc.
    ↓
Lead appears in KANBAN BOARD
```

**Lead Journey:**
```
New Lead → Contacted → Qualified → Proposal Sent → Won/Lost
```

### **PHASE 4: Convert Lead to Customer** 🎉

```
Step 4: Lead Becomes Customer
    ↓
Lead → Convert to Customer
    ↓
System creates CLIENT ACCOUNT
    ↓
Email sent to client with login credentials
    ↓
Client can now login at: /clients portal
```

### **PHASE 5: Client Access** 👤

```
Step 5: Client Logs In
    ↓
Client goes to: https://vezan.nooryakcrm.com/clients
    ↓
Enters: Their email + password
    ↓
Access: Client Portal (Limited View)
```

**What Client Sees:**
```
┌─────────────────────────────────────────────┐
│          CLIENT PORTAL                       │
├─────────────────────────────────────────────┤
│ → Invoices (Their invoices only)            │
│ → Estimates (Quotes sent to them)           │
│ → Projects (Their projects)                 │
│ → Proposals (Proposals they received)       │
│ → Contracts (Their contracts)               │
│ → Support (Submit tickets)                  │
│ → Files (Shared documents)                  │
│ → Billing (Subscription/package info)       │
└─────────────────────────────────────────────┘
```

### **PHASE 6: Sales Process** 💰

```
Step 6: Admin Creates Invoice/Estimate
    ↓
Sales → Invoices → New Invoice
    ↓
Select: Customer
    ↓
Add: Items, prices (with ₹ symbol)
    ↓
Send to Client
    ↓
Client receives email notification
    ↓
Client can view/pay invoice in their portal
```

### **PHASE 7: Project Management** 📊

```
Step 7: Admin Creates Project
    ↓
Projects → New Project
    ↓
Assign: To customer + team members
    ↓
Add: Tasks, milestones, files
    ↓
Track: Progress, time, expenses
    ↓
Client can view progress in their portal
```

---

## 🔑 Finding Your Admin Credentials

### Method 1: Check Email (Recommended)
Look for these email subjects:
- ✉️ "Staff Member Created"
- ✉️ "Welcome to [Company] CRM"
- ✉️ "Your Admin Account Details"
- ✉️ "Company CRM Instance created successfully" (might contain admin info)

**Search Gmail:**
1. Search: `from:noreply@nooryakcrm.com staff`
2. Search: `from:noreply@nooryakcrm.com admin`
3. Search: `subject:staff created`

### Method 2: Use Reset Tool
1. Open: `http://localhost/crm/reset_admin_password.php`
2. Find your staff account in the table
3. Click "Reset Password"
4. Set new password
5. Login with new password
6. **DELETE the file after use!**

### Method 3: Check Database
Run this query in phpMyAdmin:
```sql
SELECT staffid, firstname, lastname, email, admin, active 
FROM tbl_staff 
WHERE admin = 1 AND active = 1;
```

### Method 4: Contact System Administrator
Contact Nooryak Technologies support to retrieve your admin credentials.

---

## 📋 Common Scenarios Explained

### Scenario 1: I want to manage my business
**You need:** ADMIN/STAFF account  
**Login at:** `/admin`  
**Use case:** Manage leads, create invoices, view reports

### Scenario 2: I want to see my invoices as a customer
**You need:** CLIENT account  
**Login at:** `/clients`  
**Use case:** View and pay your bills, track projects

### Scenario 3: I'm a sales person
**You need:** STAFF account (not admin)  
**Login at:** `/admin`  
**Use case:** Manage assigned leads, create proposals

### Scenario 4: I want to add team members
**You need:** ADMIN account  
**Login at:** `/admin` → Setup → Staff  
**Action:** Add new staff members with roles

---

## 🎬 Real-World Example Flow

Let's say you run a web design company:

```
1. YOU (Admin) login at /admin
   ↓
2. Add a LEAD: "John's Bakery - needs website"
   ↓
3. Contact John, move lead through sales pipeline
   ↓
4. John agrees! Convert lead to CUSTOMER
   ↓
5. System creates CLIENT account for John
   ↓
6. John receives email: "Welcome! Login at /clients"
   ↓
7. YOU create ESTIMATE: "Website Design - ₹50,000"
   ↓
8. Send estimate to John
   ↓
9. JOHN logs in at /clients portal
   ↓
10. John sees estimate, approves it
   ↓
11. YOU convert estimate to INVOICE
   ↓
12. John pays invoice through portal
   ↓
13. YOU create PROJECT: "John's Bakery Website"
   ↓
14. Add TASKS, assign to developers
   ↓
15. JOHN tracks progress in /clients portal
   ↓
16. Project completed, John is happy! 🎉
```

---

## 🚨 Key Differences: Admin vs Client

| Feature | Admin Portal (/admin) | Client Portal (/clients) |
|---------|----------------------|-------------------------|
| **Purpose** | Manage business | View own data |
| **Users** | Staff, managers | Customers |
| **Access Level** | Full system | Own records only |
| **Can Add Leads** | ✅ Yes | ❌ No |
| **Can Create Invoices** | ✅ Yes | ❌ No |
| **View All Customers** | ✅ Yes | ❌ No |
| **View Own Invoices** | ✅ Yes | ✅ Yes |
| **Submit Tickets** | ✅ Yes | ✅ Yes |
| **View Reports** | ✅ Yes | ❌ No |
| **System Settings** | ✅ Yes (if admin) | ❌ No |
| **Manage Projects** | ✅ Yes | 👁️ View only |
| **Payment** | 👁️ View | ✅ Can pay |

---

## 🔍 How to Identify Which Account Type You Have

### You have ADMIN/STAFF account if:
- ✅ You can see "Dashboard" with charts
- ✅ You can see "Leads" menu
- ✅ You can see "Setup" or "Settings" menu
- ✅ You can see all customers list
- ✅ You can add new invoices

### You have CLIENT account if:
- ✅ You see "Good Afternoon [name]"
- ✅ You see subscription/package information
- ✅ You see "On trial: X days left"
- ✅ Limited menu (only your data)
- ✅ URL is `/clients` not `/admin`

**Based on your screenshots: You currently have CLIENT account!**

---

## 📊 Data Flow Diagram

```
┌─────────────┐
│   ADMIN     │
│   PORTAL    │
└──────┬──────┘
       │
       │ Creates/Manages
       │
       ↓
┌─────────────────────────────────────────┐
│           DATABASE                       │
│  • Leads                                │
│  • Customers                            │
│  • Invoices                             │
│  • Projects                             │
│  • Tasks                                │
│  • Estimates                            │
│  • Proposals                            │
└─────────────┬───────────────────────────┘
              │
              │ Filtered by customer_id
              │
              ↓
       ┌─────────────┐
       │   CLIENT    │
       │   PORTAL    │
       └─────────────┘
```

**Key Point:** Client portal shows **filtered** data (only their records), Admin portal shows **everything**.

---

## 🆘 Troubleshooting Common Confusions

### "I can't see leads menu"
→ You're logged in as CLIENT, not admin

### "I can only see my own invoices"
→ You're in CLIENT portal, not admin portal

### "Invalid email or password at /admin"
→ You're using CLIENT credentials, not STAFF credentials

### "Where is the Kanban board?"
→ Kanban is in ADMIN portal → Leads → Switch to Kanban view

### "I want to add a customer"
→ First add as LEAD, then convert to customer
→ OR go to Customers → New Customer

---

## ✅ Quick Action Checklist

To fully use your CRM, you need to:

- [ ] **Step 1:** Find your ADMIN credentials (check email)
- [ ] **Step 2:** Login at `/admin` with admin credentials
- [ ] **Step 3:** Explore the admin dashboard
- [ ] **Step 4:** Add staff members (if needed)
- [ ] **Step 5:** Start adding leads
- [ ] **Step 6:** Convert leads to customers
- [ ] **Step 7:** Create invoices/estimates
- [ ] **Step 8:** Set up currency to ₹ (if not done)
- [ ] **Step 9:** Customize your CRM settings
- [ ] **Step 10:** Delete temporary PHP files for security

---

## 📚 Summary

### Your Current Status:
```
✅ CRM Instance: Created (vezan.nooryakcrm.com)
✅ CLIENT Access: Working (wezan.bahad@gmail.com)
❌ ADMIN Access: Need credentials
```

### What You Need:
```
🔑 Admin/Staff login credentials
   Email: [Unknown - need to find]
   Password: [Unknown - need to find]
   
   Login at: https://vezan.nooryakcrm.com/admin
```

### Next Steps:
```
1. Search email for admin credentials
2. OR use reset_admin_password.php tool
3. Login to /admin portal
4. Start using full CRM features
5. Delete temporary security files
```

---

## 🎓 Learning Resources

### Where to Learn More:
1. **Video Tutorial:** Look for "Perfex CRM Tutorial" on YouTube
2. **Documentation:** Check `/admin/knowledge_base` in your CRM
3. **Support:** Contact Nooryak Technologies support

### Recommended First Tasks (After Admin Login):
1. Go to Setup → Staff → Add your team members
2. Go to Setup → Customers → Add your first customer
3. Go to Leads → New Lead → Add some leads
4. Try the Kanban board view
5. Create a sample invoice
6. Explore reports section

---

**Remember:** 
- **Admin Portal** = Your office (manage business)
- **Client Portal** = Your customer's view (see their data)

You're currently in the customer's view. You need to get into your office (admin portal)!

---

**Created:** June 16, 2026  
**Version:** 1.0  
**For:** Vezan Company CRM Instance
