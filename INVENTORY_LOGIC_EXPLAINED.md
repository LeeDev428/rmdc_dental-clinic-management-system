# Inventory Deduction Logic - SIMPLE EXPLANATION

## THE PROBLEM YOU HAD:
Your Anesthetic Cartridges has:
- **40 boxes** in stock
- **100 pieces per box** (items_per_unit)
- You entered **41 pieces** needed
- System allowed it (BUG) and deducted **0.41 boxes** (WRONG!)

## THE CORRECT LOGIC NOW:

### Step 1: Validation (in Manage Supplies modal)
```javascript
maxPiecesAvailable = availableQty × itemsPerUnit
                  = 40 boxes × 100 pieces/box
                  = 4000 pieces available

If requestedPieces (41) > maxPiecesAvailable (4000) → ERROR
If requestedPieces (41) ≤ maxPiecesAvailable (4000) → ALLOW
```

### Step 2: Deduction (when appointment completed)
```php
unitsToDeduct = piecesNeeded ÷ itemsPerUnit
              = 41 pieces ÷ 100 pieces/box
              = 0.41 boxes

newQuantity = currentQuantity - unitsToDeduct
            = 40 boxes - 0.41 boxes
            = 39.59 boxes remaining
```

## EXAMPLES:

### Example 1: Dental Mirror (pieces unit)
- Stock: **30 pieces**
- Items per unit: **1** (because unit is already pieces)
- Request: **20 pieces**
- Max available: 30 × 1 = **30 pieces** ✓
- Validation: 20 ≤ 30 → **PASS** ✓
- Deduction: 20 ÷ 1 = **20 pieces** deducted
- New stock: 30 - 20 = **10 pieces** ✓

### Example 2: Face Masks (boxes)
- Stock: **7.86 boxes**
- Items per unit: **50** (50 masks per box)
- Request: **2 pieces (masks)**
- Max available: 7.86 × 50 = **393 pieces** ✓
- Validation: 2 ≤ 393 → **PASS** ✓
- Deduction: 2 ÷ 50 = **0.04 boxes** deducted
- New stock: 7.86 - 0.04 = **7.82 boxes** ✓

### Example 3: Cotton Rolls (packs)
- Stock: **59.90 packs**
- Items per unit: **200** (200 rolls per pack)
- Request: **10 pieces (rolls)**
- Max available: 59.90 × 200 = **11,980 pieces** ✓
- Validation: 10 ≤ 11,980 → **PASS** ✓
- Deduction: 10 ÷ 200 = **0.05 packs** deducted
- New stock: 59.90 - 0.05 = **59.85 packs** ✓

### Example 4: Anesthetic Cartridges (YOUR CASE)
- Stock: **40 boxes**
- Items per unit: **100** (100 cartridges per box)
- Request: **41 pieces (cartridges)**
- Max available: 40 × 100 = **4,000 pieces** ✓
- Validation: 41 ≤ 4,000 → **PASS** ✓
- Deduction: 41 ÷ 100 = **0.41 boxes** deducted
- New stock: 40 - 0.41 = **39.59 boxes** ✓

### Example 5: ERROR CASE - Too many requested
- Stock: **40 boxes**
- Items per unit: **100**
- Request: **4,100 pieces** ❌
- Max available: 40 × 100 = **4,000 pieces**
- Validation: 4,100 > 4,000 → **FAIL** ❌
- Error message: "Insufficient stock! Need 4,100 pieces, only 4,000 pieces available (40 boxes × 100 pieces)"
- **System blocks saving** ✓

## SUMMARY:
✅ Validation now checks: `requestedPieces ≤ (stock × items_per_unit)`
✅ Deduction formula: `unitsDeducted = pieces ÷ items_per_unit`
✅ Result: Correct decimal deductions like 0.41 boxes for 41 pieces
