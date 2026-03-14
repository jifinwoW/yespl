<?php
function UpdateStockBalance($entityData) {
    $data = $entityData->{'data'};

    global $adb;

    $productid  = explode('x',$data['spr_product_id'])[1];    // Product ID
    $qty        = (float) $data['spr_qty'];   // Quantity to reduce
    $engineerid = explode('x',$data['spr_engineer_id'])[1];   // Engineer ID (fix from your original code)

    // 1. Fetch current stock balance for that product & engineer
    $result = $adb->pquery(
        "SELECT engineerstockbalanceid, sb_qty 
         FROM vtiger_engineerstockbalance 
         WHERE sb_product_id = ? AND sb_engineer_id = ?",
        [$productid, $engineerid]
    );

    if ($adb->num_rows($result) > 0) {
        $row      = $adb->fetch_array($result);
        $recordId = $row['engineerstockbalanceid'];
        $current  = (float) $row['sb_qty'];

        // 2. Calculate new balance
        $newQty = $current - $qty;
        if ($newQty < 0) {
            $newQty = 0; // Optional safeguard to prevent negatives
        }

        // 3. Update the record
        $adb->pquery(
            "UPDATE vtiger_engineerstockbalance 
             SET sb_qty = ? 
             WHERE engineerstockbalanceid = ?",
            [$newQty, $recordId]
        );
    } else {
        // No record found — you might want to insert a new one or log it
        // Example: insert with negative stock if allowed
        /*
        $adb->pquery(
            "INSERT INTO vtiger_engineerstockbalance (engineerstockbalanceid, sb_product_id, sb_engineer_id, sb_qty) VALUES (?, ?, ?, ?)",
            [$adb->getUniqueId("vtiger_engineerstockbalance"), $productid, $engineerid, max(0, -$qty)]
        );
        */
    }
}
