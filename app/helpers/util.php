<?php

namespace App\helpers;

class util
{
    public static function generateUniqueInvoice()
    {
        $random = time() . rand(10 * 45, 100 * 98);

        return 'INV-' . $random;
    }

    private static function generateUniqueAudNo(string $class, $column)
    {
        $random = 'INV-' . time() . rand(10 * 45, 100 * 98);
        // $lastInvNumber = $class::latest()->first('invoice_number');

        do {
            // $random = time() . rand(10 * 45, 100 * 98);
            $number = $random + 1;
        } while ($class::where($column, $number)->exists());

        return $number;
    }

}