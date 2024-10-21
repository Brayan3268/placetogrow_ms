<?php

namespace App\Imports;

use App\Http\PersistantsLowLevel\InvoicePll;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvoicesImport implements ToArray, WithHeadingRow
{
    protected $site_id;

    protected $processedInvoices = [];

    public function __construct($site_id)
    {
        $this->site_id = $site_id;
    }

    public function array(array $array)
    {
        foreach ($array as $row) {
            $invoiceData = [
                'reference' => $row['reference'],
                'amount' => $row['amount'],
                'currency' => $row['currency'],
                'user_id' => $row['user_id'],
                'date_created' => $this->convertToDate($row['date_created']),
                'date_surcharge' => $this->convertToDate($row['date_surcharge']),
                'amount_surcharge' => $row['amount_surcharge'],
                'date_expiration' => $this->convertToDate($row['date_expiration']),
            ];

            $this->processedInvoices[] = $invoiceData;
        }

        InvoicePll::save_invoices_imported($this->processedInvoices, $this->site_id);

    }

    private function convertToDate($dateValue)
    {
        if (is_numeric($dateValue)) {
            return Carbon::createFromFormat('Y-m-d', gmdate('Y-m-d', ($dateValue - 25569) * 86400));
        } elseif (is_string($dateValue)) {
            return Carbon::parse($dateValue);
        } else {
            return null;
        }
    }
}
