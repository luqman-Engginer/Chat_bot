<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('toko.nama') }} - Belanja Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --p: #4f46e5; --p2: #7c3aed; --p-soft: #eef2ff;
            --ink: #0f172a; --muted: #64748b; --line: #e2e8f0; --bg: #f8fafc;
        }
        * { box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        body { margin: 0; font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--ink); }

        /* Header */
        header { position: sticky; top: 0; z-index: 40; background: rgba(255,255,255,.85); backdrop-filter: blur(10px);
                 border-bottom: 1px solid var(--line); }
        .bar { max-width: 1140px; margin: 0 auto; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 22px; font-weight: 800; letter-spacing: -.5px; display: flex; align-items: center; gap: 8px; }
        .logo i { font-style: normal; background: linear-gradient(135deg, var(--p), var(--p2)); color: #fff;
                  width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; font-size: 18px; }
        .logo span { background: linear-gradient(135deg, var(--p), var(--p2)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .cart { position: relative; border: 1px solid var(--line); background: #fff; border-radius: 12px; padding: 9px 14px;
                font-weight: 600; cursor: pointer; font-family: inherit; font-size: 14px; }
        .cart b { position: absolute; top: -8px; right: -8px; background: var(--p); color: #fff; font-size: 11px;
                  min-width: 20px; height: 20px; border-radius: 999px; display: grid; place-items: center; padding: 0 5px; }
        .cart-drawer { position: fixed; inset: 0; z-index: 130; display: none; }
        .cart-drawer.active { display: block; }
        .cart-backdrop { position: absolute; inset: 0; background: rgba(15,23,42,.55); backdrop-filter: blur(4px); }
        .cart-panel { position: absolute; top: 0; right: 0; height: 100vh; width: min(420px, 92vw); background: #fff;
                      display: flex; flex-direction: column; box-shadow: -20px 0 60px rgba(0,0,0,.25); transform: translateX(100%);
                      transition: transform .25s ease; overflow: hidden; }
        .cart-drawer.active .cart-panel { transform: translateX(0); }
        .cart-head { padding: 18px 20px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
        .cart-head h3 { margin: 0; font-size: 18px; }
        .cart-close { border: 1px solid var(--line); background: #fff; border-radius: 10px; padding: 6px 10px; cursor: pointer; font-size: 14px; }
        .cart-body { flex: 1; overflow-y: auto; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; }
        .cart-item { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 12px; display: flex; align-items: center; gap: 12px; }
        .cart-item .pic-sm { width: 56px; height: 56px; border-radius: 12px; display: grid; place-items: center; font-size: 28px; }
        .cart-item .info { flex: 1; }
        .cart-item .nama { font-weight: 600; line-height: 1.25; }
        .cart-item .harga { color: var(--p); font-weight: 700; font-size: 14px; margin-top: 2px; }
        .cart-item .qty { display: flex; align-items: center; gap: 6px; border: 1px solid var(--line); border-radius: 999px; padding: 2px 4px; }
        .cart-item .qty button { border: 0; background: transparent; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-size: 16px; }
        .cart-item .qty button:hover { background: var(--p-soft); }
        .cart-item .qty span { min-width: 24px; text-align: center; font-weight: 600; }
        .cart-item .remove { border: 0; background: transparent; color: #b91c1c; cursor: pointer; font-size: 13px; padding: 4px 6px; border-radius: 8px; }
        .cart-item .remove:hover { background: #fee2e2; }
        .cart-empty { text-align: center; color: var(--muted); margin: auto; padding: 20px; }
        .cart-foot { border-top: 1px solid var(--line); background: #fff; padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; }
        .cart-total { display: flex; align-items: center; justify-content: space-between; font-size: 16px; }
        .cart-total b { font-size: 18px; color: var(--p); }
        .cart-actions { display: flex; gap: 8px; }
        .cart-actions .btn { flex: 1; padding: 12px; }

        /* Hero */
        .hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--p) 0%, var(--p2) 100%); color: #fff; }
        .hero::before, .hero::after { content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,.08); }
        .hero::before { width: 380px; height: 380px; top: -140px; right: -80px; }
        .hero::after { width: 240px; height: 240px; bottom: -120px; left: 8%; }
        .hero-in { position: relative; max-width: 1140px; margin: 0 auto; padding: 64px 24px 72px; }
        .hero h1 { font-size: clamp(30px, 5vw, 48px); line-height: 1.1; margin: 0 0 14px; font-weight: 800; letter-spacing: -1px; max-width: 560px; }
        .hero p { margin: 0 0 26px; font-size: 17px; opacity: .88; max-width: 480px; line-height: 1.55; }
        .hero a { display: inline-block; background: #fff; color: var(--p); padding: 13px 26px; border-radius: 12px;
                  font-weight: 700; text-decoration: none; box-shadow: 0 8px 20px rgba(0,0,0,.15); }
        .perks { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 32px; }
        .perks span { background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); padding: 7px 14px;
                      border-radius: 999px; font-size: 13px; font-weight: 500; }

        /* Toolbar */
        main { max-width: 1140px; margin: 0 auto; padding: 36px 24px 110px; }
        .tools { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; justify-content: space-between; margin-bottom: 26px; }
        .search { flex: 1; min-width: 220px; max-width: 360px; position: relative; }
        .search input { width: 100%; padding: 12px 16px 12px 42px; border: 1px solid var(--line); border-radius: 12px;
                        font-family: inherit; font-size: 14px; background: #fff; outline: none; }
        .search input:focus { border-color: var(--p); box-shadow: 0 0 0 3px rgba(79,70,229,.15); }
        .search::before { content: '🔍'; position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chips button { border: 1px solid var(--line); background: #fff; color: var(--muted); padding: 8px 16px; border-radius: 999px;
                        font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: .15s; }
        .chips button:hover { border-color: var(--p); color: var(--p); }
        .chips button.on { background: var(--p); border-color: var(--p); color: #fff; }

        /* Grid & card */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(235px, 1fr)); gap: 22px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 18px; overflow: hidden; display: flex; flex-direction: column;
                transition: transform .2s, box-shadow .2s; cursor: pointer; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(15,23,42,.1); }
        .pic { height: 170px; display: grid; place-items: center; font-size: 72px; position: relative; }
        .pic em { position: absolute; top: 12px; left: 12px; background: rgba(255,255,255,.85); font-style: normal;
                  font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; color: var(--ink); }
        .pic em.habis { background: #fee2e2; color: #b91c1c; }
        .info { padding: 16px; display: flex; flex-direction: column; flex: 1; }
        .kat { font-size: 12px; color: var(--muted); font-weight: 500; }
        .nama { font-weight: 700; margin: 4px 0 8px; line-height: 1.3; }
        .harga { font-size: 18px; font-weight: 800; color: var(--p); }
        .aksi { display: flex; gap: 8px; margin-top: 14px; }
        .btn { flex: 1; border: 0; padding: 10px; border-radius: 10px; font-family: inherit; font-weight: 600; font-size: 13px; cursor: pointer; transition: .15s; }
        .btn-p { background: var(--p); color: #fff; }
        .btn-p:hover { background: #4338ca; }
        .btn-p:disabled { background: #cbd5e1; cursor: not-allowed; }
        .btn-s { background: var(--p-soft); color: var(--p); }
        .btn-s:hover { background: #e0e7ff; }
        .kosong { text-align: center; color: var(--muted); padding: 60px 0; }

        /* Warna gradien per kategori */
        .g-fashion { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); }
        .g-tas { background: linear-gradient(135deg, #f3e8ff, #e9d5ff); }
        .g-elektronik { background: linear-gradient(135deg, #cffafe, #a5f3fc); }
        .g-rumah-tangga { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
        .g-aksesoris-komputer { background: linear-gradient(135deg, #fce7f3, #fbcfe8); }
        .g-default { background: linear-gradient(135deg, #e2e8f0, #cbd5e1); }

        /* Modal detail */
        .overlay { position: fixed; inset: 0; z-index: 100; background: rgba(15,23,42,.55); backdrop-filter: blur(4px);
                   display: grid; place-items: center; padding: 20px; }
        .modal { background: #fff; border-radius: 22px; width: 100%; max-width: 760px; max-height: 92vh; overflow: auto;
                 display: grid; grid-template-columns: 1fr 1fr; position: relative; box-shadow: 0 30px 60px rgba(0,0,0,.3); }
        .modal .pic { height: 100%; min-height: 320px; font-size: 130px; }
        .detail { padding: 32px 28px; display: flex; flex-direction: column; }
        .detail h2 { margin: 6px 0 10px; font-size: 24px; letter-spacing: -.5px; line-height: 1.2; }
        .detail .harga { font-size: 28px; }
        .detail p { color: var(--muted); line-height: 1.65; font-size: 14px; margin: 14px 0; }
        .badge { display: inline-block; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 999px;
                 background: #dcfce7; color: #15803d; align-self: flex-start; }
        .badge.habis { background: #fee2e2; color: #b91c1c; }
        .detail .aksi { margin-top: auto; flex-direction: column; }
        .detail .btn { padding: 13px; font-size: 14px; flex: none; }
        .x { position: absolute; top: 14px; right: 14px; width: 36px; height: 36px; border-radius: 50%; border: 0;
             background: rgba(255,255,255,.9); cursor: pointer; font-size: 16px; z-index: 2; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
        @media (max-width: 640px) {
            .modal { grid-template-columns: 1fr; }
            .modal .pic { min-height: 200px; font-size: 90px; }
        }

        footer { text-align: center; color: var(--muted); padding: 28px; font-size: 14px; border-top: 1px solid var(--line); background: #fff; }
    </style>
</head>
<body x-data="toko(@js($products->map(fn ($p) => $p->only(['id','nama','kategori','harga','stok','deskripsi','ikon']))->values()))"
      @keydown.escape.window="p = null">

    <header>
        <div class="bar">
            <div class="logo"><i>🛍️</i><span>{{ config('toko.nama') }}</span></div>
            <button class="cart" type="button" @click="keranjangTerbuka = true">🛒 Keranjang
                <b x-show="totalQty > 0" x-cloak x-text="totalQty"></b>
            </button>
        </div>
    </header>

    <section class="hero">
        <div class="hero-in">
            <h1>Belanja Mudah, Kirim Cepat ke Rumah</h1>
            <p>Pilihan produk terbaik dengan harga bersahabat. Bingung memilih? Tanya asisten kami kapan saja.</p>
            <a href="#produk">Mulai Belanja</a>
            <div class="perks">
                <span>🚚 Gratis ongkir &gt; Rp 300.000</span>
                <span>↩️ Retur 7 hari</span>
                <span>🛡️ Garansi elektronik</span>
            </div>
        </div>
    </section>

    <main id="produk">
        <div class="tools">
            <div class="chips">
                <button type="button" :class="{ on: kat === 'Semua' }" @click="kat = 'Semua'">Semua</button>
                <template x-for="k in kategori" :key="k">
                    <button type="button" :class="{ on: kat === k }" @click="kat = k" x-text="k"></button>
                </template>
            </div>
            <div class="search">
                <input type="text" x-model="cari" placeholder="Cari produk...">
            </div>
        </div>

        <div class="grid">
            <template x-for="item in daftar" :key="item.id">
                <div class="card" @click="p = item">
                    <div class="pic" :class="'g-' + slug(item.kategori)">
                        <em :class="{ habis: item.stok < 1 }" x-text="item.stok < 1 ? 'Stok habis' : 'Tersedia'"></em>
                        <span x-text="item.ikon"></span>
                    </div>
                    <div class="info">
                        <div class="kat" x-text="item.kategori"></div>
                        <div class="nama" x-text="item.nama"></div>
                        <div class="harga" x-text="rp(item.harga)"></div>
                        <div class="aksi">
                            <button type="button" class="btn btn-s" @click.stop="p = item">Lihat Detail</button>
                            <button type="button" class="btn btn-p" :disabled="item.stok < 1" @click.stop="addToCart(item)">+ 🛒</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="kosong" x-show="daftar.length === 0" x-cloak>
            <div style="font-size:48px">🔎</div>
            <p>Produk tidak ditemukan. Coba kata kunci lain, atau tanya asisten kami.</p>
        </div>
    </main>

    <footer>&copy; {{ date('Y') }} {{ config('toko.nama') }}. Semua hak dilindungi.</footer>

    {{-- Jendela detail produk --}}
    <div class="overlay" x-show="p" x-cloak x-transition.opacity @click.self="p = null">
        <template x-if="p">
            <div class="modal">
                <button type="button" class="x" @click="p = null">✕</button>
                <div class="pic" :class="'g-' + slug(p.kategori)"><span x-text="p.ikon"></span></div>
                <div class="detail">
                    <div class="kat" x-text="p.kategori"></div>
                    <h2 x-text="p.nama"></h2>
                    <div class="harga" x-text="rp(p.harga)"></div>
                    <p x-text="p.deskripsi"></p>
                    <span class="badge" :class="{ habis: p.stok < 1 }"
                          x-text="p.stok < 1 ? 'Stok habis' : 'Stok tersedia: ' + p.stok"></span>
                    <div class="aksi" style="margin-top:24px">
                        <button type="button" class="btn btn-p" :disabled="p.stok < 1" @click="addToCart(p); p = null">
                            Tambah ke Keranjang
                        </button>
                        <button type="button" class="btn btn-s" @click="tanyaBot()">💬 Tanya asisten tentang ini</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Drawer Keranjang --}}
    <div class="cart-drawer" :class="{ active: keranjangTerbuka }" x-cloak @keydown.escape.window="keranjangTerbuka = false">
        <div class="cart-backdrop" @click="keranjangTerbuka = false"></div>
        <div class="cart-panel">
            <div class="cart-head">
                <h3>Keranjang Belanja</h3>
                <button class="cart-close" type="button" @click="keranjangTerbuka = false">✕ Tutup</button>
            </div>
            <div class="cart-body">
                <template x-if="cartItems.length === 0">
                    <div class="cart-empty">
                        <div style="font-size:48px">🛒</div>
                        <p>Keranjang masih kosong. Yuk, tambahkan produk favoritmu!</p>
                    </div>
                </template>
                <template x-for="item in cartItems" :key="item.id">
                    <div class="cart-item">
                        <div class="pic-sm" :class="'g-' + slug(item.kategori)">
                            <span x-text="item.ikon"></span>
                        </div>
                        <div class="info">
                            <div class="nama" x-text="item.nama"></div>
                            <div class="harga" x-text="rp(item.harga)"></div>
                            <div style="margin-top:6px; font-size:13px; color:#64748b" x-text="'Subtotal: ' + rp(item.harga * item.qty)"></div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:8px">
                            <div class="qty">
                                <button type="button" @click="decreaseQty(item.id)">−</button>
                                <span x-text="item.qty"></span>
                                <button type="button" @click="increaseQty(item.id)" :disabled="item.qty >= item.stok">+</button>
                            </div>
                            <button class="remove" type="button" @click="removeItem(item.id)">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="cart-foot">
                <div class="cart-total">
                    <span>Total</span>
                    <b x-text="rp(totalHarga)"></b>
                </div>
                <div class="cart-actions">
                    <button class="btn btn-s" type="button" @click="clearCart()" :disabled="cartItems.length === 0">Kosongkan</button>
                    <button class="btn btn-p" type="button" :disabled="cartItems.length === 0">Checkout</button>
                </div>
                <small style="color:#64748b; text-align:center">Checkout belum diaktifkan untuk demo ini</small>
            </div>
        </div>
    </div>

    <livewire:chatbot />

    <script>
        function toko(produk) {
            return {
                produk,
                kat: 'Semua',
                cari: '',
                p: null,
                keranjang: 0,
                keranjangTerbuka: false,
                cart: {},
                get cartItems() {
                    return Object.values(this.cart).map(c => {
                        const p = this.produk.find(x => x.id === c.id);
                        return p ? { ...p, qty: c.qty } : null;
                    }).filter(Boolean);
                },
                get totalQty() {
                    return this.cartItems.reduce((sum, i) => sum + i.qty, 0);
                },
                get totalHarga() {
                    return this.cartItems.reduce((sum, i) => sum + (i.harga * i.qty), 0);
                },
                get kategori() { return [...new Set(this.produk.map(x => x.kategori))]; },
                get daftar() {
                    const q = this.cari.toLowerCase().trim();
                    return this.produk.filter(x =>
                        (this.kat === 'Semua' || x.kategori === this.kat) &&
                        (q === '' || x.nama.toLowerCase().includes(q) || x.kategori.toLowerCase().includes(q))
                    );
                },
                slug(t) { return t.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, ''); },
                rp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); },
                tanyaBot() {
                    const nama = this.p.nama;
                    this.p = null;
                    Livewire.dispatch('tanya-produk', { nama: nama });
                },
                addToCart(item) {
                    const key = item.id;
                    if (this.cart[key]) {
                        const baru = this.cart[key].qty + 1;
                        if (baru <= item.stok) this.cart[key].qty = baru;
                    } else {
                        this.cart[key] = { id: key, qty: 1 };
                    }
                    this.keranjangTerbuka = true;
                },
                increaseQty(id) {
                    const c = this.cart[id];
                    if (!c) return;
                    const p = this.produk.find(x => x.id === id);
                    if (p && c.qty < p.stok) c.qty++;
                },
                decreaseQty(id) {
                    const c = this.cart[id];
                    if (!c) return;
                    if (c.qty <= 1) delete this.cart[id];
                    else c.qty--;
                },
                removeItem(id) {
                    delete this.cart[id];
                },
                clearCart() {
                    this.cart = {};
                },
            };
        }
    </script>

    @livewireScripts
</body>
</html>
