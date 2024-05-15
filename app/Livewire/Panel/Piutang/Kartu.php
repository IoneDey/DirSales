<?php

namespace App\Livewire\Panel\Piutang;

use App\Models\Tim;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Kartu extends Component {
    public $title = 'Kartu Piutang';

    public $timid;
    public $timnama;

    public $dbTims;
    public $dbKartuPiutang;

    public function mount() {
        $this->dbTims = Tim::get();
    }

    public function updatedtimid($id) {
        $this->timnama = Tim::find($id)->nama;
        $this->refresh();
        //dump($this->timnama);
    }

    public function refresh() {
        $subquery = DB::table('penjualanhds as a')
            ->select(
                'a.tgljual',
                DB::raw('g.nama AS Tim'),
                'a.nota',
                'a.customernama',
                DB::raw('SUM(b.jumlah*c.hargajual) AS debet'),
                DB::raw('0 AS kredit')
            )
            ->leftJoin('penjualandts as b', 'b.penjualanhdid', '=', 'a.id')
            ->leftJoin('timsetups as f', 'f.id', '=', 'a.timsetupid')
            ->leftJoin('tims as g', 'g.id', '=', 'f.timid')
            ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
            ->where('g.nama', $this->timnama)
            ->groupBy('a.tgljual', 'g.nama', 'a.nota', 'a.customernama');

        $subquerySql = "(" . $subquery->toSql() . ") AS X";

        $this->dbKartuPiutang = DB::table(DB::raw($subquerySql))
            ->mergeBindings($subquery)
            ->select(
                'X.tgljual',
                'X.tim',
                'X.nota',
                'X.customernama',
                DB::raw('IFNULL(X.Debet, 0) AS debet'),
                'X.kredit',
                DB::raw('@saldo := @saldo + (IFNULL(X.Debet, 0) - X.kredit) AS Saldo')
            )
            ->crossJoin(DB::raw('(SELECT @saldo := 0) AS vars'))
            ->orderBy('X.tim')
            ->orderBy('X.tgljual')
            ->get();
    }

    public function render() {
        $this->refresh();

        return view('livewire.panel.piutang.kartu', [
            'dbKartuPiuangs' => $this->dbKartuPiutang,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
