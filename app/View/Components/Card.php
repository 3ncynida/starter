<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $product;

    public $isPromo;

    public $hargaPromo;

    public function __construct($product)
    {
        $this->product = $product;
        $this->calculatePromo();
    }

    private function calculatePromo()
    {
        // Cek apakah produk sedang dalam promosi
        $this->isPromo = $this->product->Promosi &&
                        $this->product->TanggalMulaiPromosi &&
                        $this->product->TanggalSelesaiPromosi &&
                        now()->between(
                            $this->product->TanggalMulaiPromosi,
                            $this->product->TanggalSelesaiPromosi
                        );

        // Hitung harga promo jika ada
        if ($this->isPromo) {
            $this->hargaPromo = $this->product->Harga -
                               ($this->product->Harga * $this->product->DiskonPersen / 100);
        } else {
            $this->hargaPromo = null;
        }
    }

    public function render()
    {
        return view('components.card');
    }
}
