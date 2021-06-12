{{--
promenne typu boolen prefixovat "is_"
typ array je potreba zminit v class componenty v $is_array
--}}

<div>

    <div id="nadpisy">E-Shop</div>
    <div id="main">
        <table border="0" cellpadding="3" cellspacing="0" class="tabulka-detail">

            <x-admin.settings.tr title="Zapnout E-shop" type="checkbox" wire:model="settings.eshop_is_active" value="1">
                Hlavni zapnuti/vypnuti celeho eshopu
                Pokud nemame aktivni eshop, tak je dobre  zmenit výchozí moduly pro kategorii - odebrat 'zbozi'
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Neomezený sklad" type="checkbox" wire:model="settings.eshop_is_sklad_unlimited" value="1">
                Kdyz 1, tak lze dat do kosiku neomezene mnostvi kusu, nekontroluje se skladovost:
                - detail Zbozi - pridavani do kosiku - javascript/POST na buttonu "Pridat"
                - "Upravit pocet" button - v prvnim kroku kosiku

                Pri realizaci objednavky (posledni krok kosiku) se stale odecita od produktu mnozstvi kusu, ktere byly objednany.
                Pujde i do minusu.
                Pokud je mnozstvi < 0 tak se prepne u zbozi "stav skladu" na hodnotu, ktera je definovana v config promenne:
                "stav_skladu_vyprodane"
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Zapnout GA Enhanced Ecommerce" type="checkbox" wire:model="settings.eshop_is_ga_enhanced_ecommerce" value="1">
                Pokud je tohle zapnuté, tak se budou odesilat transakcni js skripty pro GA Enhanced Ecommerce
                Meli bysme ale mit Enhanced ecommerce v GA zapnutou
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Stav skladu po 'vyprodání'" type="number" wire:model.lazy="settings.eshop_stav_skladu_vyprodane" style="width:40px" min="1" max="4">
                Na toto IDcko skladu se nastavi po finalizaci objednavky zbozi, pokud jeho mnozstvi klesa po objednavce pod 0.
                1 = Na dotaz
                2 = Skladem
                3 = Do týdne
                4 = Na objednávku
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Výchozí řazení produktů v kategorii" type="number" wire:model.lazy="settings.eshop_vychozi_razeni" style="width:40px" min="0" max="3">
                0 = Od nejlevnějších
                1 = Od nejdražších
                2 = Dle názvu A-Z
                3 = Dle názvu Z-A
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Štítky pro zboží" type="text" wire:model.lazy="settings.eshop_product_badges" size="65">
                Jsou zde nastavene štítky (v tabulce zbozi je sloupec pojmenovany flags) - z programatorskeho hlediska maji flags
                bitwise logiku. Zadavat je jako nasobky 2 [2,4,8].

                Pokud chci vytvorit nejakou kategorii v menu, aby zobrazovala tyto produkty, tak ji zadam do jine url '?flag=2'
                  Kde cislo je jedno z cisel, ktere zde definuji.

                Zadavat jako json: {"2":"Doprodej","4":"Nejprod."}
            </x-admin.settings.tr>

            <x-admin.settings.tr title="E-Shop má importy" type="checkbox" wire:model="settings.eshop_is_import">
                Zda eshop pouziva nejake importy - pokud "true", tak se v adminu zobrazuji ruzne detaily ohledne stavu importu
                obrazku, ze ktereho feedu zbozi pochazi apod.
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Zaokrouhlovat ceny na desetinné místa" type="text" wire:model.lazy="settings.eshop_price_decimal">
                Ovlivnuje formatovani ceny pro finalni vypis ve twig sablone. Zada se hodnota na kolik desetinnych mist se ma
                cena zaokrouhlit. Ve vetsine pripadu tedy zadame 0 nebo 2.
                0 tedy zaokrouhli na cele cislo.

                Interne budou ceny v kosiku porad pocitane tak jak jsou (napr. desetinne mista v "bez DPH") - tohle ovlivňuje jen
                vizuelní výpis.
            </x-admin.settings.tr>

        </table>
    </div>

    <br>
    <div id="nadpisy">Moduly v kategorii</div>
    <div id="main">
        <table border="0" cellpadding="3" cellspacing="0" class="tabulka-detail">

            <x-admin.settings.tr title="Výchozí moduly" type="text" wire:model.lazy="settings.modules_default" size="65">
                Výchozí moduly pro každou kategorii. Pokud v kategorii nejsou žádné vybrané moduly, tak se nastavují jako výchozí tyto.
                Zadávat oddělené čárkou: searchinpage,wysiwyg1,icons,zbozi,clanky
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Nápověda" type="text" wire:model.lazy="settings.modules_napoveda" size="65">
                Měla by slovně popisovat defaultní pořadí modulů.
                Napr.: Pokud nejsou moduly vyplněné, tak systém automaticky nastavuje tyto:  Vyhledávač, Hlavní obsahový blok, Ikony kategorií, Zboží, Články
            </x-admin.settings.tr>

        </table>
    </div>

    <br>
    <div id="nadpisy">Comgate</div>
    <div id="main">
        <table border="0" cellpadding="3" cellspacing="0" class="tabulka-detail">

            <x-admin.settings.tr title="Testovací provoz" type="checkbox" wire:model="settings.comgate_test" value="1">
                Zda provádíme platby v Comgate režimu pro testovacího provoz.
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Merchant ID" type="text" wire:model.lazy="settings.comgate_merchant" />

            <x-admin.settings.tr title="Secret password" type="text" wire:model.lazy="settings.comgate_secret">
                Napr.: z6sD2XWpdekpPv8stSIJS7QSWMhjs456
            </x-admin.settings.tr>

            <x-admin.settings.tr title="Label" type="text" wire:model.lazy="settings.comgate_label">
                Napr.: zkopanic.cz
            </x-admin.settings.tr>

        </table>
    </div>

    <br>
    <div id="nadpisy">Měny</div>
    <div id="main">
        <table border="0" cellpadding="3" cellspacing="0" class="tabulka-detail">

            <x-admin.settings.tr title="Url pro kurzovní lístek" type="text" wire:model.lazy="settings.currency_exchange_rates_file" size="65" />

        </table>
    </div>

    <br>
    <div id="nadpisy">Feedy</div>
    <div id="main">
        <table border="0" cellpadding="3" cellspacing="0" class="tabulka-detail">

            <x-admin.settings.tr title="Povolené srovnávače u kategorií" type="text" wire:model.lazy="settings.feeds_category" size="65">
                Zde je možné vložit jakýkoli název a doplní se jako input políčko na konci formuláře kategorií.
                Do input políčka se následně může uložit název kategorie, jak jej definuje konkrétní zbožový výhledávač.
                Format: ["mergado","aukro"]
            </x-admin.settings.tr>

        </table>
    </div>

</div>
