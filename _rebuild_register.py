# -*- coding: utf-8 -*-
"""Rebuild truncated register.php from head template + preserved tail."""
from pathlib import Path

ROOT = Path(r'c:\xampp\htdocs\crm\application\views\themes\perfex\views')
TARGET = ROOT / 'register.php'
HEAD = ROOT / '_register_head.part'
TAIL = TARGET.read_text(encoding='utf-8')

head = HEAD.read_text(encoding='utf-8')
TARGET.write_text(head + TAIL, encoding='utf-8', newline='\r\n')
lines = len((head + TAIL).splitlines())
print(f'Written {lines} lines to {TARGET}')
checks = [
    'crm-register-wrap',
    '<style>',
    'registration_leftimage.png',
    'crm-register-plan-box',
    'register-country-group',
    'passwordr',
    '<script>',
]
for c in checks:
    print(f'  {c}:', c in (head + TAIL))
