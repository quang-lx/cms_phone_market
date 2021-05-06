<?php

namespace App\Imports;

use Modules\Mon\Entities\VtImportProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ImportRecipes implements ToModel, WithHeadingRow
{
   protected $rows = 0;
   /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   protected $idExcelImport;

   public function __construct($idExcelImport)
   {
      $this->idExcelImport = $idExcelImport;
   }

   public function model(array $row)
   {
      if(empty($row['ten_vat_tu'])||empty($row['so_luong'])){
         return;
      }
      $this->rows+=1;
      return  VtImportProduct::create([
         'vt_import_excel_id' => $this->idExcelImport,
         'vt_product_id' => $row['ma_vat_tu'],
         'vt_product_name' => $row['ten_vat_tu'],
         'amount' => $row['so_luong'],
      ]);
   }

   // public function collection(Collection $rows)
   // {
   //    foreach ($rows as $row) {
   //       VtImportProduct::create([
   //          'vt_import_excel_id' => $this->idExcelImport,
   //          'vt_product_id' => $row[0],
   //          'vt_product_name' => $row[1],
   //          'amount' => $row[2],
   //       ]);
   //    }
   // }

   public function getRowCount(): int
   {
      return $this->rows;
   }
}
