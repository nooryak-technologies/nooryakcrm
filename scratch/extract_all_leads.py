import re

with open('bazaarwa_ps_demo (4).sql', 'r', encoding='utf-8', errors='ignore') as f:
    sql = f.read()

# Find the INSERT INTO `demo_tblleads` statement
inserts = re.findall(r"INSERT INTO `demo_tblleads` VALUES\s*\((.*?)\);", sql, re.DOTALL)
if not inserts:
    # Try alternative matching
    inserts = re.findall(r"INSERT INTO `demo_tblleads`\s*\((.*?)\)\s*VALUES\s*(.*?);", sql, re.DOTALL)

print(f"Found {len(inserts)} inserts")

# Let's search for values
# A standard mysql dump insert can contain multiple rows separated by ),(
# Let's extract all rows from: INSERT INTO `demo_tblleads` VALUES (...);
all_rows = []
matches = re.finditer(r"INSERT INTO `demo_tblleads` VALUES\s*(.*?);", sql, re.DOTALL)
for m in matches:
    content = m.group(1)
    # Split by ),( but respect quotes
    # For a simple parser, let's just find all occurrences of (val1, val2, ...)
    # Since it's a standard format, we can split by '),\('
    rows = re.split(r'\),\s*\(', content.strip('()'))
    for r in rows:
        all_rows.append(r)

print(f"Total rows found: {len(all_rows)}")
for idx, r in enumerate(all_rows):
    # Split row by comma, but be careful with commas in strings.
    # Let's just do a regex to find fields or print the row.
    print(f"Row {idx+1}: {r[:120]}...")
