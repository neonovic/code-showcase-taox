<?php

namespace App\Services\Wishlist;

use App\Models\UzivatelShopu;
use App\Models\Wishlist;
use App\Models\WishlistSharing;
use App\Models\Zbozi;
use App\Models\ZboziVarianta;

/**
 * Objekt, ktery operuje nad zaznamy Wishlist itemu pro konkretniho uzivatele
 * Tabulka: wishlists
 */
class WishlistManager
{
    private $user;

    public function __construct(UzivatelShopu $user)
    {
        $this->user = $user;
    }

    /**
     * Najde Item nebo vytvori novy a aktualizuje jeho quantity.
     * Lze zadat i zapornou quantity. Proto se tato metoda jmenuje addOrRemove - lze sni pridat i odebrat.
     */
    public function addOrRemoveItem(Zbozi $product, ?ZboziVarianta $variant = null, int $quantity = 1)
    {
        $wishlistItem = $this->getItemOrCreate($product, $variant);

        $newQuantity = $wishlistItem->quantity + $quantity;

        if ($newQuantity <= 0) {
            $wishlistItem->delete();
            return;
        }

        $wishlistItem->quantity = $newQuantity;
        $wishlistItem->save();
    }

    /**
     * Vraci pocet unikatnich Item zaznamu ve Wishlistu.
     * Nejde o pocet vsech kusu, ale o pocet vsech druhu zbozi/variant.
     */
    public function getItemsCount(): int
    {
        return $this->user->wishlistItems()->count();
    }

    /**
     * Vraci Collection vsech Wishlist Item zaznamu
     */
    public function getItems()
    {
        return $this->user->wishlistItems;
    }

    /**
     * Nachazi se produkt v uzivatelove wishlistu?
     * Pouzivam hlavne z Twigu
     */
    public function isItemInWishlist(int $productId, int $variantId = 0)
    {
        return $this->getItems()
            ->where('zbozi_id', $productId)
            ->where('variant_id', $variantId)
            ->count();
    }

    public function hasAnySharedWishlists()
    {
        return WishlistSharing::where('uzivatele_shopu_id', $this->user->id)->exists();
    }

    /**
     * Vstupuje pole parametru:
     *   [ID samotneho zaznamu v tabulce wishlist => mnozstvi kusu]
     *
     * Tim, ze vstupuje pole konkretnich IDcek v tabulce, tak nemusim dohledavat pres zbozi_id, varianta_id
     */
    public function updateQuantityFromArrayOfWishlistIds(array $wishlistIds)
    {
        foreach ($wishlistIds as $itemId => $itemQuantity) {
            if (!is_numeric($itemQuantity)) {
                continue;
            }
            if ($itemQuantity <= 0) {
                Wishlist::find($itemId)->delete();
                continue;
            }
            $this->updateWishlistId($itemId, $itemQuantity);
        }
    }

    /**
     * Zahodi z wishlistu vsechny kusy urciteho produktu/varianty
     */
    public function dropItem(Zbozi $product, ?ZboziVarianta $variant = null)
    {
        $this->getItem($product, $variant)->delete();
    }

    /**
     * Aktualizuje quantity pro konkretni IDcko v tabulce wishlist
     */
    private function updateWishlistId(int $wishlistId, int $quantity)
    {
        $wishlistItem = Wishlist::find($wishlistId);
        $wishlistItem->quantity = $quantity;
        $wishlistItem->save();
    }

    /**
     * Vraci Item ve Wishlistu, ktery odpovidat Product ID - pokud neni zadane Variant ID, tak se dosadi 0 - a pres nulu
     * ohybam produkty bez variant
     */
    private function getItem(Zbozi $product, ?ZboziVarianta $variant = null)
    {
        return Wishlist::where('uzivatele_shopu_id', $this->user->id)
            ->where('zbozi_id', $product->id)
            ->where('variant_id', $this->getVariantId($variant))
            ->first();
    }

    /**
     * Pokud Item jiz ve Wishlistu existuje tak jej vrati, jinak vytvori novy
     */
    private function getItemOrCreate(Zbozi $product, ?ZboziVarianta $variant = null)
    {
        return $this->getItem($product, $variant) ?? $this->createItem($product, $variant);
    }

    /**
     * Vytvori/Pripravi novy Item ve Wishlistu, quantity = 0
     */
    private function createItem(Zbozi $product, ?ZboziVarianta $variant = null)
    {
        return Wishlist::create([
            'uzivatele_shopu_id' => $this->user->id,
            'zbozi_id' => $product->id,
            'variant_id' => $this->getVariantId($variant),
            'quantity' => 0
        ]);
    }

    /**
     * Variant ID ohybam - pokud nejde o produkt s variantou, tak mu nastavuju Variant ID = 0
     */
    private function getVariantId(?ZboziVarianta $variant = null)
    {
        return isset($variant) ? $variant->id : 0;
    }
}
