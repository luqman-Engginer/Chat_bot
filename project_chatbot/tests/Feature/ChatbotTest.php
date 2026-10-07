<?php

use App\Livewire\Chatbot;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->seed(ProductSeeder::class));

it('menampilkan halaman utama toko beserta katalog produk', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Kaos Polos Premium')
        ->assertSee('cb-toggle', false);
});

it('menampilkan halaman chat', function () {
    $this->get('/chat')->assertOk()->assertSee('cb-toggle', false);
});

it('menyapa pengunjung saat chat dibuka', function () {
    Livewire::test(Chatbot::class)
        ->set('terbuka', true)
        ->assertSee('Halo! Saya asisten TokoKu');
});

it('menjawab pertanyaan pengguna lewat gemini', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Kaos Polos Premium harganya Rp 89.000.']]]]],
        ], 200),
    ]);

    Livewire::test(Chatbot::class)
        ->set('terbuka', true)
        ->set('input', 'Berapa harga kaos?')
        ->call('kirim')
        ->dispatch('minta-balasan')
        ->assertSet('input', '')
        ->assertSee('Berapa harga kaos?')
        ->assertSee('Kaos Polos Premium harganya Rp 89.000.');
});

it('mengirim katalog produk ke gemini sebagai prompt', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'baik.']]]]],
        ], 200),
    ]);

    Livewire::test(Chatbot::class)
        ->set('input', 'Produk apa saja yang ada?')
        ->call('kirim')
        ->dispatch('minta-balasan');

    Http::assertSent(function ($request) {
        $body = $request->data();

        return str_contains($body['system_instruction']['parts'][0]['text'], 'Kaos Polos Premium')
            && $body['contents'][0]['role'] === 'user'
            && $body['contents'][0]['parts'][0]['text'] === 'Produk apa saja yang ada?';
    });
});

it('mencoba model cadangan ketika model utama tidak tersedia', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:*' => Http::response([
            'error' => ['code' => 404, 'message' => 'model tidak tersedia'],
        ], 404),
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Ini jawaban cadangan.']]]]],
        ], 200),
    ]);

    Livewire::test(Chatbot::class)
        ->set('terbuka', true)
        ->set('input', 'Halo')
        ->call('kirim')
        ->dispatch('minta-balasan')
        ->assertSee('Ini jawaban cadangan.');

    Http::assertSent(fn ($request) => str_contains($request->url(), 'gemini-3.6-flash'));
});

it('memberi pesan ramah ketika gemini gagal total', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'error' => ['code' => 500, 'message' => 'server error'],
        ], 500),
    ]);

    Livewire::test(Chatbot::class)
        ->set('terbuka', true)
        ->set('input', 'Halo')
        ->call('kirim')
        ->dispatch('minta-balasan')
        ->assertSee('Maaf, chatbot sedang bermasalah. Coba lagi nanti.');
});

it('menolak pesan kosong', function () {
    Livewire::test(Chatbot::class)
        ->set('input', '')
        ->call('kirim')
        ->assertHasErrors(['input' => 'required']);
});

it('membuka chat dan bertanya produk dari halaman katalog', function () {
    Http::fake([
        'https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Produk tersebut bagus.']]]]],
        ], 200),
    ]);

    $produk = Product::first();

    Livewire::test(Chatbot::class)
        ->dispatch('tanya-produk', nama: $produk->nama)
        ->assertSet('terbuka', true)
        ->dispatch('minta-balasan')
        ->assertSee('Produk tersebut bagus.');
});

it('membatasi pesan berlebih per ip', function () {
    Http::fake();

    $livewire = Livewire::test(Chatbot::class);

    for ($i = 0; $i < 12; $i++) {
        $livewire->set('input', 'pesan '.$i)->call('kirim');
    }

    $livewire->set('terbuka', true)->assertSee('Terlalu banyak pesan. Tunggu sebentar ya.');

    Http::assertNothingSent();
});
