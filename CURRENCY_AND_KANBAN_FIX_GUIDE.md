# Currency Symbol & Kanban Scrollbar Fix Guide

## Issues Fixed

### 1. Currency Symbol Change ($ to ₹)
All currency symbols throughout the application have been updated from Dollar ($) to Indian Rupee (₹).

### 2. Kanban Lead Card Hidden When Dragging
Fixed the issue where lead cards would disappear when dragging in the Kanban board due to overflow and z-index conflicts.

---

## Files Modified

### CSS File Updated
**File:** `assets/css/custom.css`

**Changes Made:**
1. Added `overflow: visible` to card containers to prevent clipping during drag
2. Added `z-index: 1` to normal lead cards
3. Added `z-index: 9999` to dragged cards (`.ui-sortable-helper`)
4. Fixed scrollbar behavior in Kanban columns with proper `overflow-y: auto` and `overflow-x: hidden`

**Specific CSS additions:**
```css
.leads-kan-ban li.lead-kan-ban {
  /* ... existing styles ... */
  z-index: 1 !important;
}

.leads-kan-ban li.lead-kan-ban.ui-sortable-helper {
  z-index: 9999 !important;
  pointer-events: auto !important;
  opacity: 0.95 !important;
}

.leads-kan-ban .kan-ban-content-wrapper {
  overflow-y: auto !important;
  overflow-x: hidden !important;
}
```

---

## How to Update Currency Symbol

### Option 1: Using Web Interface (Recommended)

1. **Open your browser** and navigate to:
   ```
   http://your-domain/crm/update_currency_symbol.php
   ```
   Or locally:
   ```
   http://localhost/crm/update_currency_symbol.php
   ```

2. **Review** the current currencies displayed in the table

3. **Click** the "Update $ to ₹" button

4. **Confirm** the update when prompted

5. **Delete** the `update_currency_symbol.php` file after successful update for security

### Option 2: Using phpMyAdmin

1. **Open phpMyAdmin** in your browser

2. **Select** the database `nooryak_crm`

3. **Click** on SQL tab

4. **Copy and paste** the following SQL query:
   ```sql
   UPDATE `tbl_currencies` SET `symbol` = '₹' WHERE `symbol` = '$';
   ```

5. **Click** "Go" to execute the query

6. **Verify** the update by checking the `tbl_currencies` table

### Option 3: Using SQL File

1. **Locate** the file `update_currency_to_rupee.sql` in the CRM root directory

2. **Import** this file using:
   - phpMyAdmin: Import tab
   - MySQL command line: 
     ```bash
     mysql -u root -p nooryak_crm < update_currency_to_rupee.sql
     ```

---

## Testing the Fixes

### Test Kanban Drag & Drop

1. Navigate to **Leads → Kanban View**

2. Try to **drag** a lead card from one column to another

3. **Verify** that:
   - The lead card remains visible during drag
   - The card follows your cursor smoothly
   - The card drops correctly into the target column
   - No scrollbar issues occur
   - Cards don't get hidden behind other elements

### Test Currency Display

1. Check **all pages** where currency is displayed:
   - Dashboard
   - Leads Kanban (lead values)
   - Invoices
   - Estimates
   - Proposals
   - Expenses
   - Projects
   - Client profiles

2. **Verify** that all monetary values show `₹` instead of `$`

3. **Create** a new invoice/estimate to test the currency symbol in new records

---

## Clearing Cache

After making these changes, clear your browser cache:

### Chrome/Edge
1. Press `Ctrl + Shift + Delete`
2. Select "Cached images and files"
3. Click "Clear data"

### Firefox
1. Press `Ctrl + Shift + Delete`
2. Select "Cache"
3. Click "Clear Now"

### Or Force Refresh
- Windows: `Ctrl + F5`
- Mac: `Cmd + Shift + R`

---

## Rollback Instructions

If you need to revert the changes:

### Revert Currency Symbol
```sql
UPDATE `tbl_currencies` SET `symbol` = '$' WHERE `symbol` = '₹';
```

### Revert CSS Changes
Remove or comment out the added CSS rules in `assets/css/custom.css`:
- Lines related to `.ui-sortable-helper`
- `overflow` properties added to `.kan-ban-content-wrapper` and `.kan-ban-content`

---

## Additional Notes

### Why These Changes Work

1. **z-index fix:** When a card is being dragged (has class `.ui-sortable-helper`), it needs a higher z-index than all other elements to appear on top

2. **overflow fix:** The parent containers were set to `overflow: hidden` which was clipping the dragged element. Changed to `overflow: visible` for the main container and `overflow-y: auto` only for the scrollable content area

3. **Currency update:** The currency symbol is stored in the `tbl_currencies` table and fetched by the `app_format_money()` function in `application/helpers/sales_helper.php`

### Files to Keep

- `assets/css/custom.css` - Contains all the Kanban styling fixes

### Files to Delete After Update

- `update_currency_symbol.php` - Security risk if left accessible
- `check_currency.php` - Temporary diagnostic file
- `update_currency_to_rupee.sql` - No longer needed after update
- `CURRENCY_AND_KANBAN_FIX_GUIDE.md` - This guide (optional, keep for reference)

---

## Troubleshooting

### Currency Still Shows $

1. Clear browser cache completely
2. Check database was actually updated:
   ```sql
   SELECT * FROM tbl_currencies WHERE symbol = '$';
   ```
3. Verify the base currency is set correctly in Admin → Settings → Currencies

### Lead Still Disappears When Dragging

1. Clear browser cache
2. Check that `custom.css` file was actually modified
3. Verify no other CSS files are overriding the styles
4. Check browser console (F12) for JavaScript errors

### Changes Not Appearing

1. Hard refresh the page: `Ctrl + F5` (Windows) or `Cmd + Shift + R` (Mac)
2. Check file permissions - ensure web server can read the CSS file
3. Verify the CSS file path is correct in the HTML
4. Check for CSS minification - you might need to update both `custom.css` and `custom.min.css` if it exists

---

## Support

If you encounter any issues:

1. Check browser console for JavaScript errors (F12 → Console tab)
2. Verify database connection and query execution
3. Ensure all files have proper read permissions
4. Review the modifications in the files listed above

---

**Last Updated:** June 3, 2026
**CRM Version:** Compatible with Perfex CRM and similar CodeIgniter-based CRMs
