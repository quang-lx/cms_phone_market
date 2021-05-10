<?php

namespace App\Imports;

use Modules\Mon\Entities\VtImportProduct;
use Modules\Mon\Entities\VtProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
      $company_id = Auth::user()->company_id;
      $exist_vt = VtProduct::find($row['ma_vat_tu']);
      $exist_company = isset($exist_vt) ? $exist_vt->company_id : $company_id;
      
      $data = [
         'vt_import_excel_id' => $this->idExcelImport,
         'vt_product_id' => $row['ma_vat_tu'],
         'vt_product_name' => $row['ten_vat_tu'],
         'amount' => $row['so_luong'],
      ];
      if($exist_company != $company_id){
         $data['note']='Mã vật tư đã tồn tại';
      }
      return  VtImportProduct::create($data);
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
