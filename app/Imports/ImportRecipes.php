<?php

namespace App\Imports;

use Faker\Core\Number;
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
   protected $result = [];

   public function __construct($idExcelImport)
   {
      $this->idExcelImport = $idExcelImport;
   }

   public function model(array $row)
   {
      $this->rows+=1;
      $titel = array_keys($row);
      if ($titel[0] != 'stt' || $titel[1] != 'ten_vat_tu' || $titel[2] != 'so_luong') {
         return abort(500, 'Lỗi format file không đúng chuẩn');
      }

      if(empty($row['ten_vat_tu'])||empty($row['so_luong'])){
         return abort(500, 'Nhập thiếu thông tin dòng '.((int)$this->rows + 1));
      }
      if ($row['so_luong'] < 0 || !is_numeric($row['so_luong'])) {
         return abort(500, 'Số lượng không hợp lệ '.((int)$this->rows + 1));
      }
      
      $company_id = Auth::user()->company_id;
      $exist_vt = VtProduct::whereRaw('LOWER(`name`) = ? ',[trim(strtolower($row['ten_vat_tu']))])->whereNotNull('deleted_at') ->first();
      $exist_company = isset($exist_vt) ? $exist_vt->company_id : 0;
      if($exist_company != $company_id){
         return abort(500, 'Vật tư chưa tồn tại trong hệ thống lỗi dòng '.((int)$this->rows + 1));
      }
      $data = [
         'vt_import_excel_id' => $this->idExcelImport,
         'vt_product_id' => $exist_vt->id,
         'vt_product_name' => $row['ten_vat_tu'],
         'amount' => $row['so_luong'],
      ];
      array_push($this->result,$data);
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

   public function getDataImport(): array
   {
      return $this->result;
   }

   public function getRowCount(): int
   {
      return $this->rows;
   }
}
