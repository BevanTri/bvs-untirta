<?php

namespace App\Console\Commands;

use App\Models\BrandPartner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ScrapeBrandPartners extends Command
{
    protected $signature = 'scrape:brand-partners';
    protected $description = 'Scrape brand partner logos from si-tepat.com';

    public function handle()
    {
        $brands = [
            ['name' => 'AHM Oil', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/ahm-oil-q3au2d36vjkhus018x9575cn7shesf60ygikufjuqg.jpg'],
            ['name' => 'Aspira', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/aspira-1-q3au3unzp3lu8ju7k2evn9tx2r5bz62q5tp977cevs.jpg'],
            ['name' => 'Aspira Premium', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/aspira-q3atzjkmdfpj0w3hppdfpuyv26drp3z0ki250jqdeg.jpg'],
            ['name' => 'Aspira Premio', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/aspira-premio-q3atzb42nxdy4efs33pslf3pppjgru1fjc6rp22wyg.jpg'],
            ['name' => 'Asetubrux', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/asetubrux-q3au47tqcs3ur3b3f83nm6ide5cgyxiyvmu1x2swgo.jpg'],
            ['name' => 'Battlax', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/battlax-q3atzp7nifx8yjvasrt74tjmmhlyzalel9z1w7i0d4.jpg'],
            ['name' => 'BMC', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/bmc-q3au0wg88tkfsm4hsai1bimty7outdd237zdyxpwew.jpg'],
            ['name' => 'Continental', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/contin-q3atyhz2s2a24hm3t94cy4gfarj357tr3byptha4bc.jpg'],
            ['name' => 'ECGO', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/ecgo-q3au1g6u8cbgkfbtl1179vnifazkb0jf5xol1qwms8.jpg'],
            ['name' => 'Eneos', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/eneos-q3au2gujmvpn57ukmyvnh4ehlbyvn7kyaz4irjea1k.jpg'],
            ['name' => 'FDR', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/fdr-q3atzuuong4yw7n3vu8yjs4e6su69h7sm1vyrv9nbs.jpg'],
            ['name' => 'Federal Oil', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/federal-oil-q3au2mhkrvxd2vmdq1bew2z95n72xe7cbr1fn75x08.jpg'],
            ['name' => 'Fed-G', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/FED-G-qoj1waxud60k8b29dzh2om1s1h38ghhvvpwaud4xug.png'],
            ['name' => 'Gates', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/GATES-LOGO-01-qotephltwultsk1j3d70ptmt25ry9mo6gf27a1eu6w.png'],
            ['name' => 'Goda', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/goda-q3au1ltvdcj6i33mo3gyou89zm7rl75t6plhxeo9qw.jpg'],
            ['name' => 'Goodrich', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/goodrich-q3au1qj2bipm44wswni3jb1kyjklnoogvcuxbshavs.jpg'],
            ['name' => 'Grand', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/grand-q3au2r6rq23soxfjylcjqjsk4kjwzvq00eav1kyy54.jpg'],
            ['name' => 'GS Battery', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/gs-baterry-q3aty1ztjvo6n49bek7p9qhl77puid2bd4vgnrxt94.jpg'],
            ['name' => 'Honda', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/honda-q3au1w63gixc1solzpxuy9mciussxvauw4ru7g8xug.jpg'],
            ['name' => 'Honda GP', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/HONDA-GP-q3au3zd6n9s9ulndsmg0hqn81oi61nldugyoll5g0o.jpg'],
            ['name' => 'INK', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/ink-q3au1239dts5q9wavcxsqh7liix23jzg3zwaulhjdk.jpg'],
            ['name' => 'Ipone', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/ipone-2-1-qoteqhbp4jyq1qlnasmuckmbmsvwe4lz9bunijxvmg.png'],
            ['name' => 'IRC Tire', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/irc-tire-q3au00hpsgcotvewywopyqp5r42djnu6mtsvnj1aag.jpg'],
            ['name' => 'JM', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/jm-q3au0rr1ane06kbbjqgwh1tizac0qvueekpykjwva0.jpg'],
            ['name' => 'KYT', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/kyt-q3au15um55xb0pqu9ekb0g9fw2eiycedgii8rpbyoo.jpg'],
            ['name' => 'M-ONE', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/M-ONE-01-q3au0oxiq5a57qff0790rkj574px3sj7e6ri4q11so.jpg'],
            ['name' => 'Maxxis', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/maxxis-q3au072l4alp355cwhj3y71dwt5y1jkazqda0grj2w.jpg'],
            ['name' => 'MDS', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/mds-q3au1bhna650ydinch02feu7gdmq8j0rhaf5nd3lnc.jpg'],
            ['name' => 'Michelin', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/michelin-q3au0brs2gs4p6yj51k8snuovqis412yodmpeukk7s.jpg'],
            ['name' => 'Motul', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/motul-q3au2uy4he8xzda3cmz20iuei41duo4xcwwsyotdg8.jpg'],
            ['name' => 'Pirelli', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/pirelli-q3au0het7gzumuqc84007mfgg1qze7pcp5jmaic76g.jpg'],
            ['name' => 'Repsol', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/repsol-q3au3gkeul2jeeeouebh3ve05z2trpir3vwz01xbh4.jpg'],
            ['name' => 'Selis', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/selis-q3au20vaep3rnuhs89yzsqfnhs5n0ctiks19lu1yzc.jpg'],
            ['name' => 'Shell', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/OLI-SHELL-scaled-q3au34cidqlt7gwftr1bpgh0fyr1zn68q7fnrgffq0.jpg'],
            ['name' => 'UWinFly', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/uwinfly-q3au25khcva79waygu04n78ygpih2uc69fap07v048.jpg'],
            ['name' => 'Varioli', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/varioli-q3au3l9lsr8z0g7v2yclyc7b4wfnu71esj6eefqcm0.jpg'],
            ['name' => 'Yamaha', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/yamaha-1-q3gftitma8abqjuuhypphtd5bjanngemjrcad4e53s.jpg'],
            ['name' => 'Yamaha Genuine Part', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/yamaha-genuine-part-q3au442dlfypgngk16h5c7gj0lv04541j483zyyh5k.jpg'],
            ['name' => 'Yamalube', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/yamalube-q3au3pysqxfemi11bidqst0m3tshwok2h6ftstjdqw.jpg'],
            ['name' => 'Yokoba', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/yokoba-q3aty7muovvwks14hmngop2criy1sjopdwsdjfpg7s.jpg'],
            ['name' => 'Yuasa', 'url' => 'https://si-tepat.com/wp-content/uploads/elementor/thumbs/yuasa-q3atycc1n22c6tuaq6olj5vnqgavv17d2k1sxtihco.jpg'],
        ];

        $bar = $this->output->createProgressBar(count($brands));
        $bar->start();

        $imported = 0;
        foreach ($brands as $brand) {
            if (BrandPartner::where('name', $brand['name'])->exists()) {
                $bar->advance();
                continue;
            }

            try {
                $ext = pathinfo(parse_url($brand['url'], PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = 'brands/' . str()->slug($brand['name']) . '.' . $ext;
                $response = Http::timeout(15)->get($brand['url']);

                if ($response->successful()) {
                    Storage::disk('public')->put($filename, $response->body());
                    BrandPartner::create([
                        'name' => $brand['name'],
                        'logo' => $filename,
                        'is_active' => true,
                    ]);
                    $imported++;
                } else {
                    $this->warn("\nGagal download: {$brand['name']}");
                }
            } catch (\Exception $e) {
                $this->warn("\nError {$brand['name']}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Selesai! {$imported} brand baru ditambahkan.");
    }
}
