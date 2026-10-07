<div>
    <style>
        .cb-toggle { position: fixed; bottom: 22px; right: 22px; width: 60px; height: 60px; border-radius: 50%; border: 0;
                     background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; font-size: 26px; cursor: pointer;
                     z-index: 120; box-shadow: 0 10px 25px rgba(79,70,229,.45); transition: transform .15s; }
        .cb-toggle:hover { transform: scale(1.07); }
        .cb-box { position: fixed; bottom: 96px; right: 22px; width: 370px; max-width: calc(100vw - 32px); height: 540px;
                  max-height: calc(100vh - 130px); background: #fff; border-radius: 20px; display: flex; flex-direction: column;
                  overflow: hidden; box-shadow: 0 25px 60px rgba(15,23,42,.3); z-index: 120; font-family: 'Inter', system-ui, sans-serif; }
        .cb-head { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; padding: 16px; display: flex; align-items: center; gap: 12px; }
        .cb-ava { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,.2); display: grid; place-items: center; font-size: 20px; }
        .cb-head b { display: block; font-size: 15px; }
        .cb-head small { opacity: .85; font-size: 12px; display: flex; align-items: center; gap: 5px; }
        .cb-head small::before { content: ''; width: 7px; height: 7px; background: #4ade80; border-radius: 50%; }
        .cb-close { margin-left: auto; background: rgba(255,255,255,.18); border: 0; color: #fff; width: 30px; height: 30px;
                    border-radius: 50%; cursor: pointer; }
        .cb-msgs { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 10px; background: #f8fafc; }
        .cb-m { max-width: 82%; padding: 10px 14px; border-radius: 16px; line-height: 1.5; font-size: 14px; white-space: pre-wrap; word-wrap: break-word; }
        .cb-user { align-self: flex-end; background: linear-gradient(135deg, #4f46e5, #6d5ef0); color: #fff; border-bottom-right-radius: 4px; }
        .cb-bot { align-self: flex-start; background: #fff; color: #0f172a; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; }
        .cb-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 2px; }
        .cb-chips button { border: 1px solid #c7d2fe; background: #eef2ff; color: #4338ca; border-radius: 999px; padding: 7px 13px;
                           font-size: 12.5px; font-weight: 600; cursor: pointer; font-family: inherit; }
        .cb-chips button:hover { background: #e0e7ff; }
        .cb-dots { align-self: flex-start; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; border-bottom-left-radius: 4px;
                   padding: 12px 16px; gap: 5px; }
        .cb-dots i { width: 7px; height: 7px; background: #94a3b8; border-radius: 50%; animation: cbb 1.2s infinite; }
        .cb-dots i:nth-child(2) { animation-delay: .15s; }
        .cb-dots i:nth-child(3) { animation-delay: .3s; }
        @keyframes cbb { 0%, 60%, 100% { transform: translateY(0); opacity: .5; } 30% { transform: translateY(-5px); opacity: 1; } }
        .cb-form { display: flex; gap: 8px; padding: 12px; border-top: 1px solid #e2e8f0; background: #fff; }
        .cb-form input { flex: 1; border: 1px solid #e2e8f0; border-radius: 12px; padding: 11px 14px; outline: none; font-size: 14px; font-family: inherit; }
        .cb-form input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.15); }
        .cb-form button { border: 0; background: #4f46e5; color: #fff; border-radius: 12px; padding: 0 18px; cursor: pointer;
                          font-weight: 600; font-family: inherit; }
        .cb-form button:disabled { background: #a5b4fc; cursor: wait; }
    </style>

    <button class="cb-toggle" wire:click="$toggle('terbuka')">{{ $terbuka ? '✕' : '💬' }}</button>

    @if ($terbuka)
        <div class="cb-box">
            <div class="cb-head">
                <div class="cb-ava">🤖</div>
                <div>
                    <b>Asisten {{ config('toko.nama') }}</b>
                    <small>Online</small>
                </div>
                <button class="cb-close" wire:click="$set('terbuka', false)">✕</button>
            </div>

            <div class="cb-msgs" x-data x-effect="$nextTick(() => $el.scrollTop = $el.scrollHeight)">
                @foreach ($pesan as $p)
                    <div class="cb-m {{ $p['role'] === 'user' ? 'cb-user' : 'cb-bot' }}">{{ $p['text'] }}</div>
                @endforeach

                @if (count($pesan) === 1)
                    <div class="cb-chips">
                        <button wire:click="tanya('Produk apa yang paling laris?')">⭐ Produk andalan</button>
                        <button wire:click="tanya('Berapa ongkir dan lama pengiriman?')">🚚 Ongkir</button>
                        <button wire:click="tanya('Bagaimana cara retur barang?')">↩️ Retur</button>
                        <button wire:click="tanya('Metode pembayaran apa saja?')">💳 Pembayaran</button>
                    </div>
                @endif

                <div class="cb-m cb-dots" wire:loading.flex wire:target="kirim, balas"><i></i><i></i><i></i></div>
            </div>

            <form class="cb-form" wire:submit="kirim">
                <input type="text" wire:model="input" placeholder="Tanya produk atau pengiriman..." autocomplete="off" maxlength="500">
                <button type="submit" wire:loading.attr="disabled" wire:target="kirim, balas">Kirim</button>
            </form>
        </div>
    @endif
</div>
