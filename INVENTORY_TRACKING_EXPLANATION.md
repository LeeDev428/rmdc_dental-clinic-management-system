# NEW Inventory Tracking System

## Problem with OLD System

**OLD Logic**:
- `quantity` = total boxes (3 boxes)
- `items_per_unit` = pieces per box (constant: 20 masks/box)
- When using 10 masks → deduct 0.5 boxes → `quantity` becomes 2.5
- **Issue**: Fractional boxes are confusing!

## NEW System Requested by User

### Concept
Track **full boxes** separately from **open box pieces**:
- `quantity` = Number of FULL, UNOPENED boxes
- `items_per_unit` = Pieces remaining in the CURRENT OPEN box
- `original_items_per_unit` = How many pieces a fresh box contains (constant)

### Example Walkthrough

#### Starting Inventory
```
Face Masks:
- quantity: 3 (full boxes)
- items_per_unit: 20 (pieces in current open box)
- original_items_per_unit: 20 (constant - fresh box has 20 masks)
```

#### Appointment 1: Use 10 masks
```
Action: Deduct 10 from items_per_unit
Result:
- quantity: 3 (no change - still have 3 full boxes)
- items_per_unit: 10 (20 - 10 = 10 masks left in open box)
```

#### Appointment 2: Use 15 masks
```
Current: items_per_unit = 10 (only 10 left in open box)
Need: 15 masks total

Step 1: Use all 10 from current open box
  items_per_unit: 10 - 10 = 0

Step 2: Need 5 more masks
  - Open a new box from quantity
  - quantity: 3 - 1 = 2
  - items_per_unit: 20 (new box) - 5 (used) = 15

Result:
- quantity: 2 (used 1 full box)
- items_per_unit: 15 (pieces left in newly opened box)
```

#### Appointment 3: Use 20 masks
```
Current: items_per_unit = 15
Need: 20 masks

Step 1: Use all 15 from current open box
  items_per_unit: 15 - 15 = 0

Step 2: Need 5 more masks
  - Open a new box
  - quantity: 2 - 1 = 1
  - items_per_unit: 20 - 5 = 15

Result:
- quantity: 1
- items_per_unit: 15
```

## Database Changes Needed

### Add `original_items_per_unit` column
```php
Schema::table('inventories', function (Blueprint $table) {
    $table->integer('original_items_per_unit')->default(1)->after('items_per_unit');
});
```

### Update existing data
```sql
UPDATE inventories 
SET original_items_per_unit = items_per_unit;
```

## New Deduction Logic (in AdminAppointment.php)

```php
public function deductInventory($inventory, $piecesNeeded)
{
    $originalItemsPerUnit = $inventory->original_items_per_unit;
    $currentOpenBoxPieces = $inventory->items_per_unit;
    $fullBoxes = $inventory->quantity;
    
    // Can we fulfill from current open box?
    if ($piecesNeeded <= $currentOpenBoxPieces) {
        // Simple case: deduct from open box
        $inventory->items_per_unit -= $piecesNeeded;
        $inventory->save();
        return;
    }
    
    // Need more than what's in open box
    $remainingNeeded = $piecesNeeded - $currentOpenBoxPieces;
    $inventory->items_per_unit = 0; // Empty current box
    
    // How many full boxes do we need to open?
    $fullBoxesNeeded = floor($remainingNeeded / $originalItemsPerUnit);
    $piecesFromLastBox = $remainingNeeded % $originalItemsPerUnit;
    
    // Check if we have enough full boxes
    if ($fullBoxesNeeded > $fullBoxes || 
        ($fullBoxesNeeded == $fullBoxes && $piecesFromLastBox > 0)) {
        throw new \Exception("Insufficient inventory!");
    }
    
    // Deduct full boxes
    $inventory->quantity -= $fullBoxesNeeded;
    
    // Handle partial box
    if ($piecesFromLastBox > 0) {
        $inventory->quantity -= 1; // Open one more box
        $inventory->items_per_unit = $originalItemsPerUnit - $piecesFromLastBox;
    } else {
        // Used exact number of boxes, reset items_per_unit
        $inventory->items_per_unit = $originalItemsPerUnit;
    }
    
    $inventory->save();
}
```

## Validation Logic (in procedure_prices.blade.php)

```javascript
function getTotalAvailablePieces(inventory) {
    const fullBoxes = parseFloat(inventory.quantity) || 0;
    const openBoxPieces = parseFloat(inventory.items_per_unit) || 0;
    const originalPerBox = parseFloat(inventory.original_items_per_unit) || 1;
    
    // Total = (full boxes × pieces per box) + pieces in open box
    return (fullBoxes * originalPerBox) + openBoxPieces;
}

function validateQuantity() {
    const maxPiecesAvailable = getTotalAvailablePieces(selectedInventory);
    
    if (requestedPieces > maxPiecesAvailable) {
        showError(`Only ${maxPiecesAvailable} pieces available!`);
        return false;
    }
    return true;
}
```

## Benefits

✅ **No fractional boxes**: `quantity` is always a whole number
✅ **Clear tracking**: Know exactly how many full boxes + open box pieces
✅ **Realistic**: Matches how inventory actually works in real life
✅ **Easy to understand**: "3 boxes + 15 pieces in open box"
