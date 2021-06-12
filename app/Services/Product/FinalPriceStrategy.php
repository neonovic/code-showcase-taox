<?php

namespace App\Services\Product;

use App\Legacy\Entity\SlevaRub;

class FinalPriceStrategy
{
    /**
     * @var string
     */
    public $priceModel;

    public function __construct(?SlevaRub $discountCategory = null)
    {
        $this->discountCategory = $discountCategory;
    }

    public function getFinalPrice($price, $actionPrice, $vipPrice)
    {
        switch ($this->getPriceModel($price, $actionPrice, $vipPrice)) {
            case PriceModel::NORMAL:
                return $this->applyCategoryDiscount($price);

            case PriceModel::ACTION:
                return $this->applyCategoryDiscount($actionPrice);

            case PriceModel::VIP:
                return $this->applyCategoryDiscount($vipPrice);
        }

        throw new \InvalidArgumentException('Nepodařilo se vypočítat finální cenu. Neexistuje žádný price model pro: ' . $this->priceModel);
    }

    public function getPriceModel($price, $actionPrice, $vipPrice): string
    {
        if ($this->isVipUser() && $vipPrice > 0) {
            return $this->priceModel = PriceModel::VIP;
        }

        if ($actionPrice > 0 && $actionPrice < $price) {
            return $this->priceModel = PriceModel::ACTION;
        }

        return $this->priceModel = PriceModel::NORMAL;
    }

    private function categoryDiscountExists(): bool
    {
        return $this->discountCategory !== null;
    }

    /**
     * Pokud existuje sleva na kategorii, tak se ještě musí ověřit pravidlo, zda se má opravdu cena přepočítat.
     * V podstatě se nepřepočítá jen pokud by user byl VIP a sleva na kategorii by nebyla pro VIP povolena.
     */
    private function shouldCategoryDiscountBeApplied(): bool
    {
        if (!$this->categoryDiscountExists()) {
            return false;
        }

        if (!$this->isVipUser() && $this->discountCategory->isForUnregistered()) {
            return true;
        }

        if ($this->priceModel !== PriceModel::VIP && $this->isVipUser() && $this->discountCategory->isForVip()) {
            // pokud je pro produkt rucne zadana vip cena, tak slevu na kategorii neaplikovat (i kdyz je pro vip aktivni)
            // lze takto pretizit hromadnou slevu na kategorii, individualni vip cenou u produktu
            return true;
        }

        if ($this->isVip2User() && $this->discountCategory->isForVip2()) {
            return true;
        }

        return false;
    }

    private function applyCategoryDiscount(float $price)
    {
        if ($this->shouldCategoryDiscountBeApplied()) {
            return $this->discountCategory->koef() * $price;
        }

        return $price;
    }

    private function isVipUser(): bool
    {
        return !empty($_SESSION['vip_zakaznik']) && $_SESSION['vip_zakaznik'] == 1;
    }


    private function isVip2User(): bool
    {
        return !empty($_SESSION['vip_zakaznik2']) && $_SESSION['vip_zakaznik2'] == 1;
    }
}
