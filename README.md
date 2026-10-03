# TechHub - Tech Store Catalog (311 Midterm)

Run: `php -S localhost:8000` inside this folder, then open http://localhost:8000
(or drop the folder into XAMPP `htdocs`). Make sure `uploads/` is writable.

Discount codes: TECH5 (5%), STUDENT (10%), BICOL15 (15%). Volume: 5% at P20k+, 10% at P50k+. Max 20% total. VAT 12%.

## Defense map
| Member | File | Topics |
|---|---|---|
| 1 | includes/validation.php, e() in includes/functions.php | empty, trim, filter_var, htmlspecialchars, sticky form, PRG in index.php |
| 2 | includes/upload.php | $_FILES, size/MIME check, move_uploaded_file |
| 3 | includes/data.php, views/*.php | multidimensional array, foreach / endforeach / endif |
| 4 | includes/functions.php | type-hinted functions, &$products, static $count, discount + VAT math |

## Rubric checklist
- Sticky POST form, filter_var/empty, htmlspecialchars everywhere, $_FILES + move_uploaded_file, PRG (header + exit): index.php, validation.php, upload.php
- Multidimensional assoc. array, typed functions, pass-by-reference (deduct_stock), static (next_row_number), count/in_array/array_keys/max/usort/rtrim: data.php, functions.php
- foreach + alternative syntax, if/elseif/else, match: views/*.php, functions.php
- Math (discount/VAT), ??, <=> (sort_products), .= (build_receipt), ===: functions.php, validation.php

## Likely live-code challenges
- Min product name length 3 -> 8: validation.php (`strlen($name) < 3`)
- Default sort ascending -> descending: index.php (`'price_asc'`) / functions.php `sort_products`
- VAT rate: data.php `VAT_RATE`
- Max upload size: upload.php `MAX_UPLOAD_BYTES`
