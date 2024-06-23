<?php

namespace App\Livewire\Main\Penjualan;

use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cetakinvoice extends Component {
    public $id;
    public $namacustomer;
    public $tgljual;
    public $totalqty;
    public $grandtotal;

    public $datas;


    public function mount($id) {
        $this->id = $id;
        $query = DB::select(
            "
            SELECT
                a.customernama as NamaCustomer, a.TglJual,
                b.jumlah+b.jumlahkoreksi as Qty,c.nama as NamaBarang,c.hargajual as Harga,
                (b.jumlah+b.jumlahkoreksi) * c.hargajual as Total
            FROM `penjualanhds` a
            left join penjualandts b on b.penjualanhdid=a.id
            left join timsetuppakets c on c.id=b.timsetuppaketid
            where a.id=$this->id
            "
        );
        $this->datas = $query;
        $this->namacustomer = $this->datas[0]->NamaCustomer;
        $this->tgljual = $this->datas[0]->TglJual;
        $date = new DateTime($this->tgljual);
        $formattedDate = $date->format('d M Y');
        $this->tgljual = $formattedDate;

        $queryTotal = DB::select(
            "
            SELECT
                sum(b.jumlah+b.jumlahkoreksi) as TotalQty,
                sum((b.jumlah+b.jumlahkoreksi) * c.hargajual) as GrandTotal
            FROM `penjualanhds` a
            left join penjualandts b on b.penjualanhdid=a.id
            left join timsetuppakets c on c.id=b.timsetuppaketid
            where a.id=$this->id
            group by a.id
            "
        );

        $this->totalqty = $queryTotal[0]->TotalQty;
        $this->grandtotal = $queryTotal[0]->GrandTotal;
    }
    public function render() {
        return view('livewire.main.penjualan.cetakinvoice', [
            'id' => $this->id,
        ]);
    }
}
