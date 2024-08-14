<?php

namespace App\Repositories\Accounting;

use DB;
use App\Models\Accounting\{CreditNote};

class CreditNoteRepository
{
    public function raiseCreditNote($credit_note_items)
    {
        try {
            DB::beginTransaction();
    
            foreach ($credit_note_items as $item) {
                // Check if the credit note item already exists
                $exists = CreditNote::where('invoice_detail_id', $item['invoice_detail_id'])->exists();
    
                if ($exists) {
                    DB::rollback();
                    return false; // Return false if any item already exists
                }
    
                // Create the credit note item if it doesn't exist
                CreditNote::create($item);
            }
    
            DB::commit();
            return true; // Return true if all items were created successfully
    
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return false;
        }
    }
    
}