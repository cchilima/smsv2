<?php

namespace Database\Seeders;

use App\Models\Residency\Country;
use App\Models\Residency\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResidencySeeder extends Seeder
{

    public function zambiaLocationData()
    {
        $zambiaCountryId = Country::where('country', 'Zambia')->first()->id;

        Province::create([
            'name' => 'Central',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chibombo'],
                ['name' => 'Chisamba'],
                ['name' => 'Chitambo'],
                ['name' => 'Kabwe'],
                ['name' => 'Kapiri Mposhi'],
                ['name' => 'Luano'],
                ['name' => 'Mkushi'],
                ['name' => 'Mumbwa'],
                ['name' => 'Ngabwe'],
                ['name' => 'Serenje'],
                ['name' => 'Shibuyunji'],
            ]);

        Province::create([
            'name' => 'Copperbelt',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chililabombwe'],
                ['name' => 'Chingola'],
                ['name' => 'Kalulushi'],
                ['name' => 'Kitwe'],
                ['name' => 'Luanshya'],
                ['name' => 'Lufwanyama'],
                ['name' => 'Masaiti'],
                ['name' => 'Mpongwe'],
                ['name' => 'Mufulira'],
                ['name' => 'Ndola'],
            ]);

        Province::create([
            'name' => 'Eastern',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chadiza'],
                ['name' => 'Chama'],
                ['name' => 'Chasefu'],
                ['name' => 'Chipangali'],
                ['name' => 'Chipata'],
                ['name' => 'Kasenengwa'],
                ['name' => 'Katete'],
                ['name' => 'Lumezi'],
                ['name' => 'Lundazi'],
                ['name' => 'Lusangazi'],
                ['name' => 'Mambwe'],
                ['name' => 'Nyimba'],
                ['name' => 'Petauke'],
                ['name' => 'Sinda'],
                ['name' => 'Vubwi'],
            ]);

        Province::create([
            'name' => 'Luapula',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chembe'],
                ['name' => 'Chiengi'],
                ['name' => 'Chifunabuli'],
                ['name' => 'Chipili'],
                ['name' => 'Kawambwa'],
                ['name' => 'Lunga'],
                ['name' => 'Mansa'],
                ['name' => 'Milenge'],
                ['name' => 'Mwansabombwe'],
                ['name' => 'Mwense'],
                ['name' => 'Nchelenge'],
                ['name' => 'Samfya'],
            ]);

        Province::create([
            'name' => 'Lusaka',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chilanga'],
                ['name' => 'Chongwe'],
                ['name' => 'Kafue'],
                ['name' => 'Luangwa'],
                ['name' => 'Lusaka'],
                ['name' => 'Rufunsa'],
            ]);

        Province::create([
            'name' => 'Muchinga',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chinsali'],
                ['name' => 'Isoka'],
                ['name' => 'Mafinga'],
                ['name' => 'Mpika'],
                ['name' => 'Nakonde'],
                ['name' => "Shiwang'andu"],
                ['name' => 'Kanchibiya'],
                ['name' => 'Lavushimanda'],
            ]);

        Province::create([
            'name' => 'North-Western',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chavuma'],
                ['name' => 'Ikelenge'],
                ['name' => 'Kabompo'],
                ['name' => 'Kalumbila'],
                ['name' => 'Kasempa'],
                ['name' => 'Manyinga'],
                ['name' => 'Mufumbwe'],
                ['name' => 'Mushindamo'],
                ['name' => 'Mwinilunga'],
                ['name' => 'Solwezi'],
                ['name' => 'Zambezi'],
            ]);

        Province::create([
            'name' => 'Northern',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chilubi'],
                ['name' => 'Kaputa'],
                ['name' => 'Kasama'],
                ['name' => 'Luwingu'],
                ['name' => 'Mbala'],
                ['name' => 'Mporokoso'],
                ['name' => 'Mpulungu'],
                ['name' => 'Mungwi'],
                ['name' => 'Lupososhi'],
                ['name' => 'Senga Hill'],
                ['name' => 'Lunte'],
                ['name' => 'Nsama'],
            ]);

        Province::create([
            'name' => 'Southern',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Chikankata'],
                ['name' => 'Chirundu'],
                ['name' => 'Choma'],
                ['name' => 'Gwembe'],
                ['name' => 'Itezhi-Tezhi'],
                ['name' => 'Kalomo'],
                ['name' => 'Kazungula'],
                ['name' => 'Livingstone'],
                ['name' => 'Mazabuka'],
                ['name' => 'Monze'],
                ['name' => 'Namwala'],
                ['name' => 'Pemba'],
                ['name' => 'Siavonga'],
                ['name' => 'Sinazongwe'],
                ['name' => 'Zimba'],
            ]);

        Province::create([
            'name' => 'Western',
            'country_id' => $zambiaCountryId
        ])->towns()
            ->createMany([
                ['name' => 'Kalabo'],
                ['name' => 'Kaoma'],
                ['name' => 'Limulunga'],
                ['name' => 'Luampa'],
                ['name' => 'Lukulu'],
                ['name' => 'Mitete'],
                ['name' => 'Mongu'],
                ['name' => 'Mulobezi'],
                ['name' => 'Mwandi'],
                ['name' => 'Nalolo'],
                ['name' => 'Nkeyema'],
                ['name' => 'Senanga'],
                ['name' => 'Sesheke'],
                ['name' => 'Shangombo'],
                ['name' => 'Sikongo'],
                ['name' => 'Sioma'],
            ]);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->zambiaLocationData();
    }
}
